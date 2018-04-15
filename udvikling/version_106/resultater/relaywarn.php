<?php
  /*
  Copyright 2013 Melin Software HB
  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at
  
      http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
  */
	set_include_path($_SERVER['DOCUMENT_ROOT'].'/meos_mop');  
	include_once('functions.php');
try
{
	session_start();
	header('Content-type: text/html;charset=utf-8');  

	$PHP_SELF = $_SERVER['PHP_SELF'];

	ConnectToDB();

	date_default_timezone_set('Europe/Copenhagen');	
	
	$header = "";
	$meta = "";
	$body = "";
	$cmpId = "";
	$rname= "";
    $refresh = 10; /* sekunder */
	$now = time();
	$since = $now - strtotime("today");	

    $debugt = '';
	if (isset($_GET['debugt']))
	{
	    $debugt = ''.$_GET['debugt'].'';
	    if( strpos($debugt, ':') !== false )
    		$since = strtotime("1970-01-01 $debugt UTC");
	    else
    		$since = (int)$debugt;
    }

	$antal=-1;
	if (isset($_GET['antal']))
		$antal = (int)$_GET['antal'];

	$kolonner=1;
	if (isset($_GET['kolonner']))
		$kolonner = (int)$_GET['kolonner'];

	$maxtid=1200;
	if (isset($_GET['maxtid']))
		$maxtid = (int)$_GET['maxtid'];

	if (isset($_GET['since']))
		$since = (int)$_GET['since'];

	$radio=0;
	if (isset($_GET['radio']))
		$radio = $_GET['radio'];

	if (isset($_GET['refresh']))
		$refresh = (int)$_GET['refresh'];

	if (!isset($_GET['cmp']) || !isset($_GET['radio']) || isset($_GET['start'])) {	
		
		if (isset($_GET['cmp']))
			$cmpId = 1 * (int)$_GET['cmp'];
	
		$header = "V&aelig;lg konkurrence";
		$body = '<form method=get>';
		
		/* get competitions */
		
		$sql = "SELECT c.name, c.date, c.cid, ".
		       "(select count(*) FROM mopRadio r where r.cid = c.cid) as cnt, ".
               "(SELECT count(*) FROM mopTeamMember tm, mopTeam t WHERE tm.cid = c.cid AND t.cid = c.cid AND tm.id = t.id) as legs".
		       " FROM mopCompetition c ".
		       " ORDER BY c.date DESC";     
		$res = mysql_query($sql);
					
		$body = $body.'V&aelig;lg konkurrence:<br><select name=cmp onchange="this.form.submit()">';
		while ($r = mysql_fetch_array($res)) {
			if ($r['cnt']>0 && $r['legs']>0 ) {
				if ($cmpId == $r['cid'])
					$body = $body.'<option selected value="'.$r['cid'].'">'.$r['date'].' - '.$r['name'].'</option>'; 		      
				else
					$body = $body.'<option value="'.$r['cid'].'">'.$r['date'].' - '.$r['name'].'</option>'; 		      
			}
		} 
		$body = $body.'</select>&nbsp;&nbsp;<input type=submit value="go"></form>';

		if (isset($_GET['cmp'])) {
			/* viser klasser og deres startider */	
    		$sql = "SELECT distinct cls.name, t.st".
    		       " FROM  mopclass cls, mopTeam t".
                   " WHERE cls.cid = '$cmpId'".
                   "   AND t.cls = cls.id".
    		       " ORDER BY t.st ASC";     
    		$res = mysql_query($sql);
            $body = $body.'<table colpadding=1 border=1>'; 		      
            $body = $body.'<tr><th>klasse</th><th>Starttid</th></tr>'; 		      
    		while ($r = mysql_fetch_array($res)) {
    		    $tid = $r['st']/10;
                $tid = sprintf("%d:%02d:%02d", $tid/3600, ($tid/60)%60, $tid%60);		      
                $body = $body.'<tr><td>'.$r['name'].'</td><td>'.$tid.' ('.($r['st']/10).')</td></tr>'; 
    		} 
            $body = $body.'</table>'; 		      
			
			$header = "V&aelig;lg radio";
			/* viser mulige radioer og øvrige valg */	
			$header = "V&aelig;lg radio";
			$body = $body.'<form method=get>';
			$body = $body.'<input type=hidden name=cmp value="'.$cmpId.'">';

			/* get radios for class in competition*/
			$sql = "SELECT distinct mopControl.id, mopControl.name FROM mopControl ".
	         	   "WHERE mopControl.cid='$cmpId'";
			$res = mysql_query($sql);
					
			$body = $body.'<table><tr><td>V&aelig;lg radio:</td></tr><tr><td><select name=radio>';
			while ($r = mysql_fetch_array($res)) {
				if ($radio == $r['id'])
					$body = $body.'<option selected value="'.$r['id'].'">'.$r['id'].' - '.$r['name'].'</option>'; 		      
				else
					$body = $body.'<option value="'.$r['id'].'">'.$r['id'].' - '.$r['name'].'</option>'; 		      
			} 
			$body = $body.'</select></td></tr>';

			$body = $body.'<tr><td>max tid p&aring; listen (sek):</td></tr><tr><td><input name=maxtid value="'.$maxtid.'"></td></tr>';
			$body = $body.'<tr><td>max antal viste (-1 ~ alle):</td></tr><tr><td><input name=antal value="'.$antal.'"></td></tr>';
			$body = $body.'<tr><td>kolonner:</td></tr><tr><td><input name=kolonner value="'.$kolonner.'"></td></tr>';
			$body = $body.'<tr><td>refresh (sek):</td></tr><tr><td><input name=refresh value="'.$refresh.'"></td></tr>';
			$body = $body.'<tr><td>(simuler fra HH:mm:ss):</td></tr><tr><td><input name=debugt value="'.$debugt.'"></td></tr>';
			$body = $body.'</table>';

			$body = $body.'<input type=submit value="go"></form>';
		}			
	} else {

		$cmpId = 1 * (int)$_GET['cmp'];

		/* viser data*/
		$next = $since + $refresh;
		if ($debugt == "") $since = $now;

		$url = 'cmp='.$cmpId.'&radio='.$radio.'&since='.$next.'&debugt='.$debugt.'&antal='.$antal.'&kolonner='.$kolonner.'&maxtid='.$maxtid.'&refresh='.$refresh;
		$meta = '<META HTTP-EQUIV="refresh" content="'.$refresh.';?'.$url.'"/>';	


		$radio = $_GET['radio'];

		print "<i>Siden er sidst indl&aelig;st ".date('d.m.Y H:i:s', $now)."</i> - refresh=".$refresh." sek.";
		print '<a href="'.$_SERVER["SCRIPT_NAME"].'?start=1&'.$url.'">forfra</a>';
		if ($debugt != "") print "<br>debug som om klokken er ".sprintf("%d:%02d:%02d", $since/3600, ($since/60)%60, $since%60)." <a href='?$url'>next</a>";

		$rid = (int)$radio;
		$sql = "SELECT name FROM mopControl WHERE cid='$cmpId' AND id='$rid'";
	
		$res = mysql_query($sql);
		$rinfo = mysql_fetch_array($res);
		$rname = $rinfo['name'];

		$header = $rname;


		/* get results for class in competition*/
		$sql = "SELECT cl.Name AS class, team.id AS id, cmp.name AS name, m.leg AS leg, ".
			   "       team.name AS team, team.st + radio.rt as time, 1 AS status ".
	           " FROM mopRadio AS radio, mopTeamMember AS m, mopTeam AS team, ".
	           "      mopCompetitor AS cmp , mopClass as cl".
	           " WHERE radio.ctrl=$rid".
	           " AND radio.id=cmp.id ".
	           " AND m.rid = radio.id ".
	           " AND m.id = team.id ".
	           " AND cl.id = cmp.cls".
	           " AND radio.cid = '$cmpId' ".
	           " AND m.cid = radio.cid ".
	           " AND team.cid = radio.cid ".
	           " AND cmp.cid = radio.cid ".
	           " AND cmp.cid = radio.cid ".
	           " AND cl.cid = radio.cid ".
	           " ORDER BY team.st + radio.rt DESC";
	
		$res = mysql_query($sql);
		$out = array();
		$count = 0;
		while ($r = mysql_fetch_array($res)) {
			/* 
			'time' i antal 1/10 sek - transformeres til antal sekunder
			debugt fratrækkes for at forskyde i tilfælde af debug/test
			*/
			$t = $r['time']/10;
			if ($t > 0 ) {
			    if (($debugt == "") or ($t < $since))
			    {
			        /* tjek om de er for gamle eller vi har nået max antal */
    				$dt = $since - $t;
    				if ($dt > $maxtid  || ($antal>0 && $count>=$antal)) {    				    
    				    // print "break".sprintf("%d:%02d:%02d", $t/3600, ($t/60)%60, $t%60);
    				            					
    					break;
    				}

        			$row = array();
        			
        			$row['class'] = $r['class'];      
        			$row['leg'] = $r['leg'];      
        			$row['team'] = $r['id']." - ".$r['team'];
        			$row['name'] = $r['name'];      
        	
					$row['time'] = sprintf("%d:%02d:%02d", $t/3600, ($t/60)%60, $t%60);
	
					if ($t > $since-$refresh)
						$row['new'] = 'pink';
					elseif ($dt < 60)					
						$row['new'] = 'pink';
					elseif ($dt < 300)					
						$row['new'] = 'yellow';
					else
						$row['new'] = '';
	
	
					if ($dt > 0)
						$row['since'] = sprintf("%02d:%02d", ($dt/60)%60, $dt%60);
					else
						$row['since'] = '';
						
                    // $row['t'] = $t;    						
                    // $row['s'] = $since;    						
					$count++;
					$out[$count] = $row;
                }	
			}
		 }

	    // fill with empty rows to make it adjust
	    // count must be a multiple of columns
	    
	    while ($count % $kolonner > 0)
	    {
	        $count++;
			$row = array();
			$row['class'] = '';      
			$row['leg'] = '';
			$row['team'] = '';
			$row['name'] = '';
			$row['time'] = '';
			$row['new'] = '';
			$row['since'] = '';
            $out[$count] = $row;     			
        }
	}	
}
catch (Exception $e) {
    die('Caught exception: '.$e->getMessage());
}

