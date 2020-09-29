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

//aboutus
$get = mysqli_query($link, "SELECT * FROM aboutus");
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$aboutus     = htmlspecialchars_decode($row['aboutus']);
$aboutus_acp = array(
    'aboutus' => $aboutus
);

$aboutus_submit_acp[] = "";
if (isset($_POST['submit_aboutus'])) {
    $aboutus_post       = stripcslashes($_POST['aboutus']);
    $go                 = mysqli_query($link, "UPDATE aboutus SET aboutus='$aboutus_post'");
    $aboutus_submit_acp = array(
        'submit_aboutus' => 'Успешно!'
    );
}
//end aboutus

/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_edit_aboutus'); //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $aboutus_submit_acp + $aboutus_acp); //принтираме всичко в страницата ($values+$values)