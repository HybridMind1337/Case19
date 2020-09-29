<?php
/*
    Важни функции на системата
    Ако не разбираш, недей пиша по тях!
*/ 

// Взимане на потребителския аватар от phpBB
function get_user_ava() {
    global $user;
    $arg['avatar']        = $user->data['user_avatar'];
    $arg['avatar_type']   = $user->data['user_avatar_type'];
    $arg['avatar_height'] = $user->data['user_avatar_height'];
    $arg['avatar_width']  = $user->data['user_avatar_width'];
    if (empty($arg['avatar'])) {
        
        $urlParts           = explode('/', str_ireplace(array(
            'http://',
            'https://'
        ), '', url()));
        $arg['avatar']      = '//' . $_SERVER['SERVER_NAME'] . '/' . $urlParts[1] . '/template/assets/img/no_avatar.png';
        $arg['avatar_type'] = 'avatar.driver.remote';
    }
    return phpbb_get_avatar($arg, $user->lang['USER_AVATAR'], false);
}
$user_avatar = get_user_ava();

// Взимане на линка на уеб сайта
function url() {
    // output: /myproject/index.php
    $currentPath = $_SERVER['PHP_SELF'];
    $pathInfo    = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $hostName    = $_SERVER['HTTP_HOST'];
    $protocol    = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https://' ? 'https://' : 'http://';
    
    // return: http://localhost/myproject/
    return $protocol . $hostName . $pathInfo . "";
}

function secondsToTime($seconds) {
    $dtF = new DateTime("@0");
    $dtT = new DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a дни');
}

// Създаване на UTF8 файл
function write_utf8_file($file, $content) {
    $file_handle = fopen($file, "wb") or die("can't open file");
    fwrite($file_handle, iconv('UTF-8', 'UTF-8', $content));
    fclose($file_handle);
}

// Общо регистрирани потребители 
function get_total_users() {
    global $link, $bb_prefix, $bb_db;
    $mysql = mysqli_query($link, "SELECT count(user_id) as total_users FROM `$bb_db`." . $bb_prefix . "_users WHERE group_id!=6 AND group_id!=1 AND user_type!=1") or die(mysqli_error($link));
    $fetchrow = mysqli_fetch_assoc($mysql);
    @mysqli_free_result($mysql);
    return $fetchrow['total_users'];
}
$total_users = get_total_users();


// Общо сесии
function get_total_sessions() {
    global $link, $bb_prefix, $bb_db;
    $mysql = mysqli_query($link, "select session_user_id,COUNT(session_user_id) as count_sess from `$bb_db`." . $bb_prefix . "_sessions s INNER JOIN `$bb_db`." . $bb_prefix . "_users u ON u.user_id = s.session_user_id WHERE session_user_id <> 1 AND (session_time + 300) > UNIX_TIMESTAMP(NOW())") or die(mysqli_error($link));
    $fetchrow = mysqli_fetch_assoc($mysql);
    @mysqli_free_result($mysql);
    return $fetchrow['count_sess'];
}
$online_sessions = get_total_sessions();


// Общо скрити
function get_total_anonymous() {
    global $link, $bb_prefix, $bb_db;
    $mysql = mysqli_query($link, "select session_user_id,COUNT(session_user_id) as count_sess_anony from `$bb_db`." . $bb_prefix . "_sessions s INNER JOIN `$bb_db`." . $bb_prefix . "_users u ON u.user_id = s.session_user_id WHERE session_user_id = 1 AND (session_time + 300) > UNIX_TIMESTAMP(NOW())") or die(mysqli_error($link));
    $fetchrow = mysqli_fetch_assoc($mysql);
    @mysqli_free_result($mysql);
    return $fetchrow['count_sess_anony'];
}
$online_sessions_anony = get_total_anonymous();


