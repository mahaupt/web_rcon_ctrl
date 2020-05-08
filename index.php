<?php
session_start();

$_SITE_INCLUDED = true;

require_once "config.inc.php";
require_once "function.inc.php";
require_once 'oauth.inc.php';
require_once "rcon.php";
use Thedudeguy\Rcon;

$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_pw, $mysql_db);
$oauth = new oauth("twitch", $twitch_base_url, $twitch_client_id, $twitch_client_secret);
$rcon = new Rcon($mc_host, $mc_port, $mc_password, $mc_timeout);

//session timeout
if (!array_key_exists('sess_timeout', $_SESSION))
{
	$_SESSION['sess_timeout'] = 0;
}

$spawn_timeout = false;
$spawn_timeout_time = 0;
if ($_SESSION['sess_timeout'] > time())
{
	$spawn_timeout = true;
	$spawn_timeout_time = $_SESSION['sess_timeout'] - time();
}


//tab control
$active_tab = 0;
if (array_key_exists('tab', $_GET))
{
	if(is_numeric($_GET['tab']))
	{
		$active_tab = $_GET['tab'];
		
		if ($active_tab != 0 && $active_tab != 1 && $active_tab != 2)
		{
			$active_tab = 0;
		}
	}
}

require_once 'login.inc.php';
require_once 'spawn.inc.php';

?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Ruiniere das Spiel</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<style>
body {
	background-color: #343a40;
	color: white;
	margin-top: 15px;
}
h2 {
	margin-bottom: 15px;
}
/**
 * Special thanks to: http://blog.koalite.com/bbg/
 */
.btn-twitch { 
  color: #FFFFFF; 
  background-color: #6441A5; 
  border-color: #2F1F4E; 
} 
 
.btn-twitch:hover, 
.btn-twitch:focus, 
.btn-twitch:active, 
.btn-twitch.active, 
.open .dropdown-toggle.btn-twitch { 
  color: #FFFFFF; 
  background-color: #472e75; 
  border-color: #2F1F4E; 
} 
 
.btn-twitch:active, 
.btn-twitch.active, 
.open .dropdown-toggle.btn-twitch { 
  background-image: none; 
} 
 
.btn-twitch.disabled, 
.btn-twitch[disabled], 
fieldset[disabled] .btn-twitch, 
.btn-twitch.disabled:hover, 
.btn-twitch[disabled]:hover, 
fieldset[disabled] .btn-twitch:hover, 
.btn-twitch.disabled:focus, 
.btn-twitch[disabled]:focus, 
fieldset[disabled] .btn-twitch:focus, 
.btn-twitch.disabled:active, 
.btn-twitch[disabled]:active, 
fieldset[disabled] .btn-twitch:active, 
.btn-twitch.disabled.active, 
.btn-twitch[disabled].active, 
fieldset[disabled] .btn-twitch.active { 
  background-color: #6441A5; 
  border-color: #2F1F4E; 
} 
 
.btn-twitch .badge { 
  color: #6441A5; 
  background-color: #FFFFFF; 
}
</style>
<script>
window.setInterval(function(){
$('.js-countdown').each(function () {
	var time = $(this).html();
	time--;
	if (time < 0)
	{
		time = 0;
		clearInterval();
		$('.js-spawnbutton').each(function () {
			$(this).html("Spawn");
			$(this).removeClass("btn-secondary");
			$(this).addClass("btn-primary");
			$(this).removeClass("disabled");
		});
	}
	
	$(this).html(time);
});
}, 1000);
</script>
<script src="https://kit.fontawesome.com/259161033a.js" crossorigin="anonymous"></script>
</head>
<body>

<?php   
include "table.inc.php";
?>

<!-- Scripts am Ende //-->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>