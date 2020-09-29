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

//add menu
$menuz_acp[] = "";
if (isset($_POST['submit_menu'])) {
    $menu_name = mysqli_real_escape_string($link, $_POST['menu_name']);
    $menu_text = stripcslashes($_POST['menu_text']);
    $menu_pos  = mysqli_real_escape_string($link, $_POST['position_menu']);
    
    $go        = mysqli_query($link, "INSERT INTO menus (`title`,`the_content`,`position`) VALUES('$menu_name','$menu_text','$menu_pos')");
    $menuz_acp = array(
        'menu_add' => 'Успешно!'
    );
}
//end add menu

/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_add_menu'); //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $menuz_acp); //принтираме всичко в страницата ($values+$values)
