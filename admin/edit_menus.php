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


//edit menus
$mysql = mysqli_query($link, "SELECT * FROM menus order by id DESC");
if (mysqli_num_rows($mysql) > 0) {
    while ($row = mysqli_fetch_assoc($mysql)) {
        $menu_title   = $row['title'];
        $menu_id      = $row['id'];
        $menus_info[] = array(
            'menu_title' => $menu_title,
            'menu_id' => $menu_id
        );
        
    }
    @mysqli_free_result($mysql);
    $values3_acp['allmenus'] = new ArrayIterator($menus_info);
    
} else {
    $values3_acp = array(
        'no_have_menus' => "<div class='alert alert-danger'>Няма менюта!</div>"
    );
}
//end edit menus

//check is this page
$is_menus_page_acp[] = "";
if (strpos($_SERVER["REQUEST_URI"], '/edit_menus.php') !== false) {
    $is_menus_page_acp = array(
        'is_menus_page' => 1
    );
}
//end

/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_edit_menus'); //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $values3_acp + $is_menus_page_acp); //принтираме всичко в страницата ($values+$values)