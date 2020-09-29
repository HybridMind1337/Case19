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

//jquery_js
$get = mysqli_query($link, "SELECT * FROM jquery_js");
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$jquery_js     = htmlspecialchars_decode($row['jquery_js']);
$jquery_js_acp = array(
    'jquery_js' => $jquery_js
);

$jquery_js_submit_acp[] = "";
if (isset($_POST['submit_jquery_js'])) {
    $jquery_js_post       = mysqli_real_escape_string($link, stripcslashes($_POST['jquery_js']));
    $go                   = mysqli_query($link, "UPDATE jquery_js SET jquery_js='" . $jquery_js_post . "'");
    $jquery_js_submit_acp = array(
        'submit_jquery_js' => 'Успешно!'
    );
}
//end jquery_js


$tpl = $mustache->loadTemplate('admin_edit_jquery_js'); //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $jquery_js_submit_acp + $jquery_js_acp); //принтираме всичко в страницата ($values+$values)