// Общо теми
function get_total_topics() {
    global $link, $bb_prefix, $bb_db;
    $mysql = mysqli_query($link, "SELECT count(topic_id) as total_topics FROM `$bb_db`." . $bb_prefix . "_topics WHERE topic_posts_approved >= '1'") or die(mysqli_error($link));
    $fetchrow = mysqli_fetch_assoc($mysql);
    @mysqli_free_result($mysql);
    return $fetchrow['total_topics'];
}
$total_topics = get_total_topics();

// Общо раздели
function get_total_forums() {
    global $link, $bb_prefix, $bb_db;
    $mysql = mysqli_query($link, "SELECT count(forum_id) as total_forums FROM `$bb_db`." . $bb_prefix . "_forums WHERE forum_type=1") or die(mysqli_error($link));
    $fetchrow = mysqli_fetch_assoc($mysql);
    @mysqli_free_result($mysql);
    return $fetchrow['total_forums'];
}
$total_forums = get_total_forums();


// Общо мнения
function get_total_posts() {
    global $link, $bb_prefix, $bb_db;
    $mysql = mysqli_query($link, "SELECT count(post_id) as total_posts FROM `$bb_db`." . $bb_prefix . "_posts WHERE post_visibility=1") or die(mysqli_error($link));
    $fetchrow = mysqli_fetch_assoc($mysql);
    @mysqli_free_result($mysql);
    return $fetchrow['total_posts'];
}
$total_posts = get_total_posts();

// Общо прегледи на теми
function get_total_topic_views()
{
    global $link, $bb_prefix, $bb_db;
    $mysql = mysqli_query($link, "SELECT SUM(topic_views) as total_topic_views  FROM `$bb_db`." . $bb_prefix . "_topics WHERE topic_posts_approved = '1'") or die(mysqli_error($link));
    $fetchrow = mysqli_fetch_assoc($mysql);
    @mysqli_free_result($mysql);
    return $fetchrow['total_topic_views'];
}
$total_topics_views = get_total_topic_views();

// Изтриване на старите реклами
$go = mysqli_query($link, "DELETE FROM advertise WHERE `dobaven_na`+`expire`<UNIX_TIMESTAMP()") or die(mysqli_error($link)); 
@mysqli_free_result($go);

// Уникални посещения
function unique_statistic() {
    global $link;
    $mysql = mysqli_query($link, "select * from stats where date IN (CURDATE()) AND ip='" . $_SERVER['REMOTE_ADDR'] . "'") or die(mysqli_error($link));
    $n = mysqli_num_rows($mysql);
    if ($n == 0) {
        $go = mysqli_query($link, "insert into stats (date,ip) values(CURDATE(),'" . $_SERVER['REMOTE_ADDR'] . "')") or die(mysqli_error($link));
        @mysqli_free_result($go);
    }
    @mysqli_free_result($mysql);
    $go = mysqli_query($link, "DELETE FROM `stats` where date(date) < DATE_SUB(CurDate(), INTERVAL 6 DAY);") or die(mysqli_error($link));
    @mysqli_free_result($go);
}
unique_statistic();

// Глобални променливи
function get_from_db_config($val) {
    global $link;
    $get = mysqli_query($link, "SELECT * FROM config") or die(mysqli_error($link));
    $row = mysqli_fetch_assoc($get);
    @mysqli_free_result($get);
    return $row['' . $val . ''];
}


function get_from_jquery_js($val) {
    global $link;
    $get = mysqli_query($link, "SELECT * FROM jquery_js") or die(mysqli_error($link));
    $row = mysqli_fetch_assoc($get);
    @mysqli_free_result($get);
    return $row['' . $val . ''];
}

// Взимане на всички къстам страници
function get_all_custom_pages() {
    global $link;
    $get = mysqli_query($link, "SELECT page_name,page_title FROM pages") or die(mysqli_error($link));
    if (mysqli_num_rows($get) > 0) {
        while ($row = mysqli_fetch_assoc($get)) {
            $c_page_name  = $row['page_name'];
            $c_page_title = $row['page_title'];
            $c_pages .= "<li><a href='" . url() . "/$c_page_name.php'>$c_page_title</a></li>";
        }
        return $c_pages;
        @mysqli_free_result($get);
    } else {
        return 0;
    }
}

