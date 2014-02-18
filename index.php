<?php
// ENTER YOUR BITCOIN WALLET ADDRESS HERE
$address = '1fGVYgFtzmF5kDDozv8iuk65qJYHveyLu';
// FINISHED EDITING
?>

<html>
<head>
	<title>Middlecoin Stats Checker</title>
	<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>

<?php
ini_set('memory_limit','128M');
ini_set('default_charset', 'utf-8');

$site = 'http://www.middlecoin.com/json';

// Helper function
function do_curl($url) {
		if(function_exists("curl_init")) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$content = curl_exec($ch);
			curl_close($ch);
			return $content;
		}
		else {
			return file_get_contents($url);
		}
}

// Collect data from middlecoin
$data = do_curl($site);
$json = json_decode($data);

// Locate wallet address stats
foreach ($json->report as $report) {
	if ($report[0] == $address) {
		$myStats = $report;
		break;
	}
	else {
		$myStats =  'false';
	}
}

if ($myStats != 'false') {

	$theDate = date('U');
	$dt = new DateTime("@$theDate");
	$address 						= $myStats[0];
	$lastHourShares 				= $myStats[1]->lastHourShares;
	$immatureBalance 				= $myStats[1]->immatureBalance;
	$lastHourRejectedShares			= $myStats[1]->lastHourRejectedShares;
	$paidOut 						= $myStats[1]->paidOut;
	$unexchangedBalance 			= $myStats[1]->unexchangedBalance;
	$megahashesPerSecond 			= $myStats[1]->megahashesPerSecond;
	$bitcoinBalance 				= $myStats[1]->bitcoinBalance;
	$rejectedMegahashesPerSecond 	= $myStats[1]->rejectedMegahashesPerSecond;
	$predictedBalance				= $immatureBalance+$unexchangedBalance+$bitcoinBalance;
	
	// Display Stats
	echo '<h1>Middlecoin Stats from: '.$dt->format('Y-m-d H:i:s').'</h1>';
	echo '<table>';
	echo '<tr><td>';
	echo 'Address</td><td>'.$address.'</td></tr>';
	echo '<tr><td>';
	echo 'Last Hour Shares</td><td>'.$lastHourShares.'</td></tr>';
	echo '<tr><td>';
	echo 'Last Hour Rejected Shares</td><td>'.$lastHourRejectedShares.'</td></tr>';
	echo '<tr><td>';
	echo 'Immature Balance</td><td>'.$immatureBalance.'</td></tr>';
	echo '<tr><td>';
	echo 'Unexchanged Balance</td><td>'.$unexchangedBalance.'</td></tr>';
	echo '<tr><td>';
	echo 'Total Balance</td><td>'.$bitcoinBalance.'</td></tr>';
	echo '<tr><td>';
	echo '<strong>Predicted Balance</strong></td><td><strong>'.$predictedBalance.'</strong></td></tr>';
	echo '<tr><td>';	
	echo 'MH/s</td><td>'.$megahashesPerSecond.'</td></tr>';
	echo '<tr><td>';
	echo 'Rejected MH/s</td><td>'.$rejectedMegahashesPerSecond.'</td></tr>';
	echo '<tr><td>';
	echo 'Total Paid Out</td><td>'.$paidOut.'</td></tr>';
	echo '</table>';
}

elseif ($myStats == 'false') {
	echo 'Address not found';
}
?>

</body>
</html>