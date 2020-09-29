<?php
/*
	Езикова библиотека
*/

if (isset($_COOKIE['case_en']) && $_COOKIE['case_en'] == 1) {
    require "lang/en/en.php";
} else if (isset($_COOKIE['case_bg']) && $_COOKIE['case_bg'] == 1) {
    require "lang/bg/bg.php";
} else {
    $get = mysqli_query($link, "SELECT default_language FROM config");
    $row = mysqli_fetch_assoc($get);
    @mysqli_free_result($get);
    $default_lang = $row['default_language'];
    require "lang/$default_lang/$default_lang.php";
}