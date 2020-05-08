<?php
	
if ($_SITE_INCLUDED !== true) exit();

function getTabTable($mysqli, $tab, $spawn_timeout, $spawn_timeout_time, $authenticated, $site_disabled)
{
	
echo '<table class="table table-bordered table-dark text-left">';
echo '<thead>';
echo '<tr>';
echo '<th scope="col">Effekt</th>';
echo '<th scope="col">Cooldown</th>';
echo '<th scope="col">Spawn</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';


$request = $mysqli->query("SELECT * FROM statuseffects WHERE tab='" . $tab . "' ORDER BY orderid ASC");	
while($res = $request->fetch_assoc())
{

echo '<tr>';
echo '	<th scope="row">' . $res['name'] . '</th>';
echo '	<td>' . $res['price'] . 's</td>';
echo '	<td><a href="?eid=' . $res['id'] . '&tab=' . $res['tab'] . '" class="btn js-spawnbutton';

if ($spawn_timeout || !$authenticated || $site_disabled) { 
	echo " btn-secondary disabled"; 
} else {
	echo " btn-primary";
}

echo '" role="button">';
if ($spawn_timeout) { 
	echo "Cooldown <span class='js-countdown'>" . $spawn_timeout_time . "</span>s"; 
} else { 
	echo "Spawn";
}
echo '</a></td>';
echo '</tr>';

}
	
echo '</tbody>';
echo '</table>';

}



function generateRandomString($length = 16) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

	
	
?>