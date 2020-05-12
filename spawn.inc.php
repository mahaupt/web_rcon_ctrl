<?php
	
if ($_SITE_INCLUDED !== true) exit();
	
//spawn
if (array_key_exists('eid', $_GET) && $site_enabled && !$spawn_timeout && $site_authenticated)
{
	if(is_numeric($_GET['eid']))
	{
		//double check timeout
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
				$display_error = "Bitte warte bis zum Ende des Timeouts!";
			}
		}
		$t_pre->close();
		
		
		if (!$spawn_timeout)
		{
			//spawn item
			$eid = $_GET['eid'];
			$pre = $mysqli->prepare("SELECT * FROM statuseffects WHERE id=?");
			$pre->bind_param("i", $eid);
			$pre->execute();
			$result = $pre->get_result();
			
			if($result->num_rows === 1)
			{
				$row = $result->fetch_assoc();
				
				if ($oauth->getUsername() == "cbacon93")
				{
					$_SESSION['sess_timeout'] = 0;
				} else {
					$_SESSION['sess_timeout'] = time() + $row['price'];
				}
				$log_spawnname = $row['name'];
				
				if ($rcon->connect())
				{
					$cmd = explode(PHP_EOL, $row['cmd']);
					$cmd = str_replace("<viewer>", $oauth->getUsername(), $cmd);
					$cmd = str_replace("<target>", "@a", $cmd);
					
					foreach($cmd as $c)
					{
						$rcon->sendCommand($c);
					}
					$rcon->disconnect();
				}
				
			}
			
			$pre->close();
			
			
			//insert into timeout table
			$pre2 = $mysqli->prepare("INSERT INTO spawntimeout SET userid=?, timeout=?");
			$pre2->bind_param("si", $oauth->getUserId(), $_SESSION['sess_timeout']);
			$pre2->execute();
			$pre2->close();
			
				
			header('location: /?tab=' . $active_tab);
		}
	}	
}

?>