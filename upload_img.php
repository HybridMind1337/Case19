<?php
/*
Автор    HybridMind <www.webocean.info>
Проект    Case 19 (Случай 19)
Версия    1.0.0 Beta
Лиценз    GNU General Public v3.0
*/

require_once __DIR__ . "/includes/config.php"; // Конфигурационен файл
require_once __DIR__ . "/includes/lang_sys.php"; // Езикова система
require_once __DIR__ . "/includes/phpbb.php"; // Интеграция с phpBB (3.2.X/3.3.X)
require_once __DIR__ . '/includes/funcs.php'; // Важни функции на системата
require_once __DIR__ . "/vendor/autoload.php"; // Composer autoloader

Mustache_Autoloader::register();

$options = array(
    'extension' => '.html'
);

$mustache = new Mustache_Engine(array(
    'template_class_prefix' => '__case_', // Префикс на кеша
    'cache' => dirname(__FILE__) . '/cache', // Папка за кеша
    'loader' => new Mustache_Loader_FilesystemLoader('template', $options) // Папка за темплейт файловете
));

$page_title = array(
    'page_title' => $lang_sys['lang_upload_img']
); // Заглавие на страницата

// Check is this page
$is_uploadimg_page[] = "";
if (strpos($_SERVER["REQUEST_URI"], '/upload_img.php') !== false) {
    $is_uploadimg_page = array(
        'is_uploadimg_page' => 1
    );
}


$tpl = $mustache->loadTemplate('upload_img');
echo $tpl->render($page_title + $values + $values4 + $lang_sys + $banner88x31 + $banner468x60 + $is_uploadimg_page);