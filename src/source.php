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
 * Версия: [version_id] от [version_date]
 * =============================================================================
 */ 
namespace AntiShell;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
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
	'icon_url' => '[icon_url]',

	// Разрешить отправку анонимной статистики
	// Собирается статистика о следующих параметрах сканирования:
	// - кол-во отсканированных файлов
	// - кол-во изменёных файлов
	// - кол-во удалённых файлов
	// - время сканирования
	// - версия скрипта (начная с v1.1.3)
	'send_stat' => [send_stat],

	// Если разрешено - в статистике будет учитываться домен (домен не публикуется!)
	// В будущем, по запросу будет возмжно получение статистики по своему домену.
	'send_domain' => [send_domain],

	// Логин, для которого будет собираться статистика сканирования
	// Рекомендуется указывать, если сайтов несколько, тогда можно будет получить статистику сразу по всем своим сайтам.
	// Допускается использование email-адреса
	'statistics_login' => '[statistics_login]',
);
///////// Конец настроек скрипта /////////



/**
 * ВНИМАНИЕ!
 * Если не знаете что делаете - не трогайте код ниже!
 */

if(!$config['on']) die("Wat?");

$config['makesnap'] = (isset($_GET['snap'])) ? true : false ;

/**
 * Class core
 * @package AntiShell
 */
class core
{

    /**
     * Оф.сайт скрипта
     * @var string
     */
    public $url = "antishell.ru";

    /**
     * Версия скрипта
     * @var string
     */
    public $version = "1.2";

    /**
     * Массив с конфигурацией скрипта
     * @var array
     */
    public $config;
    /**
     * Массив для записи отправляемой статистики
     * @var array
     */
    public $sendStatData;

    /**
     * Засечка времени для статистики
     * @var string
     */
    public $timeStart;

    /**
     * Полное имя файла снимка
     * @var string
     */
    public $snapFile;

    /**
     * Учитываем чистые затраты памяти
     * @var int|string
     */
    public $memoryStart;

    /**
     * Конструктор класса
     */
    function __construct()
    {
        $this->timeStart = $this->timer();
        $this->memoryStart = $this->getMemory();
    }

    /**
     * Устанавливаем конфиг
     *
     * @param array $arConfig
     *
     * @return $this
     */
    public function setConfig($arConfig = array())
    {
        $this->config = $arConfig;
        $this->sendStatData = array();

        return $this;
    }

    /**
     * Преобразуем строку в массив
     *
     * @author Sander http://sandev.pro/
     *
     * @param        $array - входящая строка
     * @param string $delimiter - разделитель массива
     *
     * @return array|bool
     */

    public function str2array($array, $delimiter = ',')
    {
        if (!$array OR $array == '*') {
            return false;
        }
        $arOld = explode($delimiter, $array);
        $arNew = array();
        foreach ($arOld as $v) {
            $v = trim($v);
            if ($v) {
                $arNew[] = $v;
            }
        }

        return $arNew;
    }

    /**
     * Метод для реализации strpos с массивом
     *
     * @param string $haystack - Где искать
     * @param array $needle - Что искать (массив)
     * @param int $offset - Если этот параметр указан, то поиск будет начат с указанного количества символов с начала строки. {@see strpos()}
     *
     * @return bool
     */
    public function strposa($haystack, $needle, $offset = 0)
    {
        if (!is_array($needle)) {
            $needle = array($needle);
        }
        foreach ($needle as $query) {
            if (strpos($haystack, $query, $offset) !== false) {
                return true;
            } // stop on first true result
        }

        return false;
    }