$values = array(
    'username' => $bb_username,
    'is_logged' => $bb_is_anonymous ? false : true,
    'baseurl' => url(),
    'forum_path' => preg_replace("/[^A-Za-z0-9 ]/", '', $forum_path),
    'user_id' => $bb_user_id,
    'session_id' => $bb_session_id,
    'login_proceed' => append_sid("" . preg_replace("/[^A-Za-z0-9 ]/", '', $forum_path) . "/ucp.php", 'mode=login', true, $bb_session_id),
    'unread_pm' => $bb_unread_pm,
    'user_avatar' => $user_avatar,
    'user_ip' => $bb_user_ip,
    'user_posts' => $bb_user_posts,
    'user_last_visit' => $user->format_date($bb_user_last_visit),
    'user_logout' => preg_replace("/[^A-Za-z0-9 ]/", '', $forum_path) . '/ucp.php?mode=logout&sid=' . $bb_session_id . '',
    'user_color' => $bb_user_color,
    'is_admin' => $bb_is_admin ? true : false,
    'online_users' => $online_sessions,
    'online_users_anonymous' => $online_sessions_anony,
    'total_users' => $total_users,
    'total_topics' => $total_topics,
    'total_forums' => $total_forums,
    'total_posts' => $total_posts,
    'total_topics_views' => $total_topics_views,
    'poll_enable' => get_from_db_config('poll_enable'),
    'img_upload_enable' => get_from_db_config('img_upload_enable'),
    'socials_enable' => get_from_db_config('socials_enable'),
    'servers_enable' => get_from_db_config('servers_enable'),
    'gallery_enable' => get_from_db_config('gallery_enable'),
    'tw_link' => get_from_db_config('tw_link'),
    'fb_link' => get_from_db_config('fb_link'),
    'goo_link' => get_from_db_config('goo_link'),
    'favicon' => get_from_db_config('favicon'),
    'small_text_logo' => get_from_db_config('logo_text_small'),
    'big_text_logo' => get_from_db_config('logo_text_big'),
    'site_name' => get_from_db_config('site_name'),
    'last_news_name' => get_from_db_config('last_news_name'),
    'last_news_link' => get_from_db_config('last_news_link'),
    'head_box_text' => get_from_db_config('head_box_text'),
    'google_site_verify' => get_from_db_config('google_site_verify'),
    'google_analytics' => get_from_db_config('google_analytics'),
    'current_year' => date("Y"),
    'jquery_js' => get_from_jquery_js('jquery_js'),
    'all_custom_pages' => get_all_custom_pages()
);

// Всички групи
function group_users() {
    global $link, $bb_db, $bb_prefix;
    
    $mysql = mysqli_query($link, "select * FROM `$bb_db`." . $bb_prefix . "_groups WHERE `group_name` != 'REGISTERED_COPPA' AND `group_name` != 'GUESTS' AND `group_name` != 'NEWLY_REGISTERED'") or die(mysqli_error($link));
    while ($fetchrow = mysqli_fetch_assoc($mysql)) {
        $group_colors = $fetchrow['group_colour'];
        $group_names  = $fetchrow['group_name'];
        switch ($group_names) {
            case 'GLOBAL_MODERATORS': {
                $group_names = "Глобални модератори";
                break;
            }
            case 'REGISTERED': {
                $group_names = "Регистрирани";
                break;
            }
            case 'ADMINISTRATORS': {
                $group_names = "Администратори";
                break;
            }
            case 'BOTS': {
                $group_names = "Ботове";
                break;
            }
        }
        
        $group_users[] = array(
            'group_names' => $group_names,
            'group_colors' => $group_colors
        );
        
    }
    @mysqli_free_result($mysql);
    return new ArrayIterator($group_users);
}
$values4['group_users'] = group_users();

