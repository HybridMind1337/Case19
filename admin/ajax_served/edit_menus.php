<?php
require("../../includes/config.php");	
$forum_path = "../../".$forum_path;
require("../../includes/phpbb.php");
if(!$bb_is_admin) {
	die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title></title>
    <meta charset="UTF-8">
    <!-- Bootstrap -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../template/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
 
    <link href="../template/css/skin-red.min.css" rel="stylesheet" type="text/css" />
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<link href="../js/darcy/darcy.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../js/darcy/darcy.js"></script>
    <style>
	select {color:black;}.btn{width:100%}
	</style>
	
</head>
<body style="background:#ecf0f5">

<div class="container">
<br/>
<?php 
$id = (int)$_GET['id'];	

$get = mysqli_query($link,"SELECT * FROM menus WHERE id='$id'");
$row = mysqli_fetch_assoc($get);
@mysqli_free_result($get);
$menu_title = $row['title'];
$menu_content = stripcslashes($row['the_content']);

echo '
<form role="form" action="" method="post">


 <div class="form-group">
      <label for="name">Име на менюто</label>
      <input type="text" name="menu_title" placeholder="Име на менюто" value="'.$menu_title.'" class="form-control" />
  </div>

 <div class="form-group">
      <label for="name">Съдържание на менюто</label>
      <textarea class="darcy" data-editor="php" type="text" name="menu_content" style="height:200px;width:100%">'.$menu_content.'</textarea>
  </div>



   <button type="submit" name="submit" class="btn btn-success">Редактирай</button>
</form>
<br/>
 
';
 
if(isset($_POST['submit'])) {
$menu_content =  $_POST['menu_content'];
$menu_title =  $_POST['menu_title'];

$go = mysqli_query($link,"UPDATE menus SET title='$menu_title', the_content='$menu_content' WHERE id='$id'");
echo '<br/><div class="alert alert-success"><i class="fa fa-check"></i> Успешно променено меню!</div>';
@mysqli_free_result($go);	
	
}

?>

</div>

</body>
</html>