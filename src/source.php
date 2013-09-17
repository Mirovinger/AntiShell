<?php
/**
 * =============================================================================
 * AntiShell - Скрипт для котроля за изменениями в файлах на сайте.
 * =============================================================================
 * Автор кода: Sander 
 * URL: http://sandev.pro/
 * ICQ: 404-037-556 
 * email: olalod@mail.ru
 * -----------------------------------------------------------------------------
 * Идея, доработка внешнего вида, документирование: ПафНутиЙ
 * URL: http://pafnuty.name/
 * ICQ: 817233
 * email: pafnuty10@gmail.com
 * =============================================================================
 * Версия: [version_id] ([version_date])
 * =============================================================================
 */ 

@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
header('Content-type: text/html; charset=[doc_charset]');

//////////////////////////// Настройки ////////////////////////////
$config             = array(
	// Вкл/выкл
	'on' => true,
	
	// Подпись в письме
	'sitename' => '[sitename]',
	
	// Начальный путь проверки. '' - корень сайта
	// Для сканирования отдельной папки: '/folderName'
	'path' => '[path]',
	
	// Куда сохранять результат скана системы.
	// Путь от корня сайта.
	'scanfile' => '[scanfile]',
	
	// Список расширений файлов, которые необходимо проверять. '' - означает любые расширения. Расширения указывать без точек через запятую
	// Например, 'php,cgi,pl,perl,php3,php4,php5,php6,tpl,js,htaccess,htm,html,css,swf,txt,db,lng',
	'ext' => '[ext]',
	
	// Список расширений файлов, которые не надо учитывать при проверке. Расширения указывать без точек через запятую
	// А также можно указывать имена файлов, которые тоже не надо учитывать. Например, 'skipfile' => 'index.php,jpg', - здесь не будут учитываться файлы с именами 'index.php' и все файлы с расширением JPG
	'skipfile' => '[skipfile]',
	
	// Список папок, которые не надо проверять. Путь указывается относительно значения переменной 'path'. Перечилять папки через запятую
	// Например, 'skipdir' => '/folder,/files/web',
	'skipdir' => '[skipdir]',
	
	// Email, на который отправлять отчеты
	'email' => '[email]',

	// Email отправителя
	// Если не задан - будет взят из предыдущего параметра
	'from_email' => '[from_email]',
	
	// Отображать на экране статистику проверки? На почту в любом случае будет отправляться.
	'showtext' => 1,

	// Путь к файлу с картинками-индикаторами
	// Можно скопировать файл себе на хостинг и вставить сылку на него сюда.
	'icon_url' => '[icon_url]'
);

if(!$config['on']) die("Wat?");
$config['ext']		= str2array($config['ext']);
$config['skipfile']	= str2array($config['skipfile']);
$config['skipdir']	= str2array($config['skipdir']);

//////////////////// ДАЛЬШЕ ТРОГАТЬ НЕ НУЖНО //////////////////////

$time_start  = microtime(true);


// $root        = explode($_SERVER['HTTP_HOST'], dirname(__FILE__));
// $script_path = array_pop($root);
// $root_dir    = implode($_SERVER['HTTP_HOST'], $root) . $_SERVER['HTTP_HOST'];
define('ROOT_DIR', '[root_dir]');
// unset($root_dir);


/**
 * Преобразуем строку в массив
 * @param string $array - входящая строка
 * @param $delimetr - разделитель массива
 * @return array()
 */
function str2array($array, $delimetr = ',')
{
	if (!$array OR $array == '*')
		return false;
	$earr = explode($delimetr, $array);
	$narr = array();
	foreach ($earr as $v) {
		$v = trim($v);
		if ($v)
			$narr[] = $v;
	}
	return $narr;
}
/**
 * Запуск сканирования
 * @param $dir - Путь к сканируемой папке
 * @param $subdir - подпапки
 * @return file
 */
