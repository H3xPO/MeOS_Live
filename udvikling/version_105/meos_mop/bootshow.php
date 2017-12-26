<?php

	include_once('functions.php');

	session_start();

	$PHP_SELF = $_SERVER['PHP_SELF'];

	ConnectToDB();

	$act = getArg('action');
	$cmp = getArg('cmp');
	$cls = getArg('cls');
	$leg = getArg('leg');
	$ord = getArg('ord');
	$radio = getArg('radio');
	$multiple = getArg('multiple');
	$relayrolling  = getArg('relayrolling');

	$debug = getArg('debug');
	$msg = "";
	$logsql = "";

	$output = '';
	if (($act == "") || ($act=="comp")) {

		/* get competitions */

		$sql = "SELECT name, date, cid FROM mopCompetition ORDER BY date DESC";
		$logsql = "$logsql \n ---- SQL --- $sql";
		$res = mysql_query($sql);

		$output = formatJSON($res);
	}
	elseif (($act == "class") && ($cmp != "")) {

		/* get classes in competition */
		$sql = "SELECT name, id FROM mopClass WHERE cid = '$cmp' ORDER BY ord";
		$logsql = "$logsql \n ---- SQL --- $sql";
		$res = mysql_query($sql);

		$output = formatJSON($res);
	}
	elseif (($act == "classname") && ($cmp != "") && ($cls != "")) {

		/* get classes in competition */
		$sql = "SELECT name, id FROM mopClass WHERE cid = '$cmp' AND id='$cls'";
		$logsql = "$logsql \n ---- SQL --- $sql";
		$res = mysql_query($sql);

		$output = formatJSON($res);
	}
	elseif (($act == "leg") && ($cmp != "") && ($cls != "")) {

		/* get legs in the class */
		$sql = "SELECT DISTINCT leg FROM mopTeamMember tm, mopTeam t ".
		       " WHERE tm.cid = '$cmp' AND t.cid = '$cmp' AND tm.id = t.id AND t.cls = $cls".
		       " ORDER BY 1 ASC";
		$logsql = "$logsql \n ---- SQL --- $sql";
		$res = mysql_query($sql);

		$output = formatJSON($res);
	}
	elseif (($act == "radio") && ($cmp != "") && ($cls != "")) {

		/* get radios for class in competition*/
		$sql = "SELECT leg, ctrl, mopControl.name FROM mopClassControl, mopControl ".
         	   "WHERE mopControl.cid='$cmp' AND mopClassControl.cid='$cmp' ".
			   "AND mopClassControl.id='$cls' AND mopClassControl.ctrl=mopControl.id AND mopClassControl.leg='$leg' ".
			   "UNION ".
			   "SELECT 'finish', 'finish', 'finish' FROM mopCompetition ".
         	   "WHERE cid='$cmp' ";
		$logsql = "$logsql \n ---- SQL --- $sql";
		$res = mysql_query($sql);

		$output = formatJSON($res);
		//$output = $sql;
	}
	elseif (($act == "result") && ($cmp != "") && ($cls != "")) {

		/* get results for class in competition*/
		$results = array();
	    $sql = "SELECT max(leg) FROM mopTeamMember tm, mopTeam t WHERE tm.cid = '$cmp' AND t.cid = '$cmp' AND tm.id = t.id AND t.cls = $cls";
		$logsql = "$logsql \n ---- SQL --- $sql";
    	$res = mysql_query($sql);
    	$r = mysql_fetch_array($res);
	    $numlegs = $r[0];

		$ord=0;
    	if ($numlegs > 1) {
			//Multiple legs, relay etc.
/*
			for ($k = 1; $k <= $numlegs; $k++) {
	        	$sql = "SELECT max(ord) FROM mopTeamMember tm, mopTeam t WHERE t.cls = '$cls' AND tm.leg=$k AND ".
	            	   "tm.cid = '$cmp' AND t.cid = '$cmp' AND tm.id = t.id";

				$res = mysql_query($sql);
	        	$r = mysql_fetch_array($res);
	        	$numparallel = $r[0];
	        	if ($numparallel == 0) {
					$sql = "SELECT ctrl, mopControl.name FROM mopClassControl, mopControl ".
						   "WHERE mopControl.cid='$cmp' AND mopClassControl.cid='$cmp' ".
						   "AND mopClassControl.id='$cls' AND mopClassControl.ctrl=mopControl.id AND leg='$leg' AND ord='$ord'";

					selectLegRadio($cls, $k, 0);
				}
			}
*/
			if ($leg == '') {
				$leg = $numlegs;
			}

			if ($radio!='') {
				$lmin = ($relayrolling=="" ? $leg : 1);
				$usedteams = array();
				$results = null;
				for ($l = $leg; $l>= $lmin; $l--) {
					if ($radio == 'finish') {
						$rname = $lang["finish"];

						$sql = "SELECT t.id AS id, cmp.name AS name, t.name AS team, cmp.rt AS time, cmp.stat AS status, ".
							   "cmp.it+cmp.rt AS tottime, greatest(t.stat,cmp.tstat) AS totstat".
							   " FROM mopTeamMember tm, ".
							   "     mopCompetitor cmp, ".
							   "     mopTeam t ".
							   "WHERE t.cls = '$cls' ".
							   "  AND t.id = tm.id ".
							   "  AND tm.rid = cmp.id ".
							   "  AND t.cid = '$cmp' ".
							   "  AND tm.cid = '$cmp' ".
							   "  AND cmp.cid = '$cmp' ".
							   (($relayrolling=="" or $l == 1)? "  AND cmp.tstat>=0 " : "  AND cmp.stat=1 AND cmp.tstat<=1 ").
							   "  AND tm.leg='$l' ".
							   "  AND tm.ord='$ord' ".
							   (count($usedteams)>0 ? "  AND t.id not in (".implode(",", $usedteams).") " : "").
							   "ORDER BY cmp.stat, cmp.rt ASC, t.id";
					}
					else {
						$sql = "SELECT team.id AS id, cmp.name AS name, team.name AS team, radio.rt AS time, 1 AS status, ".
							   " cmp.it+radio.rt AS tottime, cmp.tstat AS totstat".
							   " FROM mopRadio AS radio, mopTeamMember AS m, mopTeam AS team, mopCompetitor AS cmp ".
							   "WHERE radio.ctrl='$radio' ".
							   "  AND radio.id=cmp.id ".
							   "  AND m.rid = radio.id ".
							   "  AND m.id = team.id ".
							   "  AND cmp.stat<=1 ".
							   "  AND m.leg='$l' AND m.ord='$ord' ".
							   "  AND cmp.cls='$cls' ".
							   "  AND team.cid = '$cmp' AND m.cid = '$cmp' AND cmp.cid = '$cmp'  AND radio.cid = '$cmp' ".
							   "ORDER BY radio.rt ASC ";
					}

					$logsql = "$logsql \n ---- SQL team $l --- $sql";
					$res = mysql_query($sql);
					$resx = calculateResult($res);

					if (count($usedteams)==0) {
						# first set of teams in order
						$results = $resx;
						foreach($resx as $r){
							$usedteams[] = $r['id'];
						}
					} else {
						$plstart = count($usedteams);
						foreach($resx as $r){
							/* add lesser placed teams in order without placing */
							if ($r['place'] != ''){
								$r['place'] = $r['place'] + $plstart;
							}
							$r['totafter'] = '';
							$results[] = $r;
							$usedteams[] = $r['id'];
						}
					}
				}
				if ($radio == 'finish') {
					/* populate with runners */
					/* place, name, team, time, after, tottime, totstat */
					/* ["Pl.","Navn","Klub","Tid","&nbsp;","Samlet","&nbsp;"] */
					/* ["Pl.","Tur", "Navn" "Klub (hold)", "Tur tid", "Tur efter", "Samlet tid", "Samlet efter"]*/

					$head = true;
				    $rows = array();
					foreach($results as $r) {
						if ($head == true) {
	    	    			$rrow = array();

		        			$rrow[] = 'place';
		        			$rrow[] = 'team';
		        			$rrow[] = 'time';
		        			$rrow[] = 'after';
		        			$rrow[] = 'tottime';
		        			$rrow[] = 'totafter';

							$head = false;
						}

						/* team details */
	        			$rrow = array();

	        			$rrow['place'] = $r['place'];
	        			$rrow['team'] = $r['team'];
	        			$rrow['time'] = '';
	        			$rrow['after'] = '';
	        			$rrow['tottime'] = $r['tottime'];
	        			$rrow['totafter'] = $r['totafter'];

						$rows[] = $rrow;

						/* get runners */
		      			$rrow = array();

		          		$sql = "SELECT t.id AS id, cmp.name AS name, t.name AS team, cmp.rt AS time, cmp.stat AS status, ".
		                 	   "cmp.it+cmp.rt AS tottime, cmp.tstat AS totstat, tm.leg ".
			                   "FROM mopTeamMember tm, mopCompetitor cmp, mopTeam t ".
		                 	   "WHERE t.cls = '$cls' AND t.id = tm.id AND tm.rid = cmp.id ".
		                       "AND t.cid = '$cmp' AND tm.cid = '$cmp' AND cmp.cid = '$cmp' AND t.stat>=0 ".
		                       "AND tm.ord='$ord' AND t.id='".$r['id']."' AND tm.leg <= '$leg' ORDER BY tm.leg";

						$logsql = "$logsql \n ---- SQL runners --- $sql";
						$res = mysql_query($sql);
						$tottime = 0;
						$tempstat = 0;
						while ($rr = mysql_fetch_array($res)) {

		        			$rrow['place'] = '';
		        			$rrow['team'] = $rr['leg'].". ".$rr['name'];

							$t = $rr['time']/10;
							if ($t > 0) {
			        			$rrow['time'] = sprintf("%d:%02d:%02d", $t/3600, ($t/60)%60, $t%60);
			        			$tottime  = $tottime + $t;
			        		}
							else{
								$rrow['time'] = "--"; // No timing
								if ($rr['status'] > 1 ) {
									$tottime  = 0;
								}
							}

							if ($rr['status']==1) {
								if ($tottime > 0)
				        			$rrow['after'] = sprintf("%d:%02d:%02d", $tottime/3600, ($tottime/60)%60, $tottime%60);
								else
	        						$rrow['after'] = "--"; // No timing
							}
							else {
								$rrow['after'] = getStatusString($rr['status']);
							}

							$rows[] = $rrow;
		      			}
					}

					$results  = $rows;
				}
			}
	    }
	    else {
			if (is_null($numlegs)) {
		        //No teams;
		        $msg ="no teams";
	    	    if ($radio!='') {
					if ($radio == 'finish') {
					    if ($multiple<>"") {
    	            		$sql = "SELECT cmp.id AS id, cmp.name AS name, org.name AS team, cmp.rt AS time, cmp.stat AS status, cmp.rt+cmp.it AS tottime, cmp.tstat AS totstat ".
        	                		   "FROM mopCompetitor cmp LEFT JOIN mopOrganization AS org ON cmp.org = org.id AND cmp.cid = org.cid ".
        		                   	   "WHERE cmp.cls = '$cls' ".
        		                       "AND cmp.cid = '$cmp' AND cmp.stat>0 ORDER BY cmp.stat, cmp.rt ASC, cmp.id";
		                } else {
    	            		$sql = "SELECT cmp.id AS id, cmp.name AS name, org.name AS team, cmp.rt AS time, cmp.stat AS status ".
    	                		   "FROM mopCompetitor cmp LEFT JOIN mopOrganization AS org ON cmp.org = org.id AND cmp.cid = org.cid ".
    		                   	   "WHERE cmp.cls = '$cls' ".
    		                       "AND cmp.cid = '$cmp' AND cmp.stat>0 ORDER BY cmp.stat, cmp.rt ASC, cmp.id";
                        }
						$rname = $lang["finish"];
					}
					else {
			            $sql = "SELECT cmp.id AS id, cmp.name AS name, org.name AS team, radio.rt AS time, 1 AS status ".
			                   "FROM mopRadio AS radio, mopCompetitor AS cmp ".
			                   "LEFT JOIN mopOrganization AS org ON cmp.org = org.id AND cmp.cid = org.cid ".
			                   "WHERE radio.ctrl='$radio' ".
			                   "AND radio.id=cmp.id ".
			                   "AND cmp.stat<=1 ".
			                   "AND cmp.cls='$cls' ".
			                   "AND cmp.cid = '$cmp' AND radio.cid = '$cmp' ".
			                   "ORDER BY radio.rt ASC ";
					}
					$logsql = "$logsql \n ---- SQL --- $sql";
					$res = mysql_query($sql);
					$results = calculateResult($res);
				}
			}
		    else {
		    	// Single leg (patrol etc)
		    	$msg ="single leg";
		 		if ($radio!='') {
					if ($radio == 'finish') {
						$sql = "SELECT t.id AS id, cmp.name AS name, t.name AS team, t.rt AS time, t.stat AS status ".
							   "FROM mopTeamMember tm, mopCompetitor cmp, mopTeam t ".
							   "WHERE t.cls = '$cls' AND t.id = tm.id AND tm.rid = cmp.id AND tm.leg=1 ".
							   "AND t.cid = '$cmp' AND tm.cid = '$cmp' AND cmp.cid = '$cmp' AND t.stat>0 ".
							   "ORDER BY t.stat, t.rt ASC, t.id";
						$rname = $lang["finish"];
					}
					else {
						$sql = "SELECT team.id AS id, cmp.name AS name, team.name AS team, radio.rt AS time, 1 AS status ".
							   "FROM mopRadio AS radio, mopTeamMember AS m, mopTeam AS team, mopCompetitor AS cmp ".
							   "WHERE radio.ctrl='$rid' ".
							   "AND radio.id=cmp.id ".
							   "AND m.rid = radio.id ".
							   "AND m.id = team.id ".
							   "AND cmp.stat<=1 ".
							   "AND m.leg=1 ".
							   "AND cmp.cls='$cls' ".
							   "AND radio.cid = '$cmp' AND m.cid = '$cmp' AND team.cid = '$cmp' AND cmp.cid = '$cmp' ".
							   "ORDER BY radio.rt ASC ";
					}

					$logsql = "$logsql \n ---- SQL --- $sql";
					$res = mysql_query($sql);
					$results = calculateResult($res);
				}
			}
		}
		$output = formatJSON2($results, true);
	}
	elseif (($act == "result_mid") && ($cmp != "") && ($cls != "")) {

			// Test with: http://localhost/meos_mop/bootshow.php?action=result_mid&cmp=1&cls=1&debug=1

			// Get the name of the db with details and the zerotime
			$sql = "SELECT cmp.name AS name, oev.name as oevname, oev.nameId AS nameId, oev.zerotime AS zerotime ".
				"FROM mopCompetition cmp, oEvent oev ".
				"WHERE cmp.cid = '$cmp' ".
				"AND cmp.name=oev.name ";
				$logsql = "$logsql \n ---- SQL result_mid --- $sql";

			$res = mysql_query($sql);
			$results = $res;

			if ($row = mysql_fetch_assoc($results)) {
						$database=$row['nameId'];
						$zerotime=$row['zerotime'];
					} else {
						$database="missing";
						$zerotime="missing";
					}

			// Stop if db with details not is found
			if ($database=="missing") {
				die("Fejl");
			}

  		// Select the the runners
			$sql = "SELECT oRu.Id As id, oRu.Name As name, oCl.Name as team, (oRu.FinishTime-oRu.StartTime) as time, '$zerotime'+oRu.FinishTime As lastseen, oRu.Status as status, oRu.CardNo AS cardno ".
				"FROM ".$database.".oRunner AS oRu LEFT JOIN ".$database.".oClub AS oCl ON oRu.Club=oCl.Id ".
				"WHERE Class='$cls' ".
				"AND oRu.removed=0 ".
				"ORDER BY status, time ";
				$logsql = "$logsql \n ---- SQL result_mid --- $sql";

			$res = mysql_query($sql);
		 	$results = $res;

			$head = true;
			$rows = array();
			while($r = mysql_fetch_assoc($results)) {
				if ($head == true) {
						$rrow = array();

							$rrow[] = 'check';
							$rrow[] = 'name';
							$rrow[] = 'team';
							//$rrow[] = 'radio';
							$rrow[] = 'finish';
							$rrow[] = 'time';
							$rrow[] = 'status';

							$head = false;
				}

				/* Runner details */

				/* Have the runner punched CHECK */
				$check="";
				$sql = "SELECT '$zerotime'+Time As Time ".
				 			"FROM ".$database.".oPunch ".
				 			"WHERE CardNo=".$r['cardno']." ".
				 			"AND Type=3 AND removed=0 ".
				 			"ORDER BY Time";
							$logsql = "$logsql \n ---- SQL result_mid --- $sql<br>";
				$resx = mysql_query($sql);
				while ($rowx = mysql_fetch_assoc($resx)) {
				 			$check = gmdate("H:i", $rowx['Time']);
				}

				/* Last radio punched */
				$radio="";
				$sql = "SELECT '$zerotime'+Time As Time, Type ".
				 			"FROM ".$database.".oPunch ".
				 			"WHERE CardNo=".$r['cardno']." ".
				 			"AND Type>=31 AND removed=0 ".
				 			"ORDER BY Time";
							$logsql = "$logsql \n ---- SQL result_mid --- $sql<br>";
				$resx = mysql_query($sql);
				while ($rowx = mysql_fetch_assoc($resx)) {
				 			$radio = sprintf("%03s: ",$rowx['Type']).gmdate("H:i:s", $rowx['Time']);
				}

				/* Finish punched */
				$finish="";
				$sql = "SELECT '$zerotime'+Time As Time ".
				 			"FROM ".$database.".oPunch ".
				 			"WHERE CardNo=".$r['cardno']." ".
				 			"AND Type>3 AND Type<31 AND removed=0 ".
				 			"ORDER BY Time";
							$logsql = "$logsql \n ---- SQL result_mid --- $sql<br>";
				$resx = mysql_query($sql);
				while ($rowx = mysql_fetch_assoc($resx)) {
				 			$finish = gmdate("H:i:s", $rowx['Time']);
				}

				$rrow = array();
				$rrow['check'] = $check;
				if (strpos($r['name'], ",")>0) {
					$name = explode(",", $r['name']);
					$rrow['name'] = $name[1]." ".$name[0];
				} else {
					$rrow['name'] = $r['name'];
				}
				$rrow['team'] = $r['team'];
				$rrow['radio'] = $radio;
				$rrow['finish'] = $finish;

				// Only show time if status=OK
				if (getStatusString($r['status'])=="OK") {
					$rrow['time'] = gmdate("H:i:s", $r['time']);
				} else {
					$rrow['time'] = "";
				}

				$status = "";
				if (getStatusString($r['status'])!="OK") { $status=getStatusString($r['status']); }
				$rrow['status'] = $status;

				$rows[] = $rrow;
			}
			$results = $rows;

			$output = formatJSON2($results, true);

	}
	elseif (($act == "start") && ($cmp != "") && ($cls != "")) {


		/* get results for class in competition*/
		$results = array();
	    $sql = "SELECT max(leg) FROM mopTeamMember tm, mopTeam t WHERE tm.cid = '$cmp' AND t.cid = '$cmp' AND tm.id = t.id AND t.cls = $cls";
    	$res = mysql_query($sql);
    	$r = mysql_fetch_array($res);
	    $numlegs = $r[0];

		$ord=0;
    	if ($numlegs >= 1) {
			//Multiple legs, relay etc.
      		$sql = "SELECT t.id AS id, t.name AS team, t.st % 864000 AS starttime ".
                   "FROM mopTeam t ".
             	   "WHERE t.cls = '$cls' ".
                   "AND t.cid = '$cmp' ".
                   "ORDER BY t.id ASC";

			$logsql = "$logsql \n ---- SQL multiple --- $sql";
			$results = mysql_query($sql);

			/* populate with runners */
			/* place, name, team, time, after, tottime, totstat */
			/* ["No.","Navn","Klub","Tid","&nbsp;","Samlet","&nbsp;"] */
			/* ["Pl.","Tur", "Navn" "Klub (hold)" "Start tid"]*/

			$head = true;
		    $rows = array();
			while($r = mysql_fetch_assoc($results)) {
				if ($head == true) {
	    			$rrow = array();

        			$rrow[] = 'startnumber';
        			$rrow[] = 'team';
        			$rrow[] = 'time';

					$head = false;
				}

				/* team details */
    			$rrow = array();

    			$rrow['startnumber'] = ''; /*$r['id']; */
    			$rrow['team'] = $r['team'];

    			$t = $r['starttime']/10;
    			if ($t >= 86400)
    				$t -= 86400;
		        $rrow['starttime'] = sprintf("%d:%02d:%02d", $t/3600, ($t/60)%60, $t%60);

				$rows[] = $rrow;

				/* get runners */
      			$rrow = array();

          		$sql = "SELECT tm.leg, cmp.name AS name ".
	                   "FROM mopTeam t, mopTeamMember tm, mopCompetitor cmp ".
                 	   "WHERE t.id = tm.id AND tm.rid = cmp.id ".
                       "AND tm.cid = '$cmp' AND cmp.cid = '$cmp' ".
                       "AND t.id='".$r['id']."' ".
                       "ORDER BY tm.leg";

				$logsql = "$logsql \n ---- SQL --- $sql";
				$res = mysql_query($sql);
				$tottime = 0;
				while ($rr = mysql_fetch_array($res)) {

	    			$rrow['startnumber'] = '';
	    			$rrow['team'] = $rr['leg'].". ".$rr['name'];
	    			$rrow['starttime'] = '';

					$rows[] = $rrow;
				}
			}

			$results  = $rows;
	    }
	    else {
			if (is_null($numlegs)) {

				// Read from the MySQL database
				// Get the name of the db with details and the zerotime
				$sql = "SELECT cmp.name AS name, oev.name as oevname, oev.nameId AS nameId, oev.zerotime AS zerotime ".
					"FROM mopCompetition cmp, oEvent oev ".
					"WHERE cmp.cid = '$cmp' ".
					"AND cmp.name=oev.name ";
					$logsql = "$logsql \n ---- SQL result_mid --- $sql";

				$res = mysql_query($sql);
				$results = $res;

				if ($row = mysql_fetch_assoc($results)) {
							$database=$row['nameId'];
							$zerotime=$row['zerotime'];
						} else {
							$database="missing";
							$zerotime="missing";
						}

				// Stop if db with details not is found
				if ($database=="missing") {
					die("Fejl");
				}


		        //No teams;
		        $msg ="no teams";
        		$sql = "SELECT cmp.id AS id, cmp.name AS name, org.name AS team, cmp.st % 864000 AS starttime ".
            		   "FROM mopCompetitor cmp LEFT JOIN mopOrganization AS org ON cmp.org = org.id AND cmp.cid = org.cid ".
                   	   "WHERE cmp.cls = '$cls' ".
                       "AND cmp.cid = '$cmp' ".
                       "ORDER BY cmp.st % 864000, name ";
				$logsql = "$logsql \n ---- SQL --- $sql";
				$results = mysql_query($sql);

				$head = true;
			    $rows = array();
				while($r = mysql_fetch_assoc($results)) {
					if ($head == true) {
		    			$rrow = array();

	        			$rrow[] = 'startnumber';
	        			$rrow[] = 'name';
	        			$rrow[] = 'team';
	        			$rrow[] = 'starttime';

						$head = false;
					}

					/* team details */
	    			$rrow = array();

						// Bib/brystnummer
						$bib="";
						$sql = "SELECT Bib As Bib ".
						 			"FROM ".$database.".oRunner ".
						 			"WHERE Id=".$r['id']." ";
									$logsql = "$logsql \n ---- SQL --- $sql<br>";
									//die($logsql);
						$resx = mysql_query($sql);
						While ($rowx = mysql_fetch_assoc($resx)) {
										 			$bib = $rowx['Bib'];
						}
	    			$rrow['startnumber'] = $bib; /* $r['id']; */
	    			$rrow['name'] = $r['name'];
	    			$rrow['team'] = $r['team'];

	    			$t = $r['starttime']/10;
	    			if ($t >= 86400)
	    				$t -= 86400;
			        $rrow['starttime'] = sprintf("%d:%02d:%02d", $t/3600, ($t/60)%60, $t%60);

					$rows[] = $rrow;
				}

				$results = $rows;
			}

		}
		$output = formatJSON2($results, true);
	}

	if ($debug!="1")
	{
		header('Content-type: application/json');
	}
	else {
		echo "cmp=$cmp cls=$cls leg=$leg ord=$ord radio=$radio\n";
		echo "msg=$msg\n";
		echo "$logsql\n";
	}
	echo $output;

?>