    /**
     * Основной метод класса
     */
    public function run()
    {
        // Определяем файл снимка
        $this->snapFile = $this->config['root_dir'] . $this->config['scanfile'];

        // Преобразуем нужные строки конфига в массив для дальнейшей работы.
        $this->config['ext'] = $this->str2array($this->config['ext']);
        $this->config['skipfile'] = $this->str2array($this->config['skipfile'] . ',' . basename($this->snapFile));
        $this->config['skipdir'] = $this->str2array($this->config['skipdir']);

        // Запускаем канирование
        $scan = $this->doScan($this->config['root_dir'] . $this->config['path']);
        // Пишем в файл
        $makeFile = $this->makeFile($scan);

        // Определяем выводимый контент и заголовок письма
        $status = $makeFile['status'];
        $allowMail = false;
        $title = '';
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
        $content = $makeFile['text'] . $this->showStat();

        if ($this->config['send_stat']) {
            $this->sendStat($this->sendStatData);
        }
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
     * @param string $dir - Путь к сканируемой папке
     * @param string $subdir - подпапка
     *
     * @return array
     */
    public function doScan($dir, $subdir = '')
    {
        $arFilesInfo = array();
        $scandir = $dir . $subdir;

        $directory = new RecursiveDirectoryIterator($scandir, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);

        foreach ($iterator as $obFile) {

            $fileName = $obFile->getFilename();
            $fileSize = $obFile->getSize();
            $pathName = $obFile->getPathname();
            $fileExtension = $obFile->getExtension();

            if (!empty($this->config['skipdir']) && $this->strposa($pathName, $this->config['skipdir'])) {
                continue;
            }
            if (!empty($this->config['skipfile']) && (in_array($fileExtension, $this->config['skipfile']) || in_array($fileName, $this->config['skipfile']))) {
                continue;
            }

            $fileCTime = $obFile->getCTime();
            $hash = md5($pathName . $fileName . $fileSize . $fileCTime);
            //$arFilesInfo[$hash]['hash'] = $hash;
            $arFilesInfo[$hash]['path'] = $pathName;
            $arFilesInfo[$hash]['date'] = $fileCTime;

        }

        return $arFilesInfo;
    }

    /**
     * Метод, создающий файл снимка
     *
     * @param $arScan - массив с результатами из метода doScan
     *
     * @return array  - возвращает статус процесса и текст результата
     */
    public function makeFile($arScan)
    {
        $makeFileInfo = array();
        $edit = array();
        $changedCount = 0;
        $addedCount = 0;
        $deletedCount = 0;
        $totalFilesCount = count($arScan);
        $tf_text = $this->declination($totalFilesCount, 'фай|л|ла|лов');

        $tmpFile = $this->snapFile . '.tmp';
        file_put_contents($tmpFile, serialize($arScan), LOCK_EX);

        if (file_exists($this->snapFile)) {
            $strScanFile = file_get_contents($this->snapFile);
            $arScanFile = unserialize($strScanFile);
            $strScan = serialize($arScan);

            $diff = array_diff_key($arScan, $arScanFile);

            foreach ($diff as $hash => $arFile) {
                $fPath = $arFile['path'];
                $fDate = date("Y-m-d H:i:s", $arFile['date']);
                if (strpos($strScanFile, $fPath) !== false) {
                    $edit[$hash] = $this->listStyler('change', $fDate, $fPath);
                    $changedCount++;
                } else {
                    $edit[$hash] = $this->listStyler('add', $fDate, $fPath);
                    $addedCount++;
                }
            }

            $deletedDiff = array_diff_key($arScanFile, $arScan);

            foreach ($deletedDiff as $hash => $arFile) {
                $fPath = $arFile['path'];
                $fDate = date("Y-m-d H:i:s", $arFile['date']);
                if (strpos($strScan, $fPath) === false) {
                    $edit[$hash] = $this->listStyler('del', $fDate, $fPath);
                    $deletedCount++;
                }
            }


            unset($diff, $deletedDiff);
            if ($edit) {
                arsort($edit);
                $snapDate = date("j.m.Y в H:i:s", filemtime($tmpFile));
                $logs = implode("\n\t", $edit);
                $changeText = $this->declination($changedCount, 'фай|л изменён|ла изменено|лов изменено');
                $addText = $this->declination($addedCount, 'фай|л добавлен|ла добавлено|лов добавлено');
                $delText = $this->declination($deletedCount, 'фай|л удалён|ла удалены|лов удалено');
                $snapInfo = ($this->config['makesnap'] || $this->config['allowsnap']) ? "<p>Снимок создан <b>{$snapDate}</b></p>" : "<p>Дата сканирования: <b>{$snapDate}</b></p>";

                $makeFileInfo['status'] = '1';
                $makeFileInfo['text'] = "<h1 style=\"font:normal 22px 'Trebuchet MS',Arial,sans-serif;color:#2980b9;padding:40px 10px 10px;text-align: center;\">{$this->config['sitename']} - Сканирование завершено</h1>
				<ul style='list-style:none;margin:0 0 15px 0;padding:0;'>
					{$logs}
				</ul>
				<div style='color: #34495e; line-height: 22px !important; margin-left: 40px;'>
					{$snapInfo}
					<p>
						Всего отсканировано <b>{$totalFilesCount}</b> {$tf_text}, из них:
						<br>- <b>{$changedCount}</b> {$changeText}
						<br>- <b>{$addedCount}</b> {$addText}
						<br>- <b>{$deletedCount}</b> {$delText}
					</p>
					<p>Запущено с IP: <b>{$_SERVER['REMOTE_ADDR']}</b></p>
				</div>";
            } else {
                $makeFileInfo['status'] = '2';
                $makeFileInfo['text'] = "<h1 style=\"font:normal 22px 'Trebuchet MS',Arial,sans-serif;color:#16a085;padding:40px 10px 10px;text-align: center;\">Файлы не менялись. Всё ок!</h1>";
            }
            if ($this->config['makesnap'] || $this->config['allowsnap']) {
                @unlink($this->snapFile);
            }

        } else {
            if ($this->config['makesnap'] || $this->config['allowsnap']) {
                @rename($tmpFile, $this->snapFile);
            }

            if (file_exists($this->snapFile)) {
                $makeFileInfo['status'] = '3';
                $makeFileInfo['text'] = "<h1 style=\"font:normal 22px 'Trebuchet MS',Arial,sans-serif;color:#16a085;padding:40px 10px 10px;text-align: center;\">{$this->config['sitename']} - Файл снимка успешно создан " . date("Y-m-d в H:i:s") . "</h1> <p style='color: #34495e; line-height: 22px !important; margin-left: 40px; '>В снимке содержится: <b>{$totalFilesCount}</b> {$tf_text}</p>";
            } else {
                $makeFileInfo['status'] = '4';
                $makeFileInfo['text'] = "<h1 style=\"font:normal 22px 'Trebuchet MS',Arial,sans-serif;color:#c0392b;padding:40px 10px 10px;text-align: center;\">{$this->config['sitename']} - Файл снимка не создан!</h1>
					<div style='color: #34495e; line-height: 22px !important; margin-left: 40px;'>
						Возможные причины:
						<br />- <b>Не хватает прав.</b> Установите на папку, содержащую снимок права на запись (CHMOD 755 или 777).
						<br />- <b>Неверный путь к корню сайта.</b> Откройте файл скрипта и отредактируйте настройки в ручную, либо запустите устаовку ещё раз.
						<br />- <b>Особенности хостинга или распределения прав пользователей.</b> Обратитесь за помошью в службу технической поддержки хостинга или на сайт <a href='http://antishell.ru/' target='_blank'>antishell.ru</a> (будьте готовы дать FTP-доступ к папке со скриптом и папке со снимком)
					</div>";
            }
        }

        if ($this->config['makesnap'] || $this->config['allowsnap']) {
            @rename($tmpFile, $this->snapFile);
        }
        if (!$this->config['makesnap'] && !$this->config['allowsnap']) {
            @unlink($tmpFile);
        }

        if ($this->config['send_stat']) {
            /** @var int */
            $this->sendStatData['i_change'] = $changedCount;
            /** @var int */
            $this->sendStatData['i_add'] = $addedCount;
            /** @var int */
            $this->sendStatData['i_del'] = $deletedCount;
        }

        return $makeFileInfo;
    }


    /**
     * @param string $class - название CSS-класса
     * @param string $time - время
     * @param string $file - адрес файла
     *
     * @return string
     */
    public function listStyler($class = 'change', $time, $file)
    {
        $icon_url = $this->config['icon_url'];
        $liInfo = $this->liInfo('#7f8c8d');

        switch ($class) {
            case 'add':
                $liInfo = $this->liInfo('#16a085', 'Добавлен файл', 'no-repeat 10px 3px');
                break;

            case 'del':
                $liInfo = $this->liInfo('#7f8c8d', 'Удален файл', 'no-repeat 10px -53px');
                break;

            case 'change':
                $liInfo = $this->liInfo('#c0392b', 'Изменен файл', 'no-repeat 10px -25px');
                break;
        }

        $def_style = 'display:block;line-height:24px;font-family:Arial,sans-serif;padding:2px 10px;margin:0;border-bottom:1px solid #bdc3c7;font-size:14px;color:#7f8c8d;';

        $span_style = 'display:block;height:24px;float:left;padding-right:10px;padding-left:40px;margin-right:10px;border-right:1px solid #bdc3c7;font-size:12px;background: url(' . $icon_url . ') ' . $liInfo['bgPosition'] . ';';

        $li = '<li style="' . $def_style . '" title="' . $liInfo['liTooltip'] . '"><span style="' . $span_style . '">' . $time . '</span> <span style="color:' . $liInfo['color'] . ';overflow:hidden;display:block;word-wrap:break-word;">' . $file . '</span></li>';

        return $li;
    }

    /**
     * @param string $content - контент сообщения
     * @param string $subject - имя отправителя (берётся из имени сайта)
     * @param string $from_email - email отправителя
     * @param string $email - email получателя
     * @param string $title - тема сообщения
     *
     * @return bool
     */
    public function mailFromSite($content, $subject, $from_email, $email, $title = "На сайте изменены файлы")
    {
        $set_mail = (trim($from_email) != '') ? $from_email : $email;

        if (trim($subject) != '') {
            $from = $this->mimeEncode($subject, $this->config['charset']) . " <" . $set_mail . ">";
        } else {
            $from = "<" . $set_mail . ">";
        }

        $content = str_replace("\r", "", $content);
        $headers = "From: " . $from . "\r\n";
        $headers .= "X-Mailer: ANTI-SHELL\r\n";
        $headers .= "Content-Type: text/html; charset=" . $this->config['charset'] . "\r\n";
        $headers .= "Content-Transfer-Encoding: 8bit\r\n";
        $headers .= "X-Priority: 1 (Highest)";

        $mail_send = mail($email, $this->mimeEncode($title, $this->config['charset']), $content, $headers);

        return $mail_send;
    }

    /**
     * Преобразование кодировки в кодировку )))
     *
     * @param string $text
     * @param        $charset
     *
     * @return string
     */
    public function mimeEncode($text, $charset = "utf-8")
    {
        return "=?" . $charset . "?B?" . base64_encode($text) . "?=";
    }

    /**
     * Функция для установки правильного окончания слов
     *
     * @param int $n - число, для которого будет расчитано окончание
     * @param string $words - варианты окончаний для (1 комментарий, 2 комментария, 100 комментариев)
     *
     * @return string - слово с правильным окончанием
     */
    public function declination($n = 0, $words)
    {
        $words = explode('|', $words);
        $n = intval($n);

        return $n % 10 == 1 && $n % 100 != 11 ? $words[0] . $words[1] : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $words[0] . $words[2] : $words[0] . $words[3]);
    }

