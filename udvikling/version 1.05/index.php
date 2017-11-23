<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>Usbwebserver</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
	<div id="container">
		<img id="header" src="images/header.png">
		<ul id="menu">
			<li>
				<div id="menuleft"></div>
				<a id="menua" href="http://www.usbwebserver.com">
					USBWebserver.com
				</a>
				<div id="menuright"></div>
			</li>
			<li>
				<div id="menuleft"></div>
				<a id="menua" href="http://www.border-it.nl">
					Border-IT
				</a>
				<div id="menuright"></div>
			</li>
		</ul>
		<div id="topcontent"></div>
		<div id="content">
			<div id="contentleft">

			<?php
				$ip = gethostbyname(gethostname());
				/*$ip = 'localhost';*/
			?>
			<h1>Din IP adresse <?php echo $ip; ?></h1>
			<p>
			<h1>Menu</h1>
			<p>

				<table cellpadding="5" style="border:2px solid white;">
					<tr style="border:2px solid white;">
						<td style="border:2px solid white;"><button type="button" OnClick="window.location='/info/index.html';">Information</button></td>
						<td style="border:2px solid white;">Opdater filen /info/index.html med de oplysninger der skal vises p&aring; sk&aelig;rmen</td>
					</tr>
					<tr style="border:2px solid white;">
						<td style="border:2px solid white;"><button type="button" OnClick="window.location='/resultater/rolling.htm';">Rullende</button></td>
						<td style="border:2px solid white;">Sk&aelig;rmen opdeles i 2 kolonner, der kan vise forskellige klasse startlister eller resultater</td>
					</tr>
					<tr style="border:2px solid white;">
						<td style="border:2px solid white;"><button type="button" OnClick="window.location='/resultater/livestartlist.html';">Startliste</button></td>
						<td style="border:2px solid white;">Viser en udvalgt klasse, der kan skiftes klasse p&aring; sk&aelig;rmen</td>
					</tr>
					<tr style="border:2px solid white;">
						<td style="border:2px solid white;"><button type="button" OnClick="window.location='/resultater/liveresultat.html';">Resultater</button></td>
						<td style="border:2px solid white;">Viser en udvalgt post for een klasse, der kan skiftes klasse p&aring; sk&aelig;rmen</td>
					</tr>
					<tr style="border:2px solid white;">
						<td style="border:2px solid white;"><button type="button" OnClick="window.location='/resultater/relaywarn.php';">Stafet, formelding</button></td>
						<td style="border:2px solid white;">Viser l&oslash;bere der passerer en given radiopost (formelding)</td>
					</tr>
					<tr style="border:2px solid white;">
						<td style="border:2px solid white;"><button type="button" OnClick="window.location='/divmatch/html/overview/overview.html';">Divisionsmatch - oversigt</button></td>
						<td style="border:2px solid white;">Sk&aelig;rmen opdeles i 3 kolonner, der viser det samlede resultat for hver af 3 matcher</td>
					</tr>
					<tr style="border:2px solid white;">
						<td style="border:2px solid white;"><button type="button" OnClick="window.location='/divmatch/html/resultat/resultat1.html';">Divisionsmatch - resultat (match 1)</button></td>
						<td style="border:2px solid white;">Sk&aelig;rmen viser alle resultater fra een match</td>
					</tr>
					<tr style="border:2px solid white;">
						<td style="border:2px solid white;"><button type="button" OnClick="window.location='/divmatch/html/resultat/resultat2.html';">Divisionsmatch - resultat (match 2)</button></td>
						<td style="border:2px solid white;">Sk&aelig;rmen viser alle resultater fra een match</td>
					</tr>
				</table>

			</p>
			<p>
			<?php

				// Test config
				set_include_path($_SERVER["DOCUMENT_ROOT"].'/MeOS_Live_Config');
				include("config_da.php");
				echo "Læser MySQL konfiguration fra: " . $_SERVER["DOCUMENT_ROOT"] . '/MeOS_Live_Config/config_da.php<br>';
				if (strlen(MYSQL_HOSTNAME)>1) {
					echo "MySQL database server=".MYSQL_HOSTNAME;
				} else {
					echo "FEJL: Kan ikke læse navn på MySQL database server!";
				}

				ob_start();
				phpinfo();
				$i = ob_get_contents();
				ob_end_clean();

				echo ( str_replace ( "module_Zend Optimizer", "module_Zend_Optimizer", preg_replace ( '%^.*<body>(.*)</body>.*$%ms', '$1', $i ) ) ) ;
			?>

			</div>
			<a href="#" id="banner"></a>
			<br style="clear:both">