// Взимане на 88х31 рекламни банери
function banners88x31() {
    global $link;
    $banner88x31[] = "";
    $get           = mysqli_query($link, "SELECT * FROM advertise WHERE type='88x31'");
    if (mysqli_num_rows($get) > 0) {
        while ($row = mysqli_fetch_assoc($get)) {
            $banner_link    = $row['site_link'];
            $banner_img     = $row['banner_img'];
            $banner_title   = $row['link_title'];
            $banners_info[] = array(
                'banner_link_88x31' => $banner_link,
                'banner_img_88x31' => $banner_img,
                'banner_title_88x31' => $banner_title
            );
        }
        return new ArrayIterator($banners_info);
    }
    @mysqli_free_result($get);
}
$banner88x31['get_88x31'] = banners88x31();
if (empty($banner88x31['get_88x31'])) {
    $banner88x31 = array(
        'no_banners_88x31' => 1
    );
}

// Взимане на 468х60 рекламни банери
function banners468x60() {
    global $link;
    $banner468x60[] = "";
    $get = mysqli_query($link, "SELECT * FROM advertise WHERE type='468x60' ORDER BY RAND() LIMIT 1") or die(mysqli_error($link));
    if (mysqli_num_rows($get) > 0) {
        while ($row = mysqli_fetch_assoc($get)) {
            $banner_link     = $row['site_link'];
            $banner_img      = $row['banner_img'];
            $banner_title    = $row['link_title'];
            $banners_info2[] = array(
                'banner_link_468x60' => $banner_link,
                'banner_img_468x60' => $banner_img,
                'banner_title_468x60' => $banner_title
            );
        }
        return new ArrayIterator($banners_info2);
    }
    @mysqli_free_result($get);
}
$banner468x60['get_468x60'] = banners468x60();
if (empty($banner468x60['get_468x60'])) {
    $banner468x60 = array(
        'no_banners_468x60' => 1
    );
}

// Последни теми от phpBB
function last_topics() {
    global $link, $bb_db, $bb_prefix, $user, $forum_path;
    
    $mysql = mysqli_query($link, "SELECT * FROM `$bb_db`." . $bb_prefix . "_topics WHERE topic_posts_approved >= '1' ORDER BY topic_time DESC LIMIT 0,5") or die(mysqli_error($link)); //влизаме в табицата
    $values2[] = "";
    if (mysqli_num_rows($mysql) > 0) {
        while ($row = mysqli_fetch_assoc($mysql)) {
            $usernames       = $row['topic_first_poster_name'];
            $topic_titles    = truncate_chars($row['topic_title'], 1, '35', '...');
            $topic_id        = $row['topic_id'];
            $forum_id        = $row['forum_id'];
            $topic_views     = $row['topic_views'];
            $topic_times     = $user->format_date($row['topic_time']);
            $user_post_color = $row['topic_first_poster_colour'];
            $last_topics[]   = array(
                'usernames' => $usernames,
                'topic_time' => $topic_times,
                'topic_titles' => $topic_titles,
                'topic_link' => '' . url() . '/' . preg_replace("/[^A-Za-z0-9 ]/", '', $forum_path) . '/viewtopic.php?f=' . $forum_id . '&t=' . $topic_id . '',
                'topic_views' => $topic_views,
                'topic_user_color' => $user_post_color
            );
        }
        @mysqli_free_result($mysql);
        return new ArrayIterator($last_topics);
    }
}
$values2['last_topics'] = last_topics();

