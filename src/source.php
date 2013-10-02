<?php
/**
 * =============================================================================
 * AntiShell - Скрипт для котроля за изменениями в файлах на сайте.
 * =============================================================================
 * Автор кода основных методов: Sander 
 * URL: http://sandev.pro/
 * ICQ: 404-037-556 
 * email: olalod@mail.ru
 * -----------------------------------------------------------------------------
 * Идея, доработка внешнего вида, переработка кода и документирование: ПафНутиЙ
 * URL: http://pafnuty.name/
 * ICQ: 817233
 * email: pafnuty10@gmail.com
 * =============================================================================
 * Версия: [version_id] ([version_date])
 * =============================================================================
 */ 

/////////// Настройки скрипта ///////////
$config = array(
	// Вкл/выкл
	// Настройка по большому счёту не нужная, но мало ли, вдруг нужно будет отрубить временно скрипт, тогда ставим 'on' => false;
	'on' => true,

	// Кодровка cайта
	// Задаётся для отправки писем
	'charset' => '[doc_charset]',

	// Корень сайта
	'root_dir' => '[root_dir]',

	// Название сайта
	// Будет указано в качестве имени автора письма
	'sitename' => '[sitename]',
	
	// Начальный путь проверки. '' - корень сайта
	// Для сканирования отдельной папки: '/folderName'
	'path' => '[path]',
	
	// Куда сохранять результат скана системы.
	// Путь от корня сайта.
	'scanfile' => '[scanfile]',

	// Создавать снимок сразу по окочании сканирования
	// Для отключения необходимо заменить на false
	// Для ручного создания снимка при отключенном автоматическом создании нужно запустить скрипт с параметром snap=y (http://site.com/antishell.php?snap=y)
	'allowsnap' => [allowsnap],
	
	// Список расширений файлов, которые необходимо проверять. '' - означает любые расширения. Расширения указывать без точек через запятую
	// Например, 'php,cgi,pl,perl,php3,php4,php5,php6,tpl,js,htaccess,htm,html,css,swf,txt,db,lng',
	'ext' => '[ext]',
	
	// Список расширений файлов, которые не надо учитывать при проверке. Расширения указывать без точек через запятую
	// А также можно указывать имена файлов, которые тоже не надо учитывать. Например, 'skipfile' => 'index.php,jpg', - здесь не будут учитываться файлы с именами 'index.php' и все файлы с расширением JPG
	// В этот список автоматически добавляется файл снимка.
	// 'skipfile' => 'jpg,jpeg,gif,bmp,png,rar,zip,tmp,gz,xml,flv,exe,txt,doc,pdf,avi,mp3,mp4,wmv,m4v,m4a,mov,3gp,f4v,3gp,mpg,mpeg',
	'skipfile' => '[skipfile]',
	
	// Список папок, которые не надо проверять. Путь указывается относительно значения переменной 'path'. Перечилять папки через запятую
	// Например, 'skipdir' => '/folder,/files/web',
	'skipdir' => '[skipdir]',
	
	// Email, на который отправлять отчеты
	// Можно указывать несколько адресов через запятую, на каждый адрес будет выслано отдельное письмо
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
///////// Конец настроек скрипта /////////



/**
 * ВНИМАНИЕ!
 * Если не знаете что делаете - не трогайте код ниже!
 */

if(!$config['on']) die("Wat?");

$config['makesnap'] = ($_GET['snap']) ? true : false ;

$time_start  = microtime(true);

class AntiShell {
	// Тут читый паттерн singleton, объяснять нечего, тупо взят из википедии)
	protected static $instance;
	private function __construct() {} 
	private function __clone() {} 
	private function __wakeup() {} 

	public static function getInstance() { 
		if (!isset(self::$instance)) {
			$class          = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	/**
	 * Конфигуратор
	 * @param array $cfg 
	 * @return array
	 */
	public function setConfig($cfg) {
		$this->config = $cfg;
	}

	/**
	 * Преобразуем строку в массив
	 * @param string $array - входящая строка
	 * @param $delimetr - разделитель массива
	 * @return array()
	 */
	public function str2array($array, $delimetr = ',') {
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
	 * Основной метод класса AntiShell
	 */
	public function runAntiShell($config) {
		$output = '';
		$this->setConfig($config);

		// Преобразуем нужные строки конфига в массив для дальнейшей работы.
		$this->config['ext']      = $this->str2array($this->config['ext']);
		$this->config['skipfile'] = $this->str2array($this->config['skipfile'].','.basename($this->config['root_dir'].$this->config['scanfile']));
		$this->config['skipdir']  = $this->str2array($this->config['skipdir']);

		// Запускаем канирование
		$scan = $this->doScan($this->config['root_dir'] . $this->config['path']);

		// Пишем в файл
		$makeFile = $this->makeFile($scan);

		// Определяем выводимый контент и заголовок письа
		$status = $makeFile['status'];
		$allowMail = false;
		// Разные статусы сканирования
		switch ($status) {
			case '1':
				$title = 'На сайте изменены файлы.';
				$allowMail = true;
				break;

			case '2':
				$title = 'Файлы не менялись.';
				$allowMail = false;
				break;

			case '3':
				$title = 'Файл снимка успешно создан.';
				$allowMail = true;
				break;

			case '4':
				$title = 'Ошибка при создании файла со снимком.';
				$allowMail = true;
				break;
		}

		// Определяем, что будет в контенте
		$content = $makeFile['text'].$this->showStat();

		// Суём контент в шаблон для вывода
		$output = $this->template($this->config['sitename'], $content);

		// Отправляем уведомление на почту.
		if ($allowMail) {
			$mailArr = $this->str2array($this->config['email']);
			$fromMailArr = $this->str2array($this->config['from_email']);
			foreach ($mailArr as $_mail) {
				$this->mailFromSite($output, $this->config['sitename'], $fromMailArr[0], $_mail, $title);
			}
		}

		// Выводим результаты в браузер
		if ($this->config['showtext']) {
			$this->showOutput($output, $this->config['charset']); 
		}
	}

	/**
	 * Запуск сканирования
	 * @param $dir - Путь к сканируемой папке
	 * @param $subdir - подпапка
	 * @return file
	 */
	public function doScan($dir, $subdir = '') {
		global $scan;
		$scandir = scandir($dir . $subdir);
		foreach ($scandir as $f) {
			if ($f != '.' AND $f != '..') {
				$file = $dir . $subdir . '/' . $f;
				if ($this->config['skipdir'] AND in_array($subdir, $this->config['skipdir']))
					continue;
				if (is_dir($file)) {
					$scan = $this->doScan($dir, $subdir . '/' . $f, $scan);
				} else {
					$ext = pathinfo($f, PATHINFO_EXTENSION);

					if ($this->config['skipfile'] AND (in_array($ext, $this->config['skipfile']) OR in_array($f, $this->config['skipfile'])))
						continue;
					$scan[] = $file . "|" . filectime($file) . "|" . md5($file . filesize($file));
				}
			}
		}
		return $scan;
	}

	/**
	 * Метод, создающий файл снимка
	 * @param array $scan - массив с результатами из метода doScan
	 * @return array - возвращает статус процесса и текст результата
	 */
	public function makeFile($scan) {
		$makeFile    = array();
		$total_files = count($scan);
		$tf_text     = $this->wordSpan($total_files, 'фай|л|ла|лов');

		file_put_contents($this->config['root_dir'] . $this->config['scanfile'] . '.tmp', serialize($scan), LOCK_EX);
		if (file_exists($this->config['root_dir'] . $this->config['scanfile'])) {
			$oscan  = unserialize(file_get_contents($this->config['root_dir'] . $this->config['scanfile']));
			$ioscan = implode("\n", $oscan);
			$iscan  = implode("\n", $scan);
			
			$edit_diff = array_diff($scan, $oscan);
			$edit      = array();
			$i_change  = 0;
			$i_add     = 0;
			$i_del     = 0;
			foreach ($edit_diff as $_e) {
				$e = explode("|", $_e);
				$_f = explode($this->config['root_dir'], $e[0]);
				$f = array_pop($_f);
				$d = date("Y-m-d H:i:s", $e[1]);
				if (strpos($ioscan, $e[0]) !== false) {
					$edit[$f] = $this->listStyler('change', $d, $f);
					$i_change++;
				} else {
					$edit[$f] = $this->listStyler('add', $d, $f);
					$i_add++;
				}
			}
			
			$del_diff = array_diff($oscan, $scan);
			foreach ($del_diff as $_e) {
				$e  = explode("|", $_e);
				$_f = explode($this->config['root_dir'], $e[0]);
				$f  = array_pop($_f);
				$d  = date("Y-m-d H:i:s", $e[1]);
				if (strpos($iscan, $e[0]) === false) {
					$edit[$f] = $this->listStyler('del', $d, $f);
					$i_del++;
				}
			}
			arsort($edit);
			unset($edit_diff, $del_diff);
			if ($edit) {
				$editted		= count($edit);
				$snap_date		= date("j.m.Y в H:i:s",filemtime($this->config['root_dir'].$this->config['scanfile'].'.tmp'));
				$logs			= implode("\n\t",$edit);
				$i_change_text	= $this->wordSpan($i_change, 'фай|л изменён|ла изменено|лов изменено');
				$i_add_text		= $this->wordSpan($i_add, 'фай|л добавлен|ла добавлено|лов добавлено');
				$i_del_text		= $this->wordSpan($i_del, 'фай|л удалён|ла удалены|лов удалено');
				$snap_info = ($this->config['makesnap'] || $this->config['allowsnap']) ? "<p>Снимок создан <b>{$snap_date}</b></p>" : "<p>Дата сканирования: <b>{$snap_date}</b></p>";

				$makeFile['status'] = '1';
				$makeFile['text'] = "<h1 style=\"font:normal 22px 'Trebuchet MS',Arial,sans-serif;color:#2980b9;padding:40px 10px 10px;text-align: center;\">{$this->config['sitename']} - Сканирование завершено</h1>
				<ul style='list-style:none;margin:0;padding:0;margin-bottom:15px;'>
					{$logs}
				</ul>
				<div style='color: #34495e; line-height: 22px !important; margin-left: 40px;'>
					{$snap_info}
					<p>
						Всего отсканировано <b>{$total_files}</b> {$tf_text}, из них:
						<br>- <b>{$i_change}</b> {$i_change_text}
						<br>- <b>{$i_add}</b> {$i_add_text}
						<br>- <b>{$i_del}</b> {$i_del_text}
					</p>
					<p>Запущено с IP: <b>{$_SERVER['REMOTE_ADDR']}</b></p>
				</div>";
			} else {
				$makeFile['status'] = '2';
				$makeFile['text'] = "<h1 style=\"font:normal 22px 'Trebuchet MS',Arial,sans-serif;color:#16a085;padding:40px 10px 10px;text-align: center;\">Файлы не менялись. Всё ок!</h1>"; 
			}
			if ($this->config['makesnap'] || $this->config['allowsnap']) {
				@unlink($this->config['root_dir'].$this->config['scanfile']);
			}

		} else {
			if ($this->config['makesnap'] || $this->config['allowsnap']) {
				@rename($this->config['root_dir'].$this->config['scanfile'].'.tmp', $this->config['root_dir'].$this->config['scanfile']);
			}
			
			if (file_exists($this->config['root_dir'].$this->config['scanfile'])) {
				$makeFile['status'] = '3';
				$makeFile['text'] = "<h1 style=\"font:normal 22px 'Trebuchet MS',Arial,sans-serif;color:#16a085;padding:40px 10px 10px;text-align: center;\">{$this->config['sitename']} - Файл снимка успешно создан ".date("Y-m-d в H:i:s")."</h1> <p style='color: #34495e; line-height: 22px !important; margin-left: 40px; '>В снимке содержится: <b>{$total_files}</b> {$tf_text}</p>";
			} else {
				$makeFile['status'] = '4';
				$makeFile['text'] = "<h1 style=\"font:normal 22px 'Trebuchet MS',Arial,sans-serif;color:#c0392b;padding:40px 10px 10px;text-align: center;\">{$this->config['sitename']} - Файл снимка не создан!</h1>
					<div style='color: #34495e; line-height: 22px !important; margin-left: 40px;'>
						Возможные причины: 
						<br />- <b>Не хватает прав.</b> Установите на папку, содержащую снимок права на запись (CHMOD 777). 
						<br />- <b>Неверный путь к корню сайта.</b> Откройте файл скрипта и отредактируйте настройки в ручную, либо запустите устаовку ещё раз. 
						<br />- <b>Особенности хостинга или распределения прав пользователей.</b> Обратитесь за помошью в службу технической поддержки хостинга или на сайт <a href='http://antishell.ru/' target='_blank'>antishell.ru</a> (будьте готовы дать FTP-доступ к папке со скриптом и папке со снимком)
					</div>";
			}
		}

		if ($this->config['makesnap'] || $this->config['allowsnap']) {
			@rename($this->config['root_dir'].$this->config['scanfile'].'.tmp', $this->config['root_dir'].$this->config['scanfile']);
		}
		if (!$this->config['makesnap'] && !$this->config['allowsnap']) {
			@unlink($this->config['root_dir'].$this->config['scanfile'].'.tmp');
		}

		return $makeFile;
	}

	/**
	 * Назначение стилей спискам.
	 * @param $class - название класса строки
	 * @param $time - время
	 * @param $file - адрес файла
	 * @return string
	 */
	public function listStyler($class = 'change', $time, $file) {
		$icon_url = $this->config['icon_url'];
		$color = '#7f8c8d';
		$li_title = '';

		switch ($class) {
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

		$def_style = "display:block;height:24px;line-height:24px;font-family:Arial,sans-serif;padding:2px 10px 2px 40px;margin:0 0 0 0 ;border-bottom:1px solid #bdc3c7;font-size:14px;color:#7f8c8d; background: url({$icon_url}) {$bgPosition}";

		$span_style = "display:block; float:left; padding-right:10px; margin-right:10px; border-right: 1px solid #bdc3c7; font-size: 12px;";

		$li = "<li style='{$def_style}' title='{$li_title}'><span style='{$span_style}'>{$time}</span> <span style='color:{$color}'>{$file}</span></li>";

		return $li;
	}

	/**
	 * Отправка email
	 * @param $content - контент сообщения
	 * @param $subject - имя отправителя (берётся из имени сайта)
	 * @param $from_email - email отправителя
	 * @param $email - email получателя
	 * @param $title - тема сообщения
	 * @return отправленное мыло
	 */
	public function mailFromSite($content, $subject, $from_email, $email, $title = "На сайте изменены файлы") {
		$set_mail = (trim($from_email) !='') ? $from_email : $email;

		if (trim($subject) != '')
			$from = $this->mimeEncode($subject, $this->config['charset']) . " <" . $set_mail . ">";
		else
			$from = "<" . $set_mail . ">";

		$content  = str_replace("\r", "", $content);
		$headers = "From: " . $from . "\r\n";
		$headers .= "X-Mailer: ANTI-SHELL\r\n";
		$headers .= "Content-Type: text/html; charset=".$this->config['charset']."\r\n";
		$headers .= "Content-Transfer-Encoding: 8bit\r\n";
		$headers .= "X-Priority: 1 (Highest)";

		$mail_send = mail($email, $this->mimeEncode($title, $this->config['charset']), $content, $headers);
		return $mail_send;
	}

	/**
	 * Преобразование кодировки в кодировку )))
	 * @param string $text 
	 * @param $charset 
	 * @return string
	 */
	public function mimeEncode($text, $charset = "utf-8") {
		return "=?" . $charset . "?B?" . base64_encode($text) . "?=";
	}

	/**
	 * Функция для установки правильного окончания слов
	 * @param int $n - число, для которого будет расчитано окончание
	 * @param string $words - варианты окончаний для (1 комментарий, 2 комментария, 100 комментариев) 
	 * @return string - слово с правильным окончанием
	 */
	public function wordSpan($n = 0, $words) {
		$words	= explode('|', $words);
		$n		= intval($n);
		return  $n%10==1&&$n%100!=11?$words[0].$words[1]:($n%10>=2&&$n%10<=4&&($n%100<10||$n%100>=20)?$words[0].$words[2]:$words[0].$words[3]);
	}

	/**
	 * Шаблон для вывода в браузер и отправку уведомления на email
	 * @param string $title - заголовок окна браузера
	 * @param string $content - выводимый контент
	 * @return html
	 */
	public function template($title = '', $content = '') {
		$template = <<<HTML
<!DOCTYPE html>
<html>
	<head>
		<meta charset={$this->config['charset']}" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title>{$title}</title>
	</head>
	<body style="background-color:#ecf0f1; max-width: 800px; margin: 0 auto;padding:0;">
		<div  style="background-color:#ecf0f1;font:normal 16px 'Trebuchet MS',Arial,sans-serif;color:#7f8c8d;margin:0;padding:5px 5px 35px 5px;">
			{$content}
		</div>  
	</body>
</html>
HTML;
	
		return $template;
	}

	/**
	 * Выводим информацию в браузер
	 * @param string $output - что выводим
	 * @param string $charset - кодировка, отдаваемая браузеру
	 * @return html
	 */
	public function showOutput($output, $charset) {
		$this->showHeader($charset); // Не знаю нужно ли это тут вообще
		echo $output;	
	}

	public function showHeader($charset = 'utf-8') {
		header('Content-type: text/html; charset='.$charset);
	}

	/**
	 * Показываем статистику
	 * @return stat
	 */
	public function showStat() {
		global $time_start;

		$time   = round(microtime(true)-$time_start,5) . ' Сек.';
		$memory = (!function_exists('memory_get_peak_usage')) ? 'неизвестно' : round(memory_get_peak_usage()/1024/1024, 2) . ' Mb';

		$stat = '<div style="color: #34495e; line-height: 22px !important; margin-left: 40px; margin-top: 10px; border-top: 1px solid #bdc3c7;">
			<p>Время выполнения: '.$time.'
			<br />Затраты памяти <small>(максимальное потребление)</small>: '.$memory.'</p>
		</div>';

		return $stat;
	}
}
// Запуск основного метода.
AntiShell::getInstance()->runAntiShell($config);
?>