function do_scan($dir, $subdir = '')
{
	global $config, $scan;
	$scandir = scandir($dir . $subdir);
	foreach ($scandir as $f) {
		if ($f != '.' AND $f != '..') {
			$file = $dir . $subdir . '/' . $f;
			if ($config['skipdir'] AND in_array($subdir, $config['skipdir']))
				continue;
			if (is_dir($file)) {
				$scan = do_scan($dir, $subdir . '/' . $f, $scan);
			} else {
				$ext = array_pop(explode(".", $f));
				if ($config['skipfile'] AND (in_array($ext, $config['skipfile']) OR in_array($f, $config['skipfile'])))
					continue;
				$scan[] = $file . "|" . filectime($file) . "|" . md5($file . filesize($file));
			}
		}
	}
	return $scan;
}
/**
 * Отправка уведомления на email
 * @param string $buffer - что отправляем
 * @param $subject - Тема сообщения
 * @return type
 */
function mailfromsite($buffer, $subject, $from_email, $email, $text = "На сайте изменены файлы")
{
	$set_mail = (trim($from_email) !='') ? $from_email : $email;

	if (trim($subject) != '')
		$from = mime_encode($subject) . " <" . $set_mail . ">";
	else
		$from = "<" . $set_mail . ">";

	$buffer  = str_replace("\r", "", $buffer);
	$headers = "From: " . $from . "\r\n";
	$headers .= "X-Mailer: ANTI-SHELL\r\n";
	$headers .= "Content-Type: text/html; charset=[doc_charset]\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit\r\n";
	$headers .= "X-Priority: 1 (Highest)";

	return mail($email, mime_encode($text), $buffer, $headers);
}
/**
 * Преобразование кодировки в кодировку )))
 * @param string $text 
 * @param $charset 
 * @return string
 */
function mime_encode($text, $charset = "[doc_charset]")
{
	return "=?" . $charset . "?B?" . base64_encode($text) . "?=";
}
/**
 * Функция для установки правильного окончания слов
 * @param int $n - число, для которого будет расчитано окончание
 * @param string $words - варианты окончаний для (1 комментарий, 2 комментария, 100 комментариев) 
 * @return string - слово с правильным окончанием
 */
function wordSpan($n = 0, $words) {
	$words	= explode('|', $words);
	$n		= intval($n);
	return  $n%10==1&&$n%100!=11?$words[0].$words[1]:($n%10>=2&&$n%10<=4&&($n%100<10||$n%100>=20)?$words[0].$words[2]:$words[0].$words[3]);
}

/**
 * Назначение стилей спискам.
 * @param $style - стиль строки
 * @param $time - время
 * @param $file - адрес файла
 * @param $icon_url - адрес картинки со спрайтом
 * @return string
 */
function listStyler($style = 'change', $time, $file, $icon_url)
{
	$color = '#7f8c8d';
	$li_title = '';

	switch ($style) {
		case 'add':
			$bgPosition = 'no-repeat 10px 5px';
			$color = '#16a085';
			$li_title = 'Добавлен файл';
			break;

		case 'del':
			$bgPosition = 'no-repeat 10px -51px';
			$color = '#7f8c8d';
			$li_title = 'Удален файл';
			break;

		case 'change':
			$bgPosition = 'no-repeat 10px -23px';
			$color = '#c0392b';
			$li_title = 'Изменен файл';
			break;
	}

	$def_style = "display:block;height:24px;line-height:24px;font-family:Arial,sans-serif;padding:2px 10px 2px 38px;border-bottom:1px solid #bdc3c7;font-size:14px;color:#7f8c8d; background: url({$icon_url}) {$bgPosition}";

	$span_style = "display:block; float:left; padding-right:10px; margin-right:10px; border-right: 1px solid #bdc3c7; font-size: 12px;";

	$li = "<li style='{$def_style}' title='{$li_title}'><span style='{$span_style}'>{$time}</span> <span style='color:{$color}'>{$file}</span></li>";

	return $li;
}

$scan = do_scan(ROOT_DIR . $config['path']);
file_put_contents(ROOT_DIR . $config['scanfile'] . ".tmp", serialize($scan), LOCK_EX);