?>



<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<?php
print $meta;
?>

<title>MeOS Online Results</title>
<style type="text/css">
body {
  font-family: verdana, arial, sans-serif;
  font-size: 9pt;
  background-color: #FFFFFF;
}

a.button {
  border-style: ridge;
  border-color: #b0c4de;
  background-color:#b0c4de;
  color: #900000;
  text-decoration: none;
  padding: 0.1em 0.3em;
  margin: 1em;
}

h1 {text-shadow: 3px 3px 3px #AAAAAA;}

th {text-align:left;}

td {padding-right:1em;}

tr:nth-child(even) {
    background-color: #EEEEEE;
}

tr.yellow {
    color: red;
}

.container{
  column-count: <?php echo $kolonner; ?>;
  column-gap: 20px;
  -moz-column-count: <?php echo $kolonner; ?>;
  -moz-column-gap: 20px;
   -webkit-column-count: <?php echo $kolonner; ?>;
   -webkit-column-gap: 20px;
}
</style>
</head>
<body>
<?php
	 

?>
<?php
	print "<h3>".$header."</h3>\n";         

	if ($meta <> '') {
		print '<div class="container">';
		formatResult2($out, $kolonner);
		print '</div>';
	} else
		print $body;
?> 
<div style="clear:both;padding-top:3em;color: grey;">

 Results provided by <a href="http://www.melin.nu/meos" target="_blank">MeOS Online Results</a>.

</div>

</body></html>

<?php

function formatResult2($result, $kolonner) {

  global $lang;

  $head = false;
  $cnt = 0;
  $m = count($result) % $kolonner;
  $maxpercol = (count($result) - $m) / $kolonner;
  if ($m>0)
  {
	  $maxpercol += 1;
  }
  print "<table>";

  foreach($result as $row) {
    $cnt++;            

    if ($head == false || $cnt > $maxpercol) {
      if ($head == true) {
         print "</tbody></table><table>";
      }

      print "<thead><tr>";

      foreach($row as $key => $cell) {
      
        if ($key <> 'new')
        {
          $k = $key;
          if (array_key_exists($k, $lang)) $k = $lang[$key];
          print "<th>".$k."</th>\n";  
        }   
      }

      print "</tr></thead><tbody>";

      $head = true; 
      $cnt = 1;
    }

    if ($row['new'] <> '')
      print "<tr style='background-color:".$row['new']."'>";	
    else 
	  print "<tr>";
    
	foreach($row as $key => $cell) {
      if ($key <> 'new')
	    print "<td nowrap>$cell</td>";  

      }
      print "</tr>";
    }
    
  print "</tbody></table>";  
}  
?>