<?php
require("../../includes/config.php");	
$forum_path = "../../".$forum_path;
require("../../includes/phpbb.php");
if(!$bb_is_admin) {
	die();
}

$id = (int)$_GET['id']; 

$go = mysqli_query($link,"SELECT pic_link FROM gallery WHERE id='$id'");
$row = mysqli_fetch_assoc($go);
@mysqli_free_result($go);
$image = $row['pic_link'];
unlink('../../uploads/images/'.$image.'');

$go = mysqli_query($link,"DELETE FROM gallery WHERE id='$id'") or die(mysql_error());

@mysqli_free_result($go);

echo json_encode(array('info' => "Снимката е успешно изтрита!", 'id' => $id));
?> 
