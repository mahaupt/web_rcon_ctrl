<?php
	
if ($_SITE_INCLUDED !== true) exit();

$site_authenticated = false;

if (array_key_exists('code', $_GET))
{
	$acode = $_GET['code'];
	
	if ($oauth->getAccessToken($acode, $twitch_redirect_url, $_GET['state']))
	{
		header('location: /?tab=' . $active_tab);
	}
}


$site_authenticated = $oauth->isAuthenticated();


if (array_key_exists('logout', $_GET) && $site_authenticated)
{
	$oauth->logout();
	header('location: /');
}

?>