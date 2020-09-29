<?php 
function url() {
    // output: /myproject/index.php
    $currentPath = $_SERVER['PHP_SELF'];
    $pathInfo    = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $hostName    = $_SERVER['HTTP_HOST'];
    $protocol    = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https://' ? 'https://' : 'http://';
    
    // return: http://localhost/myproject/
    return $protocol . $hostName . $pathInfo . "";
}

?>
<!doctype html>
<html lang="en">

<head>
    <title>404 - Not found</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="<?php echo url(); ?>/../404/css/normalize.css" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="<?php echo url(); ?>/../404/css/bebasneue.css" />
    <link rel="stylesheet" href="<?php echo url(); ?>/../404/css/style.css">
    <link rel="stylesheet" href="<?php echo url(); ?>/../404/css/slicknav.css">
    <link rel="stylesheet" type="text/css" href="<?php echo url(); ?>/../404/css/responsive.css" />

    <script type="application/javascript" src="//cdn.jsdelivr.net/jquery/2.1.1/jquery.min.js"></script>
    <script src="<?php echo url(); ?>/../404/js/jquery.slicknav.min.js"></script>
    <script type="text/javascript" src="<?php echo url(); ?>/../404/js/script.js"></script>
    <!--[if lt IE 9]>
      <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>

<body>
    <div id="page-404-1" class="panda-wrapper panda-404">
        <div class="panda-content page-404">
            <header>
                <div class="logo-pd-404"><img src="<?php echo url(); ?>/../404/img/logo-pd-404.png" alt=""></div>
                <div class="dot"></div>
                <div class="logo-panda"><img src="<?php echo url(); ?>/../404/img/logo-pd.png" alt=""></div>
            </header>
            <h2 class="rs title-404">oh, it’s gone :(</h2>
            <p class="rs">Angry Panda has eaten the page you are looking for, please try one of these instead</p>
            <div class="panda-bg-arrow"></div>

            <div id="responsivemenu"></div>
            <nav class="panda-menu">
                <ul id="menu" class="rs">
                    <li class="current"><a href="../index.php">home</a></li>
                    <li><a href="../contact.php">contact</a></li>
                    <li><a href="../aboutus.php">aboutus</a></li>
                   
                </ul>
            </nav>
        </div>
    </div>

</body>

</html>