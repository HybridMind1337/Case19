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
    'page_title' => $lang_sys['lang_home']
); // Заглавие на страницата

// Новини
$values7[] = ""; // Глобална
if (!isset($_GET['id'])) {
    require("includes/pagination.php");
    $results    = mysqli_result(mysqli_query($link, "SELECT COUNT(`id`) FROM `news`"), 0); // общия брой резултати
    $pagination = pagination($results, array(
        'get_vars' => array(
            'cat' => (int) @$_GET['cat'], // $_GET променливите, които да се запазват при сменянето на страницата
            'view' => @$_GET['view']
        ),
        'per_page' => 10, // по колко резултата да се показват на страница
        'per_side' => 3, // по колко страници да се показват от всяка страна на страницирането
        'get_name' => 'page' // името на $_GET променливата, от която ще бъде вземана страницата
    ));
    
    $mysql_check = mysqli_query($link, "SELECT * FROM news");
    $mysql       = mysqli_query($link, "SELECT * FROM news order by id DESC LIMIT {$pagination['limit']['first']}, {$pagination['limit']['second']}");
    if (mysqli_num_rows($mysql_check) > 0) {
        
        while ($row = mysqli_fetch_assoc($mysql)) {
            $news_id       = $row['id'];
            $news_username = $row['author'];
            $news_date     = date('d.m.y в h:i', $row['date']);
            $news_title = truncate_chars($row['title'],30,'...');
            $news_comments = $row['comments'];
            $news_text     = $row['text'];
            if (is_html($news_text)) {
                $news_text = htmlspecialchars_decode($row['text']);
            } else {
                $news_text = truncate_chars(htmlspecialchars_decode(strip_word_html($row['text'])),400,'...'); 
            }
            $news_img    = $row['img'];
            $news_seourl = $row['seourl'];
            $news_info[] = array(
                'news_username' => $news_username,
                'news_title' => $news_title,
                'news_date' => $news_date,
                'news_id' => $news_id,
                'news_comments' => $news_comments,
                'news_text' => $news_text,
                'news_img' => $news_img,
                'news_seourl' => $news_seourl
            );
            
        }
        @mysqli_free_result($results);
        @mysqli_free_result($mysql);
        $values5['allnews'] = new ArrayIterator($news_info);
        
        $pagination_out = $pagination['output'];
        $values6        = array(
            'pagination_news' => $pagination_out
        );
    } else {
        $values5   = array(
            'no_have_news' => "<div class='box'><div class='boxhead'><span class='boxhead_titles'><i class='fa fa-comment'></i> " . $lang_sys['lang_no_news'] . "</span></div><br/><div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i> " . $lang_sys['lang_no_news'] . "</div></div>"
        );
        $values6[] = "";
    }
    @mysqli_free_result($mysql_check);
} else {
    $url       = $_SERVER['REQUEST_URI'];
    $pieces    = explode("news/", $url);
    $newsearch = addslashes(trim(htmlspecialchars(mysqli_real_escape_string($link, $pieces[1]))));
    $get = mysqli_query($link, "SELECT * FROM news WHERE seourl='$newsearch'") or die(mysqli_error($link));
    $row = mysqli_fetch_assoc($get);
    
    // 18.01.2016 (check for unauthorized urls)
    if (mysqli_num_rows($get) < 1) {
        header('Location:index.php');
        exit();
    }
    // end
    
    $news_id         = $row['id'];
    $news_username   = $row['author'];
    $news_date       = date('d.m.y в h:i', $row['date']);
    $news_title      = $row['title'];
    $news_comments   = $row['comments'];
    $news_text       = htmlspecialchars_decode($row['text']);
    $news_img        = $row['img'];
    $comments_enable = $row['comments_enabled'];
    
    $get_comm = mysqli_query($link, "SELECT * FROM comments WHERE newsid='$news_id' order by id ASC") or die(mysqli_error($link));
    if (mysqli_num_rows($get_comm) > 0) {
        while ($row = mysqli_fetch_assoc($get_comm)) {
            $comment_id         = $row['id'];
            $comment_author     = $row['author'];
            $comment_text       = $row['text'];
            $comment_text       = str_replace(":)", "<img src=\"http://i.imgur.com/kM0PdWU.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(sad)", "<img src=\"http://i.imgur.com/KVnuEHL.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace(":D", "<img src=\"http://i.imgur.com/t2RAAD9.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace(";)", "<img src=\"http://i.imgur.com/LEYnCi4.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(coffee)", "<img src=\"http://i.imgur.com/n34xOuy.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(welcome)", "<img src=\"http://i.imgur.com/tnadycC.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(palav)", "<img src=\"http://i.imgur.com/OjdJK0B.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(beer)", "<img src=\"http://i.imgur.com/pQtOgpA.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(novsum)", "<img src=\"http://i.imgur.com/6v8D8LH.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(kiss)", "<img src=\"http://i.imgur.com/eIBPRfY.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(cry)", "<img src=\"http://i.imgur.com/BaTMEhT.gif\"   border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(gathering)", "<img src=\"http://i.imgur.com/nscGiyH.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(razz)", "<img src=\"http://i.imgur.com/mUKxhiJ.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(cool)", "<img src=\"http://i.imgur.com/Avhzv6O.gif\"   border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(fuck)", "<img src=\"http://i.imgur.com/4ak5jY8.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(curssing)", "<img src=\"http://i.imgur.com/0YuswtC.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(oops)", "<img src=\"http://i.imgur.com/xtTjece.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(frown)", "<img src=\"http://i.imgur.com/QstPKpR.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(omg)", "<img src=\"http://i.imgur.com/xUtVwW3.gif\"  border='0' alt='' />", $comment_text);
            $comment_text       = str_replace("(bg)", "<img src=\"http://i.imgur.com/I11A4iw.png\"  border='0' alt='' />", $comment_text);
            $comment_date       = date('d.m.y в h:i', $row['date']);
            $comment_ava        = $row['avatar'];
            $comment_nick_color = $row['nick_colour'];
            $comment_userid     = $row['user_id'];
            
            $newsinfo[] = array(
                'comment_id' => $comment_id,
                'comment_author' => $comment_author,
                'comment_text' => $comment_text,
                'comment_date' => $comment_date,
                'comment_ava' => $comment_ava,
                'comment_nick_color' => $comment_nick_color,
                'comment_userid' => $comment_userid
            );
        }
        $values5['allcomments'] = new ArrayIterator($newsinfo);
    } else {
        $values5 = array(
            'no_comments_here' => '<div class="alert alert-warning"><i class="fa fa-volume-up"></i> ' . $lang_sys['lang_no_comments'] . '</div>'
        );
    }
    @mysqli_free_result($get_comm);
    
    $values6 = array(
        'news_id' => $news_id,
        'news_exists' => 1,
        'comments_enable' => $comments_enable,
        'news_author' => $news_username,
        'news_title' => $news_title,
        'news_date' => $news_date,
        'news_comments' => $news_comments,
        'news_text' => $news_text,
        'news_img' => $news_img
    );
    
    // submit comments
    if (isset($_POST['submit_comm'])) {
        $com_username   = mysqli_real_escape_string($link, $_POST['com_username']);
        $com_ava        = $_POST['com_user_ava'];
        $com_user_color = mysqli_real_escape_string($link, $_POST['com_user_color']);
        $com_text       = mysqli_real_escape_string($link, htmlspecialchars($_POST['com_text']));
        if (empty($com_text)) {
            $values7[] = "";
        } else {
            $com_text = preg_replace('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', 'URL disabled for chat!', $com_text);
            $com_date = time();
            $go = mysqli_query($link, "INSERT INTO comments (author,text,date,avatar,nick_colour,user_id,newsid) VALUES('$com_username','$com_text','$com_date','$com_ava','$com_user_color','$bb_user_id', '$news_id')") or die(mysqli_error($link));
            @mysqli_free_result($go);
            $go = mysqli_query($link, "UPDATE news SET comments=comments+1 WHERE id='$news_id'") or die(mysqli_error($link));
            @mysqli_free_result($go);
            
            $values7 = array(
                'submit_com_suc' => '' . $lang_sys['lang_success'] . '! <meta http-equiv="refresh" content="1">'
            );
        }
    }
    // end submit comments//
}
// end news

$tpl = $mustache->loadTemplate('index');
echo $tpl->render($page_title + $values + $values2 + $values4 + $lang_sys + $values5 + $values6 + $values7 + $banner88x31 + $banner468x60 + $get_menuz + $get_menuz2 + $poll_print + $poll_send_vote); 