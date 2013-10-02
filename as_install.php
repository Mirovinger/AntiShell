<?php
/**
 ******************* ВНИМАНИЕ! ******************
 * Если Ваш сайт работает в кодровке windows-1251
 * необходимо раскомментировать строку ниже (убрать два слеша в начале строки) */


// define('AS_CHARSET', 'windows-1251');



/* Дальше трогать ничего не нужно, если точно не знаете, что делаете! */


// Тут мы проверяем задана ли константа кодировки, если нет - значит будет utf-8
if (!defined('AS_CHARSET'))	define('AS_CHARSET', 'utf-8');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="<?=AS_CHARSET?>">
	<title>Установка AntiShell</title>
	<meta name="viewport" content="width=device-width">
	<link href="http://fonts.googleapis.com/css?family=Ubuntu+Condensed&subset=latin,cyrillic" rel="stylesheet">
	<style>
		/*Общие стили*/
		html{background: #bdc3c7 url('data:image/png;base64,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') repeat;}
		body{width: 960px;padding: 20px;margin: 20px auto;font:normal 14px/18px Arial, Helvetica, sans-serif;background: #f1f1f1;box-shadow: 0 0 15px 0 rgba(0, 0, 0, 0.1);color: #34495e;}
		::-moz-selection {background: #34495e;color: #f1f1f1;text-shadow: 0 1px 1px rgba(0, 0, 0, 0.9);}
		::selection {background: #34495e;color: #f1f1f1;text-shadow: 0 1px 1px rgba(0, 0, 0, 0.9);}
		hr{margin: 18px 0;border: 0;border-top: 1px solid #f5f5f5;border-bottom: 1px solid #bdc3c7;}
		.preview  {display: block;margin: 20px auto 40px;max-width: 100%;}
		.descr  {font: normal 18px/24px "Trebuchet MS", Arial, Helvetica, sans-serif;color: #34495e;margin: 20px -20px;padding: 20px;background: #ecf0f1;-webkit-box-shadow: inset 0 10px 10px -10px rgba(0, 0, 0, 0.1), inset 0 -10px 10px -10px rgba(0, 0, 0, 0.1);box-shadow: inset 0 10px 10px -10px rgba(0, 0, 0, 0.1), inset 0 -10px 10px -10px rgba(0, 0, 0, 0.1);text-shadow: 0 1px 0 #fff;}
		b{color: #2980b9;}
		.descr hr  {margin: 18px -20px;}
		.ta-center  {text-align: center;}
		.logo{margin: 0 auto;display: block;}
		a{color: #2980b9;}
		a:hover{text-decoration: none;color: #c0392b;}
		.btn, a.btn{line-height: 32px;font-size: 100%;margin: 0;vertical-align: baseline;*vertical-align: middle;cursor: pointer;*overflow: visible;background: #3498db;color: #ecf0f1;text-shadow: 0 1px 0 rgba(0, 0, 0, 0.2);border: 0;border-radius: 3px;padding: 0 15px;display: inline-block; text-decoration: none; border-bottom: solid 3px #2980b9;}
		.btn:hover, a.btn:hover, .btn.active{background: #e74c3c; border-bottom-color: #c0392b}
		article,
		.gray{color: #95a5a6;}
		.green{color: #16a085;}
		.red{color: #c0392b;}
		.blue{color: #3498db;}
		h1, h2, h3, h4, h1 b, h2 b, h3 b, h4 b{font-family: 'Ubuntu Condensed', sans-serif;font-weight: normal;}
		h3{margin: 0;}
		h1{line-height: 20px;line-height: 28px;}
		.clr{clear: both;height: 0;overflow: hidden;}
		li{margin-bottom: 20px;color: #2980b9;}
		li li{margin-bottom: 4px;margin-top: 4px;}
		li.div, li li, li h3{color: #34495e;}
		textarea{width: 800px;margin-bottom: 10px;vertical-align: top;-webkit-transition: height 0.2s;-moz-transition: height 0.2s;transition: height 0.2s;outline: none;display: block;color:#f39c12;padding: 5px 10px;font: normal 14px/20px Consolas,'Courier New',monospace;background-color: #2c3e50;white-space: pre;white-space: pre-wrap;word-break: break-all;word-wrap: break-word;text-shadow: none;border: none; border-left: solid 3px #f39c12; }
		textarea:focus{background: #bdc3c7;border-color: #2980b9; color:#2c3e50;}
		input[type="text"] {padding: 4px 10px;width: 250px;vertical-align: middle;height: 24px;line-height: 24px;border: solid 1px #95a5a6;display: inline-block;border-radius: 3px;}
		input[type="text"]:focus {border-color: #3498db;color:#2c3e50;outline: none;-webkit-box-shadow: 0 0 0 3px rgba(41, 128, 185, .5);-moz-box-shadow: 0 0 0 3px rgba(41, 128, 185, .5);box-shadow: 0 0 0 3px rgba(41, 128, 185, .5);}
		form {margin-bottom: 10px;}
		.checkbox { display:none; }
		.checkbox + label { cursor: pointer; margin-top: 4px; display: inline-block; }
		.checkbox + label span { display:inline-block; width:18px; height:18px; margin:-1px 4px 0 0; vertical-align:middle; background: #fff; cursor:pointer; border-radius: 4px; border: solid 2px #3498db; }
		.checkbox:checked + label span { background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAICAYAAADN5B7xAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAIJJREFUeNpi+f//PwMhIL6wjQVITQDi10xEKBYEUtuAOBuIGVmgAnkgyZfxVY1oilWB1BYgVgPiRqB8A8iGfCBuAGGggnokxS5A6iSyYpA4I8gPQEkQB6YYxH4FxJOAmAVZMVwD1ERkTTCAohgE4J6GSjTiU4xiA5LbG5AMwAAAAQYAgOM4GiRnHpIAAAAASUVORK5CYII=') no-repeat 50% 50%; border-color: #16a085; }
		.form-field {margin-bottom: 18px; margin-left: 90px;}
		.lebel {float: left;width: 300px;padding-right: 10px;line-height: 32px; text-align: right;}
		.control {width: 322px;float: left;}
		.control input[type="text"] { width: 300px; margin-bottom: 2px; }
		.form-field-large .lebel {width: 100px;}
		.form-field-large .control {width: 622px;}
		.form-field-large .control input[type="text"] { width: 600px; margin-bottom: 2px; }
		.alert {background: #ebada7; color: #c0392b; text-shadow: none;}
		.clearfix:before, .clearfix:after {content: ""; display: table;}
		.clearfix:after {clear: both;}
		.clearfix {*zoom: 1;} 
	</style>
</head>
<body>
	<header>
		<h1 class="ta-center"><big class="red">AntiShell</big> <br><span class="blue">Мастер установки скрипта для предупреждения взлома Вашего сайта</span></h1>
		<hr>
	</header>
	<section>  
		<h2 class="gray ta-center">Написан специально для быстрой установки скрипта на сайт без лишних заморочек.</h2>
		<p class="ta-center">
			<a href="https://github.com/pafnuty/AntiShell" target="_blank" class="btn">Репозиторий на GitHub</a>
			<a href="http://antishell.ru/" target="_blank" class="btn">Сайт поддержки скрипта</a>
		</p>
		<?php
			$output = anti_shell_installer();
			echo $output;
		?>

	</section> 	
	<p><a href="http:antishell.ru/" target="_blank">Информация об авторах скрипта</a></p>
</body>
</html>

<?php 
	/**
	 * Basic cURL wrapper function for PHP
	 * @link http://snipplr.com/view/51161/basic-curl-wrapper-function-for-php/
	 * @param string $url URL to fetch
	 * @param array $curlopt Array of options for curl_setopt_array
	 * @return string
	 */
	function curl_get($url, $curlopt = array()){
		$ch = curl_init();
		$default_curlopt = array(
			CURLOPT_TIMEOUT => 2,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FOLLOWLOCATION => 1,
			CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:25.0) Gecko/20100101 Firefox/25.0"
		);
		$curlopt = array(CURLOPT_URL => $url) + $curlopt + $default_curlopt;
		curl_setopt_array($ch, $curlopt);
		$response = curl_exec($ch);
		if($response === false)
			trigger_error(curl_error($ch));
		curl_close($ch);
		return $response;
	}
	
	function anti_shell_installer() {
		$docroot = $_SERVER["DOCUMENT_ROOT"];
		$output = '';
		// Ветка на гитхабе
		$github = 'https://raw.github.com/pafnuty/AntiShell/dev/';

		$ver = curl_get($github.'version_id.json');
		if ($ver) {
			$version = json_decode($ver, true);
		} 

		// Если через $_POST передаётся параметр install, производим инсталляцию, согласно параметрам
		if(!empty($_POST['install'])) {

			// Определяем переменные

			$as = curl_get($github.'src/source.php');
			if (AS_CHARSET  == 'windows-1251') {
				$as = iconv("utf-8", "windows-1251//IGNORE", $as);
			}

			if (!$as) {
				die('Невозможно получить доступ к установочному файлу по адресу: '.$github.'src/source.php');
			}
			

			$as_config_array = array(
					'[root_dir]',
					'[version_id]',
					'[version_date]',
					'[sitename]',
					'[path]'	,
					'[scanfile]',
					'[allowsnap]',
					'[ext]'		,
					'[skipfile]',
					'[skipdir]'	,
					'[email]',
					'[from_email]',
					'[icon_url]',
					'[doc_charset]'
				);
			$as_config_values = array(
				$_POST['root_dir'],
				$version['id'],
				$version['date'],
				$_POST['sitename'],
				($_POST['path']) ? $_POST['path'] : "",
				$_POST['scanfile'],
				($_POST['allowsnap']) ? true : 0,
				($_POST['ext']) ? $_POST['ext'] : "",
				($_POST['skipfile']) ? $_POST['skipfile'] : "",
				($_POST['skipdir']) ? $_POST['skipdir'] : "",
				$_POST['yourmail'],
				($_POST['from']) ? $_POST['from'] : "",
				$_POST['icon_url'],
				AS_CHARSET
				);
			$as = str_replace($as_config_array, $as_config_values, $as);

			$script_filename = $_POST['script_filename'];
			
			$as_file = fopen($docroot.$script_filename, "w");
			fwrite($as_file, $as);
			fclose($as_file);
			chmod($docroot.$script_filename, 0755);

			$bsn = basename($script_filename);
			$pth = str_ireplace($bsn, '', $script_filename);
			
			if (!file_exists($docroot.$script_filename)) {
				$output .= '<div class="descr alert">';
				$output .= '<h2 class="red">Скрипт не установлен!</h2>' ;
				$output .= '<p>Мастер установки не смог получить доступ к файлу скрипта. Это значит что либо нет прав на запись, либо не верно указан путь к корню сайта.</p>' ;
				$output .= '<p>Установите на папку <b class="blue">'.$pth.'</b> права на запись: CHMOD 777. <br />И не забудьте вернуть обратно после успешной установки скрипта.</p>';
				$output .= '<a class="btn" href="javascript:history.go(-1);" title="Вернуться назад">Вернуться назад</a>';
				$output .= '</div>';
			} else {

				$output .= <<<HTML
				<div class="descr">
					<h2 class="red">Установка AntiShell скрипта на Ваш сайт завершена!</h2>
					<div class="form-field form-field-large clearfix">
					<div class="lebel">&nbsp;</div>
						<div class="control">
							<a href="http://{$_SERVER['HTTP_HOST']}{$script_filename}?snap=y" target="_blank" title="Будет запущена проверка системы: http://{$_SERVER['HTTP_HOST']}{$script_filename}" class="btn">Запустить проверку</a> &nbsp; <small>Адрес скрипта: http://{$_SERVER['HTTP_HOST']}{$script_filename}</small>
						</div>
					</div>
					<hr>
					<h2>Команды для выполнения через cron <small>(Скопируйте одну и вставьте в планировщик)</small>:</h2>
					<div class="form-field form-field-large clearfix">
						<div class="lebel">Через wget</div>
						<div class="control">
							<input type="text" onclick="this.select();" value='/usr/bin/wget -O - -q "http://{$_SERVER['HTTP_HOST']}{$script_filename}"'>
						</div>
					</div>
					<div class="form-field form-field-large clearfix">
						<div class="lebel">Через php</div>
						<div class="control">
							<input type="text" onclick="this.select();" value="/usr/bin/php -f {$docroot}{$script_filename}"></p>
							<small class="red">Путь к php или wget у вас может отличаться!</small> <small class="gray">Проверьте корректность пути (можно уточнить в ТП хостинга).</small>
							<p>Рекомендую ставить в планировщик минимум раз в сутки (лучше раз в час). Если ресурсы хостинга позволяют - раз в час. Так вы отловите зловредный код или просто изменения в файлах очеь быстро.</p>
						</div>
					</div>
					<hr>
					<div class="form-field form-field-large clearfix">
						<div class="lebel">&nbsp;</div>
						<div class="control">
							<p>Отправить информацию об установке скрипта на email</p>
							<form method="post">
								<input type="hidden" value="1" name="sendmail" />
								<input type="hidden" value="{$_POST['yourmail']}" name="ym" />
								<input type="hidden" value="{$_POST['sitename']}" name="sn" />
								<input type="hidden" value="Адрес скрипта: http://{$_SERVER['HTTP_HOST']}{$script_filename}" name="scriptlink" />
								<input type="hidden" value='/usr/bin/wget -O - -q "http://{$_SERVER['HTTP_HOST']}{$script_filename}"' name="wgetlink">
								<input type="hidden" value="/usr/bin/php -f {$docroot}{$script_filename}" name="phplink">
								<input type="submit" class="btn" value="Отправить данные на почту" />
							</form> 
							<small>На адрес <b class="red">{$_POST['yourmail']}</b> будет отправлена следующая информация:</small>
							<br><small>- ссылка на файл со скриптом.</small>
							<br><small>- команды для вставки в планировщик задач.</small>
						</div>
					</div>

					<h2 class="red">ВНИМАНИЕ! Обязательно удалите файл установщика после окончания установки!</h2>
				</div>
HTML;
			}
		}
		// Если через $_POST передаётся параметр sendmail, отправляем соответствующее уведомление на почту.
		elseif (!empty($_POST['sendmail'])) {
			/**
			 * Отправка уведомления на email
			 * @param string $buffer - что отправляем
			 * @param $subject - Тема сообщения
			 * @return type 
			 */
			function mailfromsite($buffer, $subject, $email, $text = "Установлен AntiShell скрипт", $from_email = 'noreply@antishell.ru')
			{
				$from_mail = (trim($from_email) !='') ? $from_email : $email;

				if (trim($subject) != '')
					$from = mime_encode($subject) . " <" . $from_mail . ">";
				else
					$from = "<" . $from_mail . ">";

				$buffer  = str_replace("\r", "", $buffer);
				$headers = "From: " . $from . "\r\n";
				$headers .= "X-Mailer: ANTISHELL\r\n";
				$headers .= "Content-Type: text/html; charset=" . AS_CHARSET . "\r\n";
				$headers .= "Content-Transfer-Encoding: 8bit\r\n";
				$headers .= "X-Priority: 1 (Highest)";

				$send_mail = mail($email, mime_encode($text), $buffer, $headers);
				return $send_mail;
			}
			/**
			 * Преобразование кодировки в кодировку )))
			 * @param string $text 
			 * @param $charset 
			 * @return string
			 */
			function mime_encode($text, $charset = AS_CHARSET)
			{
				return "=?" . $charset . "?B?" . base64_encode($text) . "?=";
			}
			$mail_text = <<<HTML
<body style="background-color:#ecf0f1; margin: 0; padding:0;">
	<div style="max-width: 800px; margin: 0 auto;">
		<h1 style="font:normal 22px 'Trebuchet MS', Arial, sans-serif; color:#2980b9; padding:40px 10px 10px; text-align: center;">Поздравляем!</h1> 
		<div style="background-color:#ecf0f1; font:normal 16px 'Trebuchet MS', Arial, sans-serif; color:#7f8c8d; margin:0; padding: 0px 20px 20px 20px;">
			<h4>Вы успешно установили AntiShell скрипт себе на сайт!</h4>
			<p>{$_POST['scriptlink']}</p>
			<p><b>Команды для выполнения через cron:</b></p>
			<p>Команда wget: <span style="display:inline-block; background: #FFFFBF; color: #FF0000; padding: 0 4px;">{$_POST['wgetlink']}</span></p>
			<p>Команда php: <span style="display:inline-block; background: #FFFFBF; color: #FF0000; padding: 0 4px;">{$_POST['phplink']}</span></p>
			<p>Не забывайте, что пути к модулям wget и php могут отличаться от приведённых т.к. они лишь для примера.</p>
			----------------------------------
			<p>По вопросам работы скрипта обращайтесь а сайт <a href="http://antishell.ru/" target="_blank">antishell.ru</a></p>
		</div>
	</div>
</body>
HTML;

			$sendMail = mailfromsite($mail_text, $_POST['sn'], $_POST['ym']);
			echo "<div class='descr'>";
			if ($sendMail) {
				echo "<h2>Вам на почту <span class=\"blue\">".$_POST['ym']."</span> выслано письмо.</h2>";
			} else {
				echo "<h2 class='red'>Письмо не отправлено.</h2>";
			}
				echo "<h2 class='red'>А теперь удалите файл установщика!</h2>";
			echo "</div>";
			
		}
		// Если через $_POST ничего не передаётся, выводим форму для установки модуля
		else {
			
			$site_title = 'Мой сайт';
			$site_mail = 'noname@site.ru';
			if (file_exists($docroot.'/engine/data/config.php')) {
				include_once $docroot.'/engine/data/config.php';
				$site_title = ($config['short_title']) ? $config['short_title'] : $config['home_title'];
				$site_mail = $config['admin_mail'];
			}

			$output .= <<<HTML
			<p class="ta-center">Текущая версия скрипта: <b>{$version['id']}</b> от {$version['date']}</p>
			<div class="descr">
				<form method="POST">            
					<input type="hidden" name="install" value="1">
					
					<div class="form-field clearfix">
						<div class="lebel">Путь к корню сайта</div>
						<div class="control">
							<input type="text" name="root_dir" value="$docroot">
							<small class="gray">Путь определяется автоматически. Если знаете что делаете или точно знаете, что путь должен быть другой - измените его.</small>
						</div>
					</div>
					<div class="form-field clearfix">
						<div class="lebel">Путь к файлу скрипта от корня сайта</div>
						<div class="control">
							<input type="text" name="script_filename" value="/antishell.php">
							<small class="red">Обязательно изменить имя и путь файла!</small>
							<small class="gray"><br>По указанному адресу будет создан файл с кодом скрипта. Папка в которой будет создаваться скрипт, должна имет права на запись (CHMOD 777). Рекоендуется прятать скрипт поглубже, а ссылку не афишировать. <br>К примеру так: <code>/engine/classes/min/lib/old.php</code></small>
						</div>
					</div>
				
					<div class="form-field clearfix">
						<div class="lebel">Имя сайта</div>
						<div class="control">
							<input type="text" name="sitename" value="AntiShell: $site_title">
							<small class="gray">Будет отображаться как тема письма.</small>
						</div>
					</div>

					<div class="form-field clearfix">
						<div class="lebel">Начальный путь проверки</div>
						<div class="control">
							<input type="text" name="path" value="">
							<small class="gray">По умолчанию - корень сайта.</small>
						</div>
					</div>

					<div class="form-field clearfix">
						<div class="lebel">Название файла со снимком системы</div>
						<div class="control">
							<input type="text" name="scanfile" value="/uploads/posts/snap.jpg">
							<small class="red">Обязательно изменить путь, имя и расширение файла!</small> <br>
							<small class="gray">Папка, в которой будет создаваться снимок, должна иметь CHMOD 777</small>
						</div>						
					</div>

					<div class="form-field clearfix">
						<div class="lebel">Автоматическое создание снимка</div>
						<div class="control">
							<input type="checkbox" value="1" name="allowsnap" id="allowsnap" checked class="checkbox"><label for="allowsnap"><span></span> Да</label> <br>
							<small class="gray">Если отключить автосоздание снимка, то результаты сканирования не буду записаны в файл. Для создания снимка в этом случаи необходимо запускать скрипт с параметром snap=y (например: http://antishell.php?snap=y)</small>
						</div>						
					</div>

					<div class="form-field clearfix">
						<div class="lebel">Расширения файлов, которые необходимо проверять</div>
						<div class="control">
							<input type="text" name="ext" value="">
							<small class="gray">По умолчанию все файлы. Расширения указывать без точек, разделитель - запятая.</small>
						</div>
					</div>

					<div class="form-field clearfix">
						<div class="lebel">Расширения файлов, которые проверяться не будут</div>
						<div class="control">
							<input type="text" name="skipfile" value="jpg,jpeg,gif,bmp,png,rar,zip,tmp,gz,xml,flv,exe,txt,doc,pdf,avi,mp3,mp4,wmv,m4v,m4a,mov,3gp,f4v,3gp,mpg,mpeg">
							<small class="gray">Указана оптимальная настройка. Расширения указывать без точек, разделитель - запятая.</small>
						</div>
					</div>

					<div class="form-field clearfix">
						<div class="lebel">Список папок, которые не надо проверять</div>
						<div class="control">
							<input type="text" name="skipdir" value="/engine/cache">
							<small class="gray">Путь указывается относительно корня сайта. Разделитель папок - запятая.</small>
						</div>
					</div>

					<div class="form-field clearfix">
						<div class="lebel">Email для уведомлений</div>
						<div class="control">
							<input type="text" name="yourmail" value="$site_mail">
							<small class="gray">Адрес почты, на который будут приходить уведомления. Можно указать несколько адресов через запятую.</small>
						</div>
					</div>

					<div class="form-field clearfix">
						<div class="lebel">Email отправителя</div>
						<div class="control">
							<input type="text" name="from" value="">
							<small class="gray">С этого адреса будет отправлено письмо. Если оставить пустым - письмо будет отправлено с адреса для уведомлений (из поля выше)</small>
						</div>
					</div>

					<div class="form-field clearfix">
						<div class="lebel">Путь к иконкам-индикаторам 
							<p><img src="https://raw.github.com/pafnuty/AntiShell/master/img/as_sprite.png" alt="Иконки по умолчанию"> &nbsp;</p>
						</div>
						<div class="control">
							<input type="text" name="icon_url" value="{$github}img/as_sprite.png">
							<small class="gray">Иконки-индикаторы отображаются для наглядности. Вы можете сохранить картинку (она слева) с иконками себе на сайт и прописать полный URL к этой картинке.</small>
						</div>
					</div>

					<div class="form-field clearfix">
						<div class="lebel">&nbsp;</div>
						<div class="control">
							<button class="btn" type="submit" style="width:322px;">Установить AntiShell!</button>
							<small>Будет произведенеа автоматическая установка скрипта на сайт в соответствии с заданными параметрами.</small>
						</div>
					</div>
				
				
				</form>
			</div>
HTML;


		}

		// Функция возвращает то, что должно быть выведено
		return $output;
	}

?>	