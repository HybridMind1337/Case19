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

$results    = mysqli_result(mysqli_query($link, "SELECT COUNT(`id`) FROM `greyfish_servers`"), 0); // общия брой резултати
$pagination = pagination($results, array(
    'get_vars' => array(
        'cat' => (int) @$_GET['cat'], // $_GET променливите, които да се запазват при сменянето на страницата
        'view' => @$_GET['view']
    ),
    'per_page' => 15, // по колко резултата да се показват на страница
    'per_side' => 3, // по колко страници да се показват от всяка страна на страницирането
    'get_name' => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
));

$mysql_check = mysqli_query($link, "SELECT * FROM greyfish_servers");
$mysql       = mysqli_query($link, "SELECT * FROM greyfish_servers order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}");
if (mysqli_num_rows($mysql_check) > 0) {
    while ($row = mysqli_fetch_assoc($mysql)) {
        $server_id      = $row['id'];
        $server_name    = truncate_chars($row['hostname'], 1, 30, '...');
        $server_port    = $row['port'];
        $server_ip      = $row['ip'];
        $real_server_ip = $server_ip . ':' . $server_port;
        $server_map     = truncate_chars($row['map'], 1, 30, '...');
        $server_type    = $row['type'];
        $servers_info[] = array(
            'server_id' => $server_id,
            'server_name' => $server_name,
            'server_ip' => $real_server_ip,
            'server_map' => $server_map,
            'server_type' => $server_type
        );
        
    }
    @mysqli_free_result($results);
    @mysqli_free_result($mysql);
    $values3_acp['allservers'] = new ArrayIterator($servers_info);
    
    $pagination_out = $pagination['output'];
    $values4_acp    = array(
        'pagination_servers' => $pagination_out
    );
} else {
    $values3_acp   = array(
        'no_have_servers' => "<div class='alert alert-danger'>Няма сървъри!</div>"
    );
    $values4_acp[] = "";
}
@mysqli_free_result($mysql_check);
//end edit servers

//check is this page
$is_servers_page_acp[] = "";
if (strpos($_SERVER["REQUEST_URI"], '/edit_servers.php') !== false) {
    $is_servers_page_acp = array(
        'is_servers_page' => 1
    );
}
//end

/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_edit_servers'); //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $values3_acp + $values4_acp + $is_servers_page_acp); //принтираме всичко в страницата ($values+$values)