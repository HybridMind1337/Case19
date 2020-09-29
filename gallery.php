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
require_once __DIR__ . "/includes/pagination.php";
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
    'page_title' => $lang_sys['lang_gallery']
); // Заглавие на страницата


//check is this page
$is_gallery_page[] = "";
if (strpos($_SERVER["REQUEST_URI"], '/gallery.php') !== false) {
    $is_gallery_page = array(
        'is_gallery_page' => 1
    );
}
//end 


$results    = mysqli_result(mysqli_query($link, "SELECT COUNT(`id`) FROM `gallery`"), 0); // общия брой резултати
$pagination = pagination($results, array(
    'get_vars' => array(
        'cat' => (int) @$_GET['cat'], // $_GET променливите, които да се запазват при сменянето на страницата
        'view' => @$_GET['view']
    ),
    'per_page' => 10, // по колко резултата да се показват на страница
    'per_side' => 3, // по колко страници да се показват от всяка страна на страницирането
    'get_name' => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
));

$mysql_check = mysqli_query($link, "SELECT * FROM gallery") or die(mysqli_error($link));
$mysql = mysqli_query($link, "SELECT * FROM gallery order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}");
if (mysqli_num_rows($mysql_check) > 0) {
    while ($row = mysqli_fetch_assoc($mysql)) {
        $pic_date       = date('d.m.y :: h:i', $row['date']);
        $pic_uploader   = $row['uploader'];
        $pic_link       = $row['pic_link'];
        $gallery_info[] = array(
            'pic_date' => $pic_date,
            'pic_uploader' => $pic_uploader,
            'pic_link' => $pic_link
        );
        
    }
    @mysqli_free_result($results);
    @mysqli_free_result($mysql);
    $values5['allpictures'] = new ArrayIterator($gallery_info);
    
    $pagination_out = $pagination['output'];
    $values6        = array(
        'pagination_gallery' => $pagination_out
    );
} else {
    $values5   = array(
        'no_have_pics' => "<br/><div class='alert alert-info'><i class='fa fa-exclamation-triangle'></i> " . $lang_sys['lang_no_pics'] . "</div>"
    );
    $values6[] = "";
}
@mysqli_free_result($mysql_check);

$tpl = $mustache->loadTemplate('gallery'); 
echo $tpl->render($page_title + $values + $values4 + $lang_sys + $banner88x31 + $banner468x60 + $is_gallery_page + $values5 + $values6);