    /**
     * Шаблон для вывода в браузер и отправку уведомления на email
     *
     * @param string $title - заголовок окна браузера
     * @param string $content - выводимый контент
     *
     * @return string
     */
    public function template($title = '', $content = '')
    {
        $template = <<<HTML
<!DOCTYPE html>
<html>
	<head>
		<meta charset="{$this->config['charset']}" />
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
     * @param $output - что выводим
     * @param $charset - кодировка, отдаваемая браузеру
     */
    public function showOutput($output, $charset)
    {
        $this->showHeader($charset);
        echo $output;
    }

    /**
     * @param string $charset
     */
    public function showHeader($charset = 'utf-8')
    {
        header('Content-type: text/html; charset=' . $charset);
    }

    /**
     * Подсчитываем время выполнения скрипта
     *
     * @param bool|string $stop
     *
     * @return float|mixed
     */
    public function timer($stop = false)
    {

        return ($stop) ? (microtime(true) - $stop) : microtime(true);
    }

    /**
     * Подсчитываем затраты памяти
     *
     * @param bool|string $stop
     *
     * @return int|string
     */
    public function getMemory($stop = false)
    {
        if (function_exists('memory_get_usage')) {
            return ($stop) ? (memory_get_usage() - $stop) : memory_get_usage();
        }

        return 0;
    }

    /**
     * Показываем статистику
     * @return string
     */
    public function showStat()
    {
        $timerStart = $this->timeStart;
        $time = round($this->timer($timerStart), 5);
        $memory = (!function_exists('memory_get_peak_usage')) ? 'неизвестно' : round(memory_get_peak_usage() / 1024 / 1024, 2) . ' Mb';
        $realMemory = round($this->getMemory($this->memoryStart) / 1024 / 1024, 3) . ' Mb';
        $showSendStatInfo = '';
        if ($this->config['send_stat']) {
            $this->sendStatData['time'] = $time;
            $showSendStatInfo = '<p>Статистика отправлена и доступна на <a href="http://' . $this->url . '/statistics/" target="_blank">странице статистики</a>.</p>';
        }


        $stat = '<div style="color: #34495e; line-height: 22px !important; margin-left: 40px; margin-top: 10px; border-top: 1px solid #bdc3c7;">
			<p>Время выполнения: ' . $time . ' Сек.
			<br />Затраты памяти <small>(максимальное потребление)</small>: ' . $memory . '
			<br />Затраты памяти <small>(реальное потребление)</small>: ' . $realMemory . '</p>
			' . $showSendStatInfo . '
		</div>';

        return $stat;
    }

    /**
     * Отправка Анонимной статистики
     *
     * @param  array $array массив с данными
     */
    public function sendStat($array)
    {

        if ($this->config['send_stat']) {

            $domain = ($this->config['send_domain']) ? $_SERVER['HTTP_HOST'] : '';
            $user = ($this->config['statistics_login'] != '') ? $this->config['statistics_login'] : '';

            if (function_exists('curl_exec')) {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_TIMEOUT, 15);
                curl_setopt($curl, CURLOPT_URL, 'http://' . $this->url . '/stat.php');
                curl_setopt($curl, CURLOPT_POST, 1);
                $data = "?&ichange={$array['i_change']}&iadd={$array['i_add']}&idel={$array['i_del']}&time={$array['time']}&version={$this->version}&domain={$domain}&user={$user}";
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_exec($curl);
                curl_close($curl);
            } else {
                $fp = fsockopen($this->url, 80, $errno, $errstr, 15);
                if ($fp) {
                    $data = '?&ichange=' . urlencode($array['i_change']) . '&iadd=' . urlencode($array['i_add']) . '&idel=' . urlencode($array['i_del']) . '&time=' . urlencode($array['time']) . '&version=' . urlencode($this->version) . '&domain=' . urlencode($domain) . '&user=' . urlencode($user);
                    $headers = 'POST ' . '/stat.php' . " HTTP/1.1\r\n";
                    $headers .= 'Host: ' . $this->url . "\r\n";
                    $headers .= "Content-type: application/x-www-form-urlencoded\r\n";
                    $headers .= 'Content-Length: ' . strlen($data) . "\r\n\r\n";
                    fwrite($fp, $headers . $data);
                    fclose($fp);
                }
            }

        }
    }


    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return array
     */
    public function getSendStatData()
    {
        return $this->sendStatData;
    }

    /**
     * @param string $color
     * @param string $liTooltip
     * @param string $bgPosition
     *
     * @return array
     */
    private function liInfo($color = '', $liTooltip = '', $bgPosition = '')
    {
        return array('color' => $color, 'liTooltip' => $liTooltip, 'bgPosition' => $bgPosition);
    }

}

$antishell = new core();
$antishell->setConfig($config)->run();