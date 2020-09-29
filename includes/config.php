<?php
$link = mysqli_connect("localhost","testing","testing","testing") or die("Error " . mysqli_error($link)); // Свързваме се към базата данни
mysqli_set_charset($link, "utf8"); // За всеки случай

// В коя папка се намира phpBB
$forum_path = "forums/";

// Статистика на сървърите
$greyfish_update = 300; // През колко време да се ъпдейтват сървърите, подразбиране е 5 минути