// Взимане на дясни менюта
function get_right_menus() {
    global $link;
    $get_menuz[] = "";
    $get = mysqli_query($link, "SELECT * FROM menus WHERE position='right' order by id ASC") or die(mysqli_error($link));
    if (mysqli_num_rows($get) > 0) {
        while ($row = mysqli_fetch_assoc($get)) {
            $menu_title   = $row['title'];
            $menu_content = htmlspecialchars_decode($row['the_content']);
            
            $menus_info[] = array(
                'menu_title' => $menu_title,
                'menu_content' => $menu_content
            );
            
        }
        @mysqli_free_result($get);
        return new ArrayIterator($menus_info);
    }
}
$get_menuz['get_menus'] = get_right_menus();
if (empty($get_menuz['get_menus'])) {
    $get_menuz = array(
        'no_menuz' => 1
    );
}

// Взимане на ляви менюта
function get_left_menus() {
    global $link;
    $get_menuz2[] = "";
    $get2 = mysqli_query($link, "SELECT * FROM menus WHERE position='left' order by id ASC") or die(mysqli_error($link));
    if (mysqli_num_rows($get2) > 0) {
        while ($row = mysqli_fetch_assoc($get2)) {
            $menu_title   = $row['title'];
            $menu_content = htmlspecialchars_decode($row['the_content']);
            
            $menus_info2[] = array(
                'menu_title2' => $menu_title,
                'menu_content2' => $menu_content
            );
            
        }
        @mysqli_free_result($get2);
        return new ArrayIterator($menus_info2);
    }
}
$get_menuz2['get_menus2'] = get_left_menus();
if (empty($get_menuz2['get_menus2'])) {
    $get_menuz2 = array(
        'no_menuz2' => 1
    );
}


// Анкета
$get_polls = mysqli_query($link, "SELECT * FROM dpolls ORDER by id DESC LIMIT 1") or die(mysqli_error($link));
if (mysqli_num_rows($get_polls) > 0) {
    $row_poll = mysqli_fetch_assoc($get_polls);
    @mysqli_free_result($$get_polls);
    $poll_id    = $row_poll['id'];
    $pollansw1  = $row_poll['poll_answer']; 
    $poll_votes = $row_poll['poll_votes'];
    
    $pieces   = explode(";", $pollansw1); 
    $pollansw = array(
        "votes" => $pieces
    ); 
    $quest    = $row_poll['poll_question']; 
    $user_ip  = $bb_user_ip; 
    
    $get_poll_votes = mysqli_query($link, "SELECT * FROM dpolls_votes WHERE ip='$user_ip' AND poll_id='$poll_id' ORDER by id DESC LIMIT 1") or die(mysqli_error($link));
    if (mysqli_num_rows($get_poll_votes) < 1) {
        $poll_print .= '<br />
        <div class="alert alert-info" role="alert"><i class="fas fa-poll"></i> ' . $quest . '</div>
<form  method="post">';
        $counter = 0;
        foreach ($pollansw['votes'] as $v) {
            $counter++;
            $pollansw_redit = explode("##", $v);
            $poll_print .= '<input type="radio" name="answ" class="css-checkbox" id="radio' . $counter . '" value="' . $v . '"/> ' . $pollansw_redit[0] . ' <label for="radio' . $counter . '" class="css-label radGroup1">&nbsp;</label><div class="clearfix"></div>';
        }
        $poll_print .= '
<input type="submit" class="btn btn-sm btn-primary" name="submit_vote" value="' . $lang_sys['lang_vote'] . '"/>
</form>
<div class="clear clearfix"></div>
';
        $poll_print = array(
            'poll_print' => $poll_print
        );
    } else {
        $poll_print = "<br />
        <div class='alert alert-info' role='alert'><i class='fas fa-poll'></i> $quest</div><br />";
        
        foreach ($pollansw['votes'] as $v) {
            $pollansw_redit = explode("##", $v);
            $poll_bar_width = floor(($pollansw_redit[1] / $poll_votes) * 100);
            $poll_print .= "" . $pollansw_redit[0] . " 
            <div class='progress'>
  <div class='progress-bar' role='progressbar' style='width: $poll_bar_width%' aria-valuenow='$poll_bar_width' aria-valuemin='0' aria-valuemax=''></div>
</div> ($pollansw_redit[1])<br />";
        }
        $poll_print .= "" . $lang_sys['lang_total_votes'] . " <b>$poll_votes</b>";
        
        $poll_print = array(
            'poll_print' => $poll_print
        );
    }
    @mysqli_free_result($get_poll_votes);
} else {
    $poll_print = array(
        'poll_print' => '<br /><div class="alert alert-danger" role="alert">' .$lang_sys['lang_no_poll']. '</div>'
    );
}

