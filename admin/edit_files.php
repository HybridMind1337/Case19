<?php
require_once "../includes/config.php"; // Конфигурационен файл
$forum_path = "../" . $forum_path;
require_once "../includes/phpbb.php"; // Интеграция с phpBB (3.2.X/3.3.X)
require_once '../includes/funcs.php'; // Важни функции на системата
require_once '../includes/pagination.php';
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

$results    = mysqli_result(mysqli_query($link, "SELECT COUNT(`id`) FROM `files`"), 0); // общия брой резултати
$pagination = pagination($results, array(
    'get_vars' => array(
        'cat' => (int) @$_GET['cat'], // $_GET променливите, които да се запазват при сменянето на страницата
        'view' => @$_GET['view']
    ),
    'per_page' => 15, // по колко резултата да се показват на страница
    'per_side' => 3, // по колко страници да се показват от всяка страна на страницирането
    'get_name' => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
));

$mysql_check = mysqli_query($link, "SELECT * FROM files");
$mysql       = mysqli_query($link, "SELECT * FROM files order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}");
if (mysqli_num_rows($mysql_check) > 0) {
    while ($row = mysqli_fetch_assoc($mysql)) {
        $file_id      = $row['id'];
        $file_author  = $row['author'];
        $file_game    = $row['game'];
        $file_type    = $row['type'];
        $file_name    = $row['name'];
        $files_info[] = array(
            'file_name' => $file_name,
            'file_id' => $file_id,
            'file_author' => $file_author,
            'file_game' => $file_game,
            'file_type' => $file_type
        );
        
    }
    @mysqli_free_result($results);
    @mysqli_free_result($mysql);
    $values3_acp['allfiles'] = new ArrayIterator($files_info);
    
    $pagination_out = $pagination['output'];
    $values4_acp    = array(
        'pagination_files' => $pagination_out
    );
} else {
    $values3_acp   = array(
        'no_have_files' => "<div class='alert alert-danger'>Няма файлове!</div>"
    );
    $values4_acp[] = "";
}
@mysqli_free_result($mysql_check);
//end edit files

//check is this page
$is_files_page_acp[] = "";
if (strpos($_SERVER["REQUEST_URI"], '/edit_files.php') !== false) {
    $is_files_page_acp = array(
        'is_files_page_acp' => 1
    );
}
//end

/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_edit_files'); //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $values3_acp + $values4_acp + $is_files_page_acp); //принтираме всичко в страницата ($values+$values)