if (file_exists(ROOT_DIR . $config['scanfile'])) {
	$oscan  = unserialize(file_get_contents(ROOT_DIR . $config['scanfile']));
	$ioscan = implode("\n", $oscan);
	$iscan  = implode("\n", $scan);
	
	$edit_diff = array_diff($scan, $oscan);
	$edit      = array();
	$i_change = 0;
	$i_add = 0;
	$i_del = 0;
	foreach ($edit_diff as $e) {
		$e = explode("|", $e);
		$f = array_pop(explode(ROOT_DIR, $e[0]));
		$d = date("Y-m-d H:i:s", $e[1]);
		if (strpos($ioscan, $e[0]) !== false) {
			$edit[$f] = listStyler('change', $d, $f, $config['icon_url']);
			$i_change++;
		} else {
			$edit[$f] = listStyler('add', $d, $f, $config['icon_url']);
			$i_add++;
		}
	}
	
	$del_diff = array_diff($oscan, $scan);
	foreach ($del_diff as $e) {
		$e = explode("|", $e);
		$f = array_pop(explode(ROOT_DIR, $e[0]));
		$d = date("Y-m-d H:i:s", $e[1]);
		if (strpos($iscan, $e[0]) === false) {
			$edit[$f] = listStyler('del', $d, $f, $config['icon_url']);
			$i_del++;
		}
	}
	arsort($edit);
	unset($edit_diff, $del_diff);
	if ($edit) {
		$editted		= count($edit);
		$site_date		= date("j.m.Y в H:i:s",filemtime(ROOT_DIR.$config['scanfile'].".tmp"));
		$total_files	= count($scan);
		$logs			= implode("\n\t",$edit);
		$time			= round(microtime(true)-$time_start,4);
		$i_change_text	= wordSpan($i_change, 'фай|л изменён|ла изменено|лов изменено');
		$i_add_text		= wordSpan($i_add, 'фай|л добавлен|ла добавлено|лов добавлено');
		$i_del_text		= wordSpan($i_del, 'фай|л удалён|ла удалены|лов удалено');
		$__wd1			= wordSpan($total_files, 'фай|л|ла|лов');

		$logs = <<<HTML
<title>{$config['sitename']}</title>
<body style="background-color:#ecf0f1; max-width: 800px; margin: 0 auto;padding:0;">
	<h1 style="font:normal 22px 'Trebuchet MS',Arial,sans-serif;color:#2980b9;padding:40px 10px 10px;text-align: center;">{$config['sitename']} - Сканирование завершено</h1>
	<div style="background-color:#ecf0f1;font:normal 16px 'Trebuchet MS',Arial,sans-serif;color:#7f8c8d;margin:0;padding:5px 5px 35px 5px;">
		<ul style="list-style:none;margin:0;padding:0;margin-bottom:15px;">
			{$logs}
		</ul>
		<p>Снимок создан <b>{$site_date}</b></p>
		<p>
			Всего отсканировано <b>{$total_files}</b> {$__wd1}, из них:
			<br>- <b>{$i_change}</b> {$i_change_text}
			<br>- <b>{$i_add}</b> {$i_add_text}
			<br>- <b>{$i_del}</b> {$i_del_text}
		</p>
		<p>Сканирование выполнено за <b>{$time} сек.</b></p>
		<br/>
		<p>Запустил IP: <b>{$_SERVER['REMOTE_ADDR']}</b></p>
	</div>
</body>
HTML;
		$logs = str_replace("<p>","<p style='color:#34495e;margin:0;padding:0 38px;'>",$logs);
		$logs = str_replace("<b>","<b style='color:#2c3e50;'>",$logs);

		mailfromsite($logs, $config['sitename'], $config['from_email'], $config['email']);
	} else 
		$logs = "Файлы не менялись";

	if($config['showtext']) 
		echo $logs;
	@unlink(ROOT_DIR.$config['scanfile']);

} else {
	@rename(ROOT_DIR.$config['scanfile'].".tmp",ROOT_DIR.$config['scanfile']);
	if (file_exists(ROOT_DIR.$config['scanfile'])) {
		echo "\n\n<br/><br/>Файл снимка успешно создан ".date("Y-m-d в H:i:s");
		echo "\n<br/>Время выполнения: ".(microtime(true)-$time_start)." сек.";
	} else {
		echo "Файл снимка не создан! Возможно не хватает прав. Установите на папку, содержащую снимок права на запись (CHMOD 777). Если после установки нужных прав это сообщение появляется вновь - обратитесь за помошью на сайт <a href='http://antishell.ru/' target='_blank'>antishell.ru</a>";
	}
}

@rename(ROOT_DIR.$config['scanfile'].".tmp",ROOT_DIR.$config['scanfile']);

?>