// Подаване на гласуването
$poll_send_vote[] = "";
if (isset($_POST['submit_vote'])) {
    if (!empty($_POST['answ'])) {

        $answ1z         = mysqli_real_escape_string($link, trim(htmlspecialchars($_POST['answ'])));
        $answer         = explode("##", $answ1z);
        $answer2        = $answer[1] + 1;
        $updated_answer = $answer[0] . '##' . $answer2;

        $get_poll_answer = mysqli_query($link, "SELECT poll_answer from dpolls order by id DESC LIMIT 1") or die(mysqli_error($link));
        $row_poll = mysqli_fetch_assoc($get_poll_answer);
        @mysqli_free_result($go);
        $poll_answer = $row_poll['poll_answer'];
        ;
        
        $go = mysqli_query($link, "UPDATE dpolls SET poll_answer = REPLACE('$poll_answer', '$answ1z', '$updated_answer') WHERE poll_answer LIKE '%$answ1z%' AND id='$poll_id'") or die(mysqli_error($link));
        $go = mysqli_query($link, "INSERT INTO dpolls_votes (poll_id,ip) VALUES('$poll_id','$user_ip')") or die(mysqli_error($link)); 
        $go = mysqli_query($link, "UPDATE dpolls SET poll_votes=poll_votes+1") or die(mysqli_error($link));
        @mysqli_free_result($go);
        header('Location: http://' . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI']);
        exit();
    } else {
        $poll_send_vote = array(
            'poll_choose' => $lang_sys['lang_choose_one_option']
        );
    }
} 

// Функция за съкращения
function truncate_chars($str, $limit = 15, $bekind = false, $maxkind = NULL, $end = NULL){
    if ( empty($str) || gettype($str) != 'string' ){
        return false;
    }
    $end = empty($end) || gettype($end) != 'string' ? '...' : $end;
    $limit = intval($limit) <= 0 ? 15 : intval($limit);
    if ( mb_strlen($str, 'UTF-8') > $limit ){
        if ( $bekind == true ){
            $maxkind = $maxkind == NULL || intval($maxkind) <= 0 ? 5 : intval($maxkind);
            $chars = preg_split('/(?<!^)(?!$)/u', $str);
            $cut = mb_substr($str, 0, $limit, 'UTF-8');
            $buffer = '';
            $total = $limit;
            for ( $i = $limit ; $i < count($chars) ; $i++ ){
                if ( !( $chars[$i] == "\n" || $chars[$i] == "\r" || $chars[$i] == " " || $chars[$i] == NULL || preg_match('/[\p{P}\p{N}]$/u', $chars[$i]) ) ){
                    if ( $maxkind > 0 ){
                        $maxkind--;
                        $buffer = $buffer . $chars[$i];
                    }else{
                        $buffer = !( $chars[$i] == "\n" || $chars[$i] == "\r" || $chars[$i] == " " || $chars[$i] == NULL || preg_match('/[\p{P}\p{N}]$/u', $chars[$i]) ) ? '' : $buffer;
                        $total = !( $chars[$i] == "\n" || $chars[$i] == "\r" || $chars[$i] == " " || $chars[$i] == NULL || preg_match('/[\p{P}\p{N}]$/u', $chars[$i]) ) ? 0 : ( $total + 1 );
                        break;
                    }
                    $total++;
                }else{
                    break;
                }
            }
            return $total == mb_strlen($str, 'UTF-8') ? $str : ( $cut . $buffer . $end );
        }
        return mb_substr($str, 0, $limit, 'UTF-8') . $end;
    }else{
        return $str;
    }
}

// MySQLi Result
function mysqli_result($result, $row, $field = 0) {
    if ($result === false)
        return false;
    if ($row >= mysqli_num_rows($result))
        return false;
    if (is_string($field) && !(strpos($field, ".") === false)) {
        $t_field  = explode(".", $field);
        $field    = -1;
        $t_fields = mysqli_fetch_fields($result);
        for ($id = 0; $id < mysqli_num_fields($result); $id++) {
            if ($t_fields[$id]->table == $t_field[0] && $t_fields[$id]->name == $t_field[1]) {
                $field = $id;
                break;
            }
        }
        if ($field == -1)
            return false;
    }
    mysqli_data_seek($result, $row);
    $line = mysqli_fetch_array($result);
    return isset($line[$field]) ? $line[$field] : false;
}

function unlink_recursive($dir_name, $ext) {

    if (!file_exists($dir_name)) {
        return false;
    }
    
    $dir_handle = dir($dir_name);

    while (false !== ($entry = $dir_handle->read())) {
        
        if ($entry == '.' || $entry == '..') {
            continue;
        }
        
        $abs_name = "$dir_name/$entry";
        
        if (is_file($abs_name) && preg_match("/^.+\.$ext$/", $entry)) {
            if (unlink($abs_name)) {
                continue;
            }
            return false;
        }

        if (is_dir($abs_name) || is_link($abs_name)) {
            unlink_recursive($abs_name, $ext);
        }
        
    }
    
    $dir_handle->close();
    return true;
    
}

// SEO Линкове
function parse_cyr_en_url($str, $replace = array(), $delimiter = '-') {
    
    $cyr = array(
        "Щ",
        "Ш",
        "Ч",
        "Ц",
        "Ю",
        "Я",
        "Ж",
        "А",
        "Б",
        "В",
        "Г",
        "Д",
        "Е",
        "Ё",
        "З",
        "И",
        "Й",
        "К",
        "Л",
        "М",
        "Н",
        "О",
        "П",
        "Р",
        "С",
        "Т",
        "У",
        "Ф",
        "Х",
        "Ь",
        "Ы",
        "Ъ",
        "Э",
        "Є",
        "Ї",
        "І",
        "щ",
        "ш",
        "ч",
        "ц",
        "ю",
        "я",
        "ж",
        "а",
        "б",
        "в",
        "г",
        "д",
        "е",
        "ё",
        "з",
        "и",
        "й",
        "к",
        "л",
        "м",
        "н",
        "о",
        "п",
        "р",
        "с",
        "т",
        "у",
        "ф",
        "х",
        "ь",
        "ы",
        "ъ",
        "э",
        "є",
        "ї",
        "і"
    );
    $lat = array(
        "sch",
        "sh",
        "ch",
        "c",
        "yu",
        "ya",
        "j",
        "a",
        "b",
        "v",
        "g",
        "d",
        "e",
        "e",
        "z",
        "i",
        "y",
        "k",
        "l",
        "m",
        "n",
        "o",
        "p",
        "r",
        "s",
        "t",
        "u",
        "f",
        "h",
        "",
        "y",
        "",
        "e",
        "e",
        "yi",
        "i",
        "sch",
        "sh",
        "ch",
        "c",
        "yu",
        "ya",
        "j",
        "a",
        "b",
        "v",
        "g",
        "d",
        "e",
        "e",
        "z",
        "i",
        "y",
        "k",
        "l",
        "m",
        "n",
        "o",
        "p",
        "r",
        "s",
        "t",
        "u",
        "f",
        "h",
        "",
        "y",
        "",
        "e",
        "e",
        "yi",
        "i"
    );
    
    for ($i = 0; $i < count($cyr); $i++) {
        $c_cyr = $cyr[$i];
        $c_lat = $lat[$i];
        $str   = str_replace($c_cyr, $c_lat, $str);
    }
    
    $str = preg_replace("/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]e/", "\${1}e", $str);
    $str = preg_replace("/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]/", "\${1}'", $str);
    $str = preg_replace("/([eyuioaEYUIOA]+)[Kk]h/", "\${1}h", $str);
    $str = preg_replace("/^kh/", "h", $str);
    $str = preg_replace("/^Kh/", "H", $str);
    
    $str2 = $str;
    
    if (!empty($replace)) {
        $str2 = str_replace((array) $replace, ' ', $str2);
    }
    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str2);
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = strtolower(trim($clean, '-'));
    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
    
    return $clean;
}

