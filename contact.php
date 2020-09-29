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
require_once __DIR__ . '/includes/captcha/challenge.php'; //captcha
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
    'page_title' => $lang_sys['lang_contacts']
); // Заглавие на страницата

$contact[] = ""; //globalize
if (isset($_POST['submit_contact'])) {
    $your_name     = mysqli_real_escape_string($link, $_POST['your-name']);
    $your_question = mysqli_real_escape_string($link, $_POST['your-question']);
    $your_email    = mysqli_real_escape_string($link, $_POST['your-email']);
    $your_text     = mysqli_real_escape_string($link, htmlspecialchars($_POST['your-text']));
    
    if (empty($your_name)) {
        $contact = array(
            'submit_contact' => $lang_sys['lang_no_name_found'],
            'submit_contact_alert' => "danger",
            'submit_contact_ico' => "exclamation-circle"
        );
    } else if (empty($your_question)) {
        $contact = array(
            'submit_contact' => $lang_sys['lang_no_question_found'],
            'submit_contact_alert' => "danger",
            'submit_contact_ico' => "exclamation-circle"
        );
        
    } else if (empty($your_text)) {
        $contact = array(
            'submit_contact' => $lang_sys['lang_no_text_found'],
            'submit_contact_alert' => "danger",
            'submit_contact_ico' => "exclamation-circle"
        );
    } else if (empty($_POST['verificationCode'])) {
        $contact = array(
            'submit_contact' => $lang_sys['lang_missing_capcha'],
            'submit_contact_alert' => "danger",
            'submit_contact_ico' => "exclamation-circle"
        );
    } else if (isChallengeAccepted($_POST[$CHALLENGE_FIELD_PARAM_NAME])) {
        
        $contact = array(
            'submit_contact' => $lang_sys['lang_success_contact'],
            'submit_contact_alert' => "success",
            'submit_contact_ico' => "check"
        );
        $time    = time();
        $go = mysqli_query($link, "INSERT INTO contacts (`date`, `ip`,`username`, `text`, `question`, `email`) VALUES('$time','$bb_user_ip','$your_name', '$your_text','$your_question','$your_email')") or die(mysqli_error($link));
        @mysqli_free_result($go);
    } else {
        $contact = array(
            'submit_contact' => $lang_sys['lang_wrong_captcha'],
            'submit_contact_alert' => "danger",
            'submit_contact_ico' => "exclamation-circle"
        );
    }
}

$tpl = $mustache->loadTemplate('contact');
echo $tpl->render($page_title + $values + $values4 + $lang_sys + $contact + $cap + $banner88x31 + $banner468x60 + $get_menuz + $get_menuz2 + $poll_print + $poll_send_vote);
