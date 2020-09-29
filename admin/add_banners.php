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

//banners
$banner_add_acp[] = "";
if (isset($_POST['submit_banner'])) {
    $type      = $_POST['type'];
    $aktivnost = $_POST['aktivnost'];
    switch ($aktivnost) {
        case '30': {
            $aktivnost = '2629743';
            break;
        }
        case '7': {
            $aktivnost = '604800';
            break;
        }
        case '0': {
            $aktivnost = '9999999999999999';
            break;
        }
    }
    $img_link   = mysqli_real_escape_string($link, $_POST['img_link']);
    $img_banner = mysqli_real_escape_string($link, $_POST['img_banner']);
    $title_b    = mysqli_real_escape_string($link, $_POST['link_title']);
    $dobaven_na = time();
    $go         = mysqli_query($link, "INSERT INTO advertise (type,site_link,banner_img,expire,link_title,dobaven_na) VALUES('$type','$img_link','$img_banner','$aktivnost','$title_b','$dobaven_na')");
    @mysqli_free_result($go);
    $banner_add_acp = array(
        'banner_add' => 'Успешно'
    );
    
}
//end

/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_add_banners'); //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $banner_add_acp); //принтираме всичко в страницата ($values+$values)