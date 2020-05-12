<?php
	
if ($_SITE_INCLUDED !== true) exit();

$site_authenticated = true;

if (array_key_exists('code', $_GET))
{
	$acode = $_GET['code'];
	
	if ($oauth->getAccessToken($acode, $twitch_redirect_url, $_GET['state']))
	{
		//reload any timer
		$t_pre = $mysqli->prepare("SELECT * FROM spawntimeout WHERE userid=? ORDER BY timeout DESC LIMIT 1");
		$user_id = $oauth->getUserId();
		$t_pre->bind_param("s", $user_id);
		$t_pre->execute();
		$t_res = $t_pre->get_result();
		if($t_res->num_rows === 1)
		{
			$row = $t_res->fetch_assoc();
			if ($row['timeout'] > time())
			{
				//session deleted manually or new browser
				$spawn_timeout = true;
				$spawn_timeout_time = $row['timeout'] - time();
				$_SESSION['sess_timeout'] = $row['timeout'];
			}
		}
		$t_pre->close();
		
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