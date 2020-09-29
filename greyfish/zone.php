<?php
if (empty($_SERVER['HTTP_REFERER'])){die();}
include("inc/game_q.php");
use xPaw\SourceQuery\SourceQuery;
$Query = new SourceQuery( );
require_once("inc/TeamSpeak3/TeamSpeak3.php");
?>
<link href="greyfish/style_zone.css" rel="stylesheet" />
<script src="greyfish/js/fancybox/jquery.fancybox.pack.js"></script>
<script src="greyfish/js/jcarousel.js"></script>


<div style="max-width:280px;max-height:170px;margin:15px auto">

<ul class="bxslider">
<?php
include("../includes/config.php");

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
/*край на съкращаване*/

$getzone = mysqli_query($link,"SELECT * FROM greyfish_servers ORDER by type DESC");
while($row = mysqli_fetch_assoc($getzone)) {
	$type = $row['type'];
	$map = $row['map'];
	$map_min =  truncate_chars($row['map'],1,10,'...');
	$servid = $row['id'];
	$players = $row['players'];
	$maxplayers = $row['maxplayers'];
	$hostname = $row['hostname'];
	$hostname_min = truncate_chars($row['hostname'],1,25,'...');
	$ip = $row['ip'];
	$port = $row['port'];
	@$progressbar=floor(($players / $maxplayers) * 100); //progress bar
	
	$steam = ""; //globalize
	if($type == "cs" || $type=="csgo") {
	$steam = "<a href='steam://connect/$ip:$port' title='steam'><img src='//".$_SERVER['HTTP_HOST']."/greyfish/icons/steam/steam.gif' alt='steam' style='display:inline-block'/></a>";
	} 
	$gametracker ="<a href='https://www.gametracker.com/server_info/$ip:$port/' target='_blank' title='gametracker'><img style='display:inline-block' src='//".$_SERVER['HTTP_HOST']."/greyfish/icons/gt/gt.gif' alt='gt'/></a>";
	
	
	$status = $row['status'];
	switch($status) {
		case 1: {
			$statuscolor = "green";
			break;
		}
		case 0: {
			$statuscolor = "red";
			break;
		}
	}
	
	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/greyfish/maps/'.$type.'/'.$map.'.jpg')) {
		 $mapimg = '//'.$_SERVER['HTTP_HOST'].'/greyfish/maps/'.$type.'/'.$map.'.jpg';
	} else {
		$mapimg = '//'.$_SERVER['HTTP_HOST'].'/greyfish/maps/map_no_response.jpg';
	}
	$last_update = $row['last_update'];
	 
	 switch($type) {
		case 'cs': {
			
			////////////////LIKE CRON////////////////////
			if($last_update < time()) {
			$nextupd = time() + $greyfish_update;
			
			try
			{
			$Query->Connect( ''.$ip.'',$port, 1, SourceQuery::GOLDSOURCE );
			$update_q_cs = $Query->GetInfo();
			$ServerErr = true;
			}
			catch( Exception $e )
			{
			$ServerErr = false;
			}
			finally
			{
			$Query->Disconnect( );
			}
	
			$host_cron = $update_q_cs['HostName'];
			if ($ServerErr == false) {
			//offline
			$query_q_cs = mysqli_query($link,"UPDATE greyfish_servers SET status='0', players='0',maxplayers='0',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_cs);
			} else {
			//online
			$map_cron = $update_q_cs['Map'];
			$p_cron = $update_q_cs['Players'];
			$maxp_cron = $update_q_cs['MaxPlayers'];
			$query_q_cs = mysqli_query($link,"UPDATE greyfish_servers SET status='1',hostname='$host_cron',map='$map_cron', players='$p_cron',maxplayers='$maxp_cron',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_cs);
			}
			}
			///////////////////END CRON///////////////////////

			$game = '<img style="display:inline-block" src="//'.$_SERVER['HTTP_HOST'].'/greyfish/icons/cs/cs.png" alt="CS 1.6"/>';
			break;
		}
		case 'csgo': {
			
			////////////////LIKE CRON////////////////////
			if($last_update < time()) {
			$nextupd = time() + $greyfish_update;
			
			try
			{
			$Query->Connect( ''.$ip.'',$port, 1, SourceQuery::SOURCE );
			$update_q_cs = $Query->GetInfo();
			$ServerErr = true;
			}
			catch( Exception $e )
			{
			$ServerErr = false;
			}
			finally
			{
			$Query->Disconnect( );
			}
	
			$host_cron = $update_q_cs['HostName'];
			if ($ServerErr == false) {
			//offline
			$query_q_cs = mysqli_query($link,"UPDATE greyfish_servers SET status='0', players='0',maxplayers='0',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_cs);
			} else {
			//online
			$map_cron = $update_q_cs['Map'];
			$p_cron = $update_q_cs['Players'];
			$maxp_cron = $update_q_cs['MaxPlayers'];
			$query_q_cs = mysqli_query($link,"UPDATE greyfish_servers SET status='1',hostname='$host_cron',map='$map_cron', players='$p_cron',maxplayers='$maxp_cron',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_cs);
			}
			}
			///////////////////END CRON///////////////////////
			
			$game = '<img style="display:inline-block" src="//'.$_SERVER['HTTP_HOST'].'/greyfish/icons/csgo/csgo.png" alt="CS:GO"/>';
			break;
		}
		case 'samp': {
			
			////////////////LIKE CRON////////////////////
			if($last_update < time()) {
			$nextupd = time() + $greyfish_update;
			
			try {
			$rQuery = new QueryServer( $ip, $port );
    
			$aInformation  = $rQuery->GetInfo( );
			$aServerRules  = $rQuery->GetRules( );
			$aTotalPlayers = $rQuery->GetDetailedPlayers( );
    
			$rQuery->Close( );
			$serverState = true;
			}
			catch (QueryServerException $pError) {
			$serverState = false;
			}
			if ($serverState == true) {
			$host_cron = mb_convert_encoding( $aInformation['Hostname'], "utf-8", "windows-1251");
			$map_cron = $aInformation['Map'];
            $p_cron = $aInformation['Players'];
			$maxp_cron = $aInformation['MaxPlayers'];
			$query_q_samp = mysqli_query($link,"UPDATE greyfish_servers SET status='1',hostname='$host_cron',map='$map_cron', players='$p_cron',maxplayers='$maxp_cron',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_samp);
			} else {
			$query_q_samp = mysqli_query($link,"UPDATE greyfish_servers SET status='0', players='0',maxplayers='0',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_samp);
			}
			
			}
			///////////////////END CRON///////////////////////
			
			$game = '<img style="display:inline-block" src="//'.$_SERVER['HTTP_HOST'].'/greyfish/icons/samp/samp.png" alt="San Andreas Multi-Player"/>';
			break;
		}
		case 'ts': {
			
			////////////////LIKE CRON////////////////////
			if($last_update < time()) {
			$nextupd = time() + $greyfish_update;
			
			
			try
			{
			// connect to server, authenticate and grab info
			$ts3 = TeamSpeak3::factory("serverquery://$query_ts_user:$query_ts_pass@$ip:10011/?server_port=$port");
  
 
			$host_cron= $ts3->virtualserver_name;
			$p_cron  = $ts3->virtualserver_clientsonline ;
			$maxp_cron =  $ts3->virtualserver_maxclients ;
			$query_q_ts = mysqli_query($link,"UPDATE greyfish_servers SET status='1',hostname='$host_cron', players='$p_cron',maxplayers='$maxp_cron',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_ts);
			}
			catch(Exception $e)
			{
			$query_q_ts3 = mysqli_query($link,"UPDATE greyfish_servers SET status='0', players='0',maxplayers='0',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_ts3);
			}
			
 
			}
			///////////////////END CRON///////////////////////
			
			$game = '<img style="display:inline-block" src="//'.$_SERVER['HTTP_HOST'].'/greyfish/icons/ts/ts.png" alt="TeamSpeak 3"/>';
			break;
		}
		case 'mc': {
			
			////////////////LIKE CRON////////////////////
			if($last_update < time()) {
			$nextupd = time() + $greyfish_update;
			
			try {
			$Query = new MinecraftQuery( );
			$Query->Connect( $ip,$port );
		   
			$mc_data =  $Query->GetInfo( );
			$host_cron = mb_convert_encoding($mc_data['HostName'], "utf-8", "windows-1251");
			$map = $mc_data['Map'];
		    $p_cron = $mc_data['Players'];
		    $maxp_cron = $mc_data['MaxPlayers'];
			$query_q_mc = mysqli_query($link,"UPDATE greyfish_servers SET status='1',hostname='$host_cron', players='$p_cron',maxplayers='$maxp_cron',last_update='$nextupd' WHERE id='$servid'");
			@mysqli_free_result($query_q_mc);
		     
			} catch( MinecraftQueryException $e ) {
			 $query_q_mc = mysqli_query($link,"UPDATE greyfish_servers SET status='0', players='0',maxplayers='0',last_update='$nextupd' WHERE id='$servid'");
			 @mysqli_free_result($query_q_mc);
			}
			
			}
			///////////////////END CRON///////////////////////
			
			$game = '<img style="display:inline-block" src="//'.$_SERVER['HTTP_HOST'].'/greyfish/icons/mc/mc.png" alt="Minecraft"/>';
			break;
		}
	}
	
$mapimg = "<img style='width:280px;height:160px;' src='$mapimg' alt='$map'/>";
echo "
<li class='mqy'>
<span class='zoverlay' style='display:none;position:absolute;'>
$game
<a href='$greyfish_tw' title='Сървърите в Twitter'><img style='display:inline-block' src='//".$_SERVER['HTTP_HOST']."/greyfish/icons/socials/tw.png' alt='Twitter'/></a>
<a href='$greyfish_go' title='Сървърите в Google+'><img style='display:inline-block' src='//".$_SERVER['HTTP_HOST']."/greyfish/icons/socials/goo.png' alt='Google+'/></a>
<a href='$greyfish_fb' title='Сървърите във Facebook'><img style='display:inline-block' src='//".$_SERVER['HTTP_HOST']."/greyfish/icons/socials/fb.png' alt='Facebook'/></a>
<i class='fancybox2 uncategorizei' data-fancybox-type='iframe' data-href='//".$_SERVER['HTTP_HOST']."/greyfish/showplayers.php?ip=$ip&amp;port=$port&amp;game=$type' title='".$hostname_min." :: PLAYERS:' data-type='iframe'><img style='display:inline-block' src='//".$_SERVER['HTTP_HOST']."/greyfish/icons/users/users.png' alt='Users'/></i>
$gametracker $steam
</span>
$mapimg
<p class=\"caption\" style=\"border-left:4px solid ".$statuscolor."\">
<span style=\"float:left\">IP: <span  onclick='prompt(\"Server: ".$hostname_min.":\",\"".$ip.":".$port."\"); return false;'>".$ip.":".$port."</span><br/>".$hostname_min."</span>
<span style=\"float:right\">Map: ".$map_min."<br/>Players: <i class='fancybox2 uncategorizei' data-fancybox-type='iframe' data-href='//".$_SERVER['HTTP_HOST']."/greyfish/showplayers.php?ip=$ip&amp;port=$port&amp;game=$type' title='".$hostname_min." :: PLAYERS:' data-type='iframe'>".$players."/".$maxplayers."</i></span>
</p>

<div style=\"clear:both\"></div>
<div class=\"statusbar back\">
<div class=\"statusbar filled\" style=\"width: ".$progressbar."%\"></div>
</div>
					
</li>";
}

?>
</ul>
</div>

<script>
$(".fancybox2").fancybox({
		maxWidth	: 850,
		maxHeight	: 600,
		fitToView	: false,
		width		: '30%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
});
	
	$('.bxslider').bxSlider({
  adaptiveHeight: true,
  mode: 'fade',
   onSliderLoad: function(){
	$(".zoverlay").hide();
    $(".zoverlay").animate({
    left: "+=75",
    height: "toggle"
  }, 1000, function() {
  });
  }

});
</script>