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
    'page_title' => $lang_sys['lang_banners']
); // Заглавие на страницата


// Взимаме 468x60
$own_banners .= "<div class='alert alert-warning'>468x60</div>";
$get = mysqli_query($link, "SELECT * FROM `banners` WHERE type='468x60' ORDER by id") or die(mysqli_error($link));
while ($row = mysqli_fetch_assoc($get)) {
    
    $id            = $row['id'];
    $banner_link   = $row['site_link'];
    $banner_img    = $row['banner_img'];
    $banner_title  = $row['link_title'];
    $banner_author = $row['avtor'];
    
    $own_banners .= "
<div style='text-align:center'>
<img src='$banner_img' width='468' height='60' alt=''/><br/>
<textarea style='width:90%;' onclick='hl_text(this)' readonly='readonly'>&lt;a href='$banner_link' target='_blank' title='$banner_title'&gt;&lt;img src='$banner_img' alt='' style='border:0' /&gt;&lt;/a&gt;</textarea>
<br/>" . $lang_sys['lang_author'] . ": <b>$banner_author</b>
</div>
";
}
@mysqli_free_result($get);

// Взимаме 88x31
$own_banners .= "<div class='alert alert-warning'>88x31</div>";
$get = mysqli_query($link, "SELECT * FROM `banners` WHERE type='88x31' ORDER by id") or die(mysqli_error($link));
while ($row = mysqli_fetch_assoc($get)) {
    
    $id            = $row['id'];
    $banner_link   = $row['site_link'];
    $banner_img    = $row['banner_img'];
    $banner_title  = $row['link_title'];
    $banner_author = $row['avtor'];
    
    $own_banners .= "
<div style='text-align:center'>
<img src='$banner_img' width='88' height='31' alt=''/><br/>
<textarea style='width:90%;' onclick='hl_text(this)' readonly='readonly'>&lt;a href='$banner_link' target='_blank' title='$banner_title'&gt;&lt;img src='$banner_img' alt='' style='border:0' /&gt;&lt;/a&gt;</textarea>
<br/>" . $lang_sys['lang_author'] . ": <b>$banner_author</b>
</div>
";
}
@mysqli_free_result($get);

// Взимаме 200x200
$own_banners .= "<div class='alert alert-warning'>200x200</div>";
$get = mysqli_query($link, "SELECT * FROM `banners` WHERE type='200x200' ORDER by id") or die(mysqli_error($link));
while ($row = mysqli_fetch_assoc($get)) {
    
    $id            = $row['id'];
    $banner_link   = $row['site_link'];
    $banner_img    = $row['banner_img'];
    $banner_title  = $row['link_title'];
    $banner_author = $row['avtor'];
    
    $own_banners .= "
<div style='text-align:center'>
<img src='$banner_img' width='200' height='200' alt=''/><br/>
<textarea style='width:90%;' onclick='hl_text(this)' readonly='readonly'>&lt;a href='$banner_link' target='_blank' title='$banner_title'&gt;&lt;img src='$banner_img' alt='' style='border:0' /&gt;&lt;/a&gt;</textarea>
<br/>" . $lang_sys['lang_author'] . ": <b>$banner_author</b>
</div>
";
}
@mysqli_free_result($get);

// Взимаме userbars
$own_banners .= "<div class='alert alert-warning'>userbars</div>";
$get = mysqli_query($link, "SELECT * FROM `banners` WHERE type='userbar' ORDER by id") or die(mysqli_error($link));
while ($row = mysqli_fetch_assoc($get)) {
    
    $id            = $row['id'];
    $banner_link   = $row['site_link'];
    $banner_img    = $row['banner_img'];
    $banner_title  = $row['link_title'];
    $banner_author = $row['avtor'];
    
    $own_banners .= "
<div style='text-align:center'>
<img src='$banner_img' width='350' height='20' alt=''/><br/>
<textarea style='width:90%;' onclick='hl_text(this)' readonly='readonly'>&lt;a href='$banner_link' target='_blank' title='$banner_title'&gt;&lt;img src='$banner_img' alt='' style='border:0' /&gt;&lt;/a&gt;</textarea>
<br/>" . $lang_sys['lang_author'] . ": <b>$banner_author</b>
</div>
";
}
@mysqli_free_result($get);

// Взимаме 728x90
$own_banners .= "<div class='alert alert-warning'>728x90</div>";
$get = mysqli_query($link, "SELECT * FROM `banners` WHERE type='728x90' ORDER by id") or die(mysqli_error($link));
while ($row = mysqli_fetch_assoc($get)) {
    
    $id            = $row['id'];
    $banner_link   = $row['site_link'];
    $banner_img    = $row['banner_img'];
    $banner_title  = $row['link_title'];
    $banner_author = $row['avtor'];
    
    $own_banners .= "
<div style='text-align:center'>
<img src='$banner_img' width='728' height='90' alt=''/><br/>
<textarea style='width:90%;' onclick='hl_text(this)' readonly='readonly'>&lt;a href='$banner_link' target='_blank' title='$banner_title'&gt;&lt;img src='$banner_img' alt='' style='border:0' /&gt;&lt;/a&gt;</textarea>
<br/>" . $lang_sys['lang_author'] . ": <b>$banner_author</b>
</div>
";
}
@mysqli_free_result($get);

// Взимаме 120x240
$own_banners .= "<div class='alert alert-warning'>120x240</div>";
$get = mysqli_query($link, "SELECT * FROM `banners` WHERE type='120x240' ORDER by id") or die(mysqli_error($link));
while ($row = mysqli_fetch_assoc($get)) {
    
    $id            = $row['id'];
    $banner_link   = $row['site_link'];
    $banner_img    = $row['banner_img'];
    $banner_title  = $row['link_title'];
    $banner_author = $row['avtor'];
    
    $own_banners .= "
<div style='text-align:center'>
<img src='$banner_img' width='120' height='240' alt=''/><br/>
<textarea style='width:90%;' onclick='hl_text(this)' readonly='readonly'>&lt;a href='$banner_link' target='_blank' title='$banner_title'&gt;&lt;img src='$banner_img' alt='' style='border:0' /&gt;&lt;/a&gt;</textarea>
<br/>" . $lang_sys['lang_author'] . ": <b>$banner_author</b>
</div>
";
}
@mysqli_free_result($get);
$banners_own[] = "";
$get           = mysqli_query($link, "SELECT * FROM banners");
if (mysqli_num_rows($get) < 1) {
    $banners_own = array(
        'all_own_banners' => "<br/><div class='alert alert-info'><i class='fa fa-info-circle'></i> " . $lang_sys['lang_no_banners'] . "</div>"
    );
} else {
    $banners_own = array(
        'all_own_banners' => $own_banners
    );
}

$tpl = $mustache->loadTemplate('banners');
echo $tpl->render($page_title + $values + $values4 + $lang_sys + $banner88x31 + $banner468x60 + $banners_own);