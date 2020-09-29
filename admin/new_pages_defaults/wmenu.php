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

$pagename      = basename($_SERVER['PHP_SELF']);
$pagename      = explode('.php', $pagename);
$pagename      = $pagename[0];
$page_name_get = mysqli_query($link, "SELECT * FROM pages WHERE page_name='$pagename'");
$row2          = mysqli_fetch_assoc($page_name_get);


$page_title = array(
    'page_title' => $row2['page_title']
); // Заглавие на страницата


//custom page content
$mustache->addHelper('legacy', array(
    'php' => function()
    {
        global $pagename;
        ob_start();
        include 'custom_page_content/' . $pagename . '.php';
        return ob_get_clean();
    }
));

$tpl = $mustache->loadTemplate($pagename); //име на темплейт файла
@mysqli_free_result($page_name_get);
echo $tpl->render($page_title + $values + $values4 + $lang_sys + $banner88x31 + $banner468x60 + $get_menuz + $get_menuz2 + $poll_print + $poll_send_vote); //принтираме всичко в страницата ($values+$values)
