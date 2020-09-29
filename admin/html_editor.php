<?php
require_once "../includes/config.php"; // Конфигурационен файл
$forum_path = "../" . $forum_path;
require_once "../includes/phpbb.php"; // Интеграция с phpBB (3.2.X/3.3.X)
require_once '../includes/funcs.php'; // Важни функции на системата
require_once __DIR__ . '/includes/admin_functions.php'; // Важни функции за ACP-то

require_once "../vendor/autoload.php"; // Composer autoloader

Mustache_Autoloader::register(); //Регистрираме всичко от Autoloader-a
$options = array(
    'extension' => '.html'
);

$mustache = new Mustache_Engine(array( //декларираме обект
    'template_class_prefix' => '__caseacp_', //Префикс на кеша
    'cache' => '../cache', //папка за кеша
    'loader' => new Mustache_Loader_FilesystemLoader('template', $options) //папка за темплейт файловете
));

//check is this page
$is_html_page_acp[] = "";
if (strpos($_SERVER["REQUEST_URI"], '/html_editor.php') !== false) {
    $is_html_page_acp = array(
        'is_html_page' => 1
    );
}
//end

//select files for htmlarea
$thelist[] = "";
if ($handle = opendir('../template')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && strtolower(substr($entry, strrpos($entry, '.') + 1)) == 'html') {
            $htmlfiles .= "<option data-html='$entry' value='../template/$entry'>$entry</option>\n";
            $thelist = array(
                'thelist' => $htmlfiles
            );
        }
    }
    closedir($handle);
}
//end select files for htmlarea


$tpl = $mustache->loadTemplate('admin_htmleditor'); //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $is_html_page_acp + $thelist); //принтираме всичко в страницата ($values+$values)