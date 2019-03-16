<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>MeOS Live</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
	<div id="container">
		<div id="content">
			<div id="contentleft">

			<?php
				//echo gmdate("H:i:s", 86400);

				$ip = gethostbyname(gethostname());
				/*$ip = 'localhost';*/
			?>
			<h1>Din IP adresse <?php echo $ip; ?></h1>
			<p>
			<h1>Funktioner i version 1.10</h1>
			<p>

				<table cellpadding="5" style="border:2px solid white;">
					<tr style="border:2px solid white;">
						<td style="border:2px solid white;"><b>1</b></td>
						<td style="border:2px solid white;"><button type="button" OnClick="window.location='/info/index.html';">Information</button></td>
						<td style="border:2px solid white;">Opdater filen /info/index.html med de oplysninger der skal vises p&aring; sk&aelig;rmen</td>
					</tr>
					<tr style="border:2px solid white;">
						<td style="border:2px solid white;"><b>2/3</b></td>
						<td style="border:2px solid white;"><button type="button" OnClick="window.location='/resultater/rolling.htm';">Rullende</button></td>
						<td style="border:2px solid white;">Sk&aelig;rmen opdeles i 2 kolonner, der kan vise forskellige klasse startlister eller resultater</td>
					</tr>
					<tr style="border:2px solid white;">
						<td style="border:2px solid white;"><b>4</b></td>
						<td style="border:2px solid white;"><button type="button" OnClick="window.location='/resultater/relaywarn.php';">Stafet, formelding</button></td>
						<td style="border:2px solid white;">Viser l&oslash;bere der passerer en given radiopost (formelding)</td>
					</tr>
					<tr style="border:2px solid white;">
						<td style="border:2px solid white;"><b>5</b></td>
						<td style="border:2px solid white;"><button type="button" OnClick="window.location='/divmatch/html/overview/overview.html';">Divisionsmatch - oversigt</button></td>
						<td style="border:2px solid white;">Sk&aelig;rmen opdeles i 2-3 kolonner, der viser det samlede resultat for hver af 3 matcher</td>
					</tr>
					<tr style="border:2px solid white;">
						<td style="border:2px solid white;"><b>6</b></td>
						<td style="border:2px solid white;"><button type="button" OnClick="window.location='/divmatch/html/resultat/resultat1.html';">Divisionsmatch - resultat (match 1)</button></td>
						<td style="border:2px solid white;">Sk&aelig;rmen viser alle resultater fra een match</td>
					</tr>
				</table>
			</div>

			<?php

				ob_start();
				phpinfo();
				$i = ob_get_contents();
				ob_end_clean();

				//echo ( str_replace ( "module_Zend Optimizer", "module_Zend_Optimizer", preg_replace ( '%^.*<body>(.*)</body>.*$%ms', '$1', $i ) ) ) ;
			?>
			<br style="clear:both">

			<br>
			<?php
				// Test config
				include_once('meos_mop/config_da.php');
				print "<br>Læser MySQL konfiguration fra: " . $_SERVER["DOCUMENT_ROOT"] . '/meos_mop/config_da.php';
				if (strlen(MYSQL_HOSTNAME)>1) {
					print "<br>MySQL database server=".MYSQL_HOSTNAME;
				} else {
					print "<br><b>FEJL:</b> Kan ikke læse navn på MySQL database server!";
				}

				// Er MySQl og webserver samme server
				if (MYSQL_HOSTNAME<>$ip) {
					print "<br><b>ADVARSEL:</b> MySQL databasen og webserveren køre ikke på samme maskine!";
					print "<br>   MySQL database=".MYSQL_HOSTNAME;
					print "<br>   Webserver =".$ip;
				}

				print "<br>PHP version " . phpversion();
			?>
