<?php
require("../../includes/config.php");	
$forum_path = "../../".$forum_path;
require("../../includes/phpbb.php");
if(!$bb_is_admin) {
	die();
}

$id = (int)$_GET['id']; 

$go = mysqli_query($link,"SELECT * FROM pages WHERE id='$id'");
$row = mysqli_fetch_assoc($go);
$pagename = $row['page_name'];

unlink("../../custom_page_content/$pagename.php");
unlink("../../template/$pagename.html");
unlink("../../$pagename.php");

$go = mysqli_query($link,"DELETE FROM pages WHERE id='$id'");
@mysqli_free_result($go);

echo json_encode(array('info' => "Страницата е успешно изтрита!", 'id' => $id));
?> 
