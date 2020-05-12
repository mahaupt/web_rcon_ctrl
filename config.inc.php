<?php
if ($_SITE_INCLUDED !== true) exit();

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$site_enabled = true;

$mc_host = 'localhost'; // Server host name or IP
$mc_port = 25575;                      // Port rcon is listening on
$mc_password = ''; // rcon.password setting set in server.properties
$mc_timeout = 3;                       // How long to timeout.

$twitch_base_url = "https://id.twitch.tv/oauth2/";
$twitch_client_id = "";
$twitch_client_secret = "";
$twitch_redirect_url = "";

$mysql_host = "localhost";
$mysql_user = "streamui";
$mysql_pw = "";
$mysql_db = "streamui";

?>