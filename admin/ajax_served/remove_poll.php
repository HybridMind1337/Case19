<?php
require("../../includes/config.php");	
$forum_path = "../../".$forum_path;
require("../../includes/phpbb.php");
if(!$bb_is_admin) {
	die();
}

$id = (int)$_GET['id']; 

$go = mysqli_query($link,"DELETE FROM dpolls_votes WHERE poll_id='$id'");
$go = mysqli_query($link,"DELETE FROM dpolls WHERE id='$id'");
@mysqli_free_result($go);

echo json_encode(array('info' => "Анкетата е успешно изтрит!", 'id' => $id));
?> 
