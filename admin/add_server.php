<?php
require_once "../includes/config.php"; // Конфигурационен файл
$forum_path = "../" . $forum_path;
require_once "../includes/phpbb.php"; // Интеграция с phpBB (3.2.X/3.3.X)
require_once '../includes/funcs.php'; // Важни функции на системата
require_once __DIR__ . '/includes/admin_functions.php'; // Важни функции за ACP-то

require_once "../vendor/autoload.php"; // Composer autoloader

Mustache_Autoloader::register(); //Регистрираме всичко от Autoloader-a
$options = array(
    'extension' => '.html'
);

$mustache = new Mustache_Engine(array( //декларираме обект
    'template_class_prefix' => '__caseacp_', //Префикс на кеша
    'cache' => '../cache', //папка за кеша
    'loader' => new Mustache_Loader_FilesystemLoader('template', $options) //папка за темплейт файловете
));

$submit_server[] = "";
include('../greyfish/inc/game_q.php');
if (isset($_POST['submit_server'])) {
    $type = $_POST['type'];
    $ip   = $_POST['server_ip'];
    $time = time();
    $port = $_POST['server_port'];
    if ($port != "PORT" || $ip != "IP") {
        if ($type == 'mc') {
            
            try {
                $Query = new MinecraftQuery();
                $Query->Connect($ip, $port);
                
                $mc_data  = $Query->GetInfo();
                $hostname = mb_convert_encoding($mc_data['HostName'], "utf-8", "windows-1251");
                
                $version       = $mc_data['Version'];
                $map           = $mc_data['Map'];
                $players       = $mc_data['Players'];
                $maxplayers    = $mc_data['MaxPlayers'];
                $go            = mysqli_query($link, "INSERT INTO greyfish_servers (ip,port,players,maxplayers,version,type,map,hostname,vote,status,last_update) VALUES('$ip','$port','$players','$maxplayers','$version','$type','$map','$hostname','0','1','$time')");
                $submit_server = array(
                    'server_add' => '<div class="alert alert-success">Успешно добавен сървър!</div>'
                );
                
                //Getting all players
                // print_r($Query->GetPlayers( ));
            }
            catch (MinecraftQueryException $e) {
                $submit_server = array(
                    'server_add' => '<div class="alert alert-danger">Този сървър е офлайн!</div>'
                );
            }
            
        } else if ($type == 'samp') {
            
            
            try {
                $rQuery = new QueryServer($ip, $port);
                
                $aInformation  = $rQuery->GetInfo();
                $aServerRules  = $rQuery->GetRules();
                $aTotalPlayers = $rQuery->GetDetailedPlayers();
                
                $rQuery->Close();
                $serverState = true;
            }
            catch (QueryServerException $pError) {
                $serverState = false;
            }
            if ($serverState == true) {
                
                
                $hostname   = mb_convert_encoding($aInformation['Hostname'], "utf-8", "windows-1251");
                $map        = $aInformation['Map'];
                $version    = $aInformation['Gamemode'];
                $players    = $aInformation['Players'];
                $maxplayers = $aInformation['MaxPlayers'];
                
                $go            = mysqli_query($link, "INSERT INTO greyfish_servers (ip,port,players,maxplayers,version,type,map,hostname,vote,status,last_update) VALUES('$ip','$port','$players','$maxplayers','$version','$type','$map','$hostname','0','1','$time')");
                $submit_server = array(
                    'server_add' => '<div class="alert alert-success">Успешно добавен сървър!</div>'
                );
                
                //GEt players
                /* if ($aInformation['Players'] > 0) {
                echo "<table border=\&quot;0\&quot;>";
                echo "<tr>";
                echo "<th>ID</th><th>Nickname</th><th>Score</th><th>Ping</th>";
                echo "</tr>";
                foreach( $aTotalPlayers as $aPlayer ) {
                echo "<tr>";
                echo "<td>" . $aPlayer['PlayerID'] . "</td>";
                echo "<td>" . $aPlayer['Nickname'] . "</td>";
                echo "<td>" . $aPlayer['Score'] . "</td>";
                echo "<td>" . $aPlayer['Ping'] . "</td>";
                echo "</tr>";
                }
                echo "</table>";
                }*/
            } else {
                $submit_server = array(
                    'server_add' => '<div class="alert alert-danger">Този сървър е офлайн!</div>'
                );
            }
            
        } else if ($type == 'cs') {
            
            $get = query_cs16($ip, $port); //ip и порт на сървъра
            
            $hostname = mb_convert_encoding($get['s_name'], "utf-8", "windows-1251");
            if ($hostname) {
                $map           = $get['s_map'];
                $players       = $get['s_players'];
                $maxplayers    = $get['s_maxplayers'];
                $version       = 'CS 1.6';
                $go            = mysqli_query($link, "INSERT INTO greyfish_servers (ip,port,players,maxplayers,version,type,map,hostname,vote,status,last_update) VALUES('$ip','$port','$players','$maxplayers','$version','$type','$map','$hostname','0','1','$time')");
                $submit_server = array(
                    'server_add' => '<div class="alert alert-success">Успешно добавен сървър!</div>'
                );
            } else {
                $submit_server = array(
                    'server_add' => '<div class="alert alert-danger">Този сървър е офлайн!</div>'
                );
            }
            
        } else if ($type == 'csgo') {
            
            $get      = query_csgo($ip, $port); //ip и порт на сървъра
            $hostname = mb_convert_encoding($get['s_name'], "utf-8", "windows-1251");
            if ($hostname) {
                $map           = $get['s_map'];
                $players       = $get['s_players'];
                $maxplayers    = $get['s_maxplayers'];
                $version       = 'CS:GO';
                $go            = mysqli_query($link, "INSERT INTO greyfish_servers (ip,port,players,maxplayers,version,type,map,hostname,vote,status,last_update) VALUES('$ip','$port','$players','$maxplayers','$version','$type','$map','$hostname','0','1','$time')");
                $submit_server = array(
                    'server_add' => '<div class="alert alert-success">Успешно добавен сървър!</div>'
                );
                
            } else {
                $submit_server = array(
                    'server_add' => '<div class="alert alert-danger">Този сървър е офлайн!</div>'
                );
            }
            
        } else if ($type == 'ts') {
            //beta with api
            $ip_data = @json_decode(file_get_contents("https://api.planetteamspeak.com/serverstatus/$ip:$port"));
            
            @$playersz = $ip_data->result->users;
            @$maxp = $ip_data->result->slots;
            @$namez = $ip_data->result->name;
            if ($playersz) {
                
                $hostname   = mb_convert_encoding($namez, "utf-8", "windows-1251");
                $players    = $playersz;
                $maxplayers = $maxp;
                $map        = "Teamspeak";
                $version    = '3';
                
                $go            = mysqli_query($link, "INSERT INTO greyfish_servers (ip,port,players,maxplayers,version,type,map,hostname,vote,status,last_update) VALUES('$ip','$port','$players','$maxplayers','$version','$type','$map','$hostname','0','1','$time')");
                $submit_server = array(
                    'server_add' => '<div class="alert alert-success">Успешно добавен сървър!</div>'
                );
                
            } else {
                $submit_server = array(
                    'server_add' => '<div class="alert alert-danger">Този сървър е офлайн!</div>'
                );
            }
            
        }
        
    } else {
        $submit_server = array(
            'server_add' => '<div class="alert alert-danger">Въведете IP и Port!</div>'
        );
    }
}


/////////PRINT/////// 
$tpl = $mustache->loadTemplate('admin_add_server'); //име на темплейт файла
echo $tpl->render($values_acp + $contact_pm_acp + $submit_server); //принтираме всичко в страницата ($values+$values)