function strip_word_html($text, $allowed_tags = '<b><i><sup><sub><em><strong><u><br>') {
    mb_regex_encoding('UTF-8');
    //replace MS special characters first 
    $search  = array(
        '/&lsquo;/u',
        '/&rsquo;/u',
        '/&ldquo;/u',
        '/&rdquo;/u',
        '/&mdash;/u'
    );
    $replace = array(
        '\'',
        '\'',
        '"',
        '"',
        '-'
    );
    $text    = preg_replace($search, $replace, $text);
    //make sure _all_ html entities are converted to the plain ascii equivalents - it appears 
    //in some MS headers, some html entities are encoded and some aren't 
    $text    = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    //try to strip out any C style comments first, since these, embedded in html comments, seem to 
    //prevent strip_tags from removing html comments (MS Word introduced combination) 
    if (mb_stripos($text, '/*') !== FALSE) {
        $text = mb_eregi_replace('#/\*.*?\*/#s', '', $text, 'm');
    }
    //introduce a space into any arithmetic expressions that could be caught by strip_tags so that they won't be 
    //'<1' becomes '< 1'(note: somewhat application specific) 
    $text        = preg_replace(array(
        '/<([0-9]+)/'
    ), array(
        '< $1'
    ), $text);
    $text        = strip_tags($text, $allowed_tags);
    //eliminate extraneous whitespace from start and end of line, or anywhere there are two or more spaces, convert it to one 
    $text        = preg_replace(array(
        '/^\s\s+/',
        '/\s\s+$/',
        '/\s\s+/u'
    ), array(
        '',
        '',
        ' '
    ), $text);
    //strip out inline css and simplify style tags 
    $search      = array(
        '#<(strong|b)[^>]*>(.*?)</(strong|b)>#isu',
        '#<(em|i)[^>]*>(.*?)</(em|i)>#isu',
        '#<u[^>]*>(.*?)</u>#isu'
    );
    $replace     = array(
        '<b>$2</b>',
        '<i>$2</i>',
        '<u>$1</u>'
    );
    $text        = preg_replace($search, $replace, $text);
    //on some of the ?newer MS Word exports, where you get conditionals of the form 'if gte mso 9', etc., it appears 
    //that whatever is in one of the html comments prevents strip_tags from eradicating the html comment that contains 
    //some MS Style Definitions - this last bit gets rid of any leftover comments */ 
    $num_matches = preg_match_all("/\<!--/u", $text, $matches);
    if ($num_matches) {
        $text = preg_replace('/\<!--(.)*--\>/isu', '', $text);
    }
    return $text;
}

// Define is html string or not
function is_html($string) {
    return preg_match("/<[^<]+>/", $string, $m) != 0;
}

function file_get_contents_utf8($url) {
    $raw = file_get_contents($url);
    if ($raw === FALSE) {
        return false;
    } else {
        return mb_convert_encoding($raw, 'UTF-8', mb_detect_encoding($raw, 'UTF-8, ISO-8859-1', true));
    }
}