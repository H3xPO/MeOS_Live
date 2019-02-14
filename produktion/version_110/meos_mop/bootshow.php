<?php

	// Test with: http://localhost/meos_mop/bootshow.php?action=result&cmp=1&cls=1&debug=1
	// Test with: http://localhost/meos_mop/bootshow.php?action=start&cmp=1&cls=1&debug=1

	//include_once('functions.php');
	include_once('config_da.php');

	session_start();

	$PHP_SELF = $_SERVER['PHP_SELF'];

	$link = ConnectToDB();

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
	$results = NULL;
	if (($act == "") || ($act=="comp")) {
		/* get competitions */
		$sql = "SELECT name, date, cid FROM mopCompetition ORDER BY date DESC";
		$logsql = "$logsql <br> ---- SQL 1 --- $sql";
		$res = $link->query($sql);

		$output = formatJSON($res);
	}
	elseif (($act == "class") && ($cmp != "")) {
		/* get classes in competition */
		$sql = "SELECT name, id FROM mopClass WHERE cid = '$cmp' ORDER BY ord";
		$logsql = "$logsql <br> ---- SQL 2 --- $sql";
		$res = $link->query($sql);

		$output = formatJSON($res);
	}
	elseif (($act == "classname") && ($cmp != "") && ($cls != "")) {
		/* get classes in competition */
		$sql = "SELECT name, id FROM mopClass WHERE cid = '$cmp' AND id='$cls'";
		$logsql = "$logsql <br> ---- SQL 3 --- $sql";
		$res = $link->query($sql);

		$output = formatJSON($res);
	}
	elseif (($act == "leg") && ($cmp != "") && ($cls != "")) {
		/* get legs in the class */
		$sql = "SELECT DISTINCT leg FROM mopTeamMember tm, mopTeam t ".
		       " WHERE tm.cid = '$cmp' AND t.cid = '$cmp' AND tm.id = t.id AND t.cls = $cls".
		       " ORDER BY 1 ASC";
		$logsql = "$logsql <br> ---- SQL 4 --- $sql";
		$res = $link->query($sql);

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
		$logsql = "$logsql <br> ---- SQL 5 --- $sql";
		$res = $link->query($sql);

		$output = formatJSON($res);
		//$output = $sql;
	}
	elseif (($act == "result") && ($cmp != "") && ($cls != "")) {
		/* Get results for class in competition*/
		$results = array();
		$sql = "SELECT max(leg) as cnt FROM mopTeamMember tm, mopTeam t WHERE tm.cid = '$cmp' AND t.cid = '$cmp' AND tm.id = t.id AND t.cls = $cls";
		$logsql = "$logsql <br> ---- SQL 6 --- $sql";
		$res = $link->query($sql);
        $r = $res->fetch_assoc();
        if (is_null($r['cnt'])) {
            $numlegs = NULL;
        } else {
            $numlegs = $r['cnt'];
        }

		// Get the name of the db with details and the zerotime
		$sql = 	"SELECT cmp.name AS name, oev.name as oevname, oev.nameId AS nameId, oev.zerotime AS zerotime ".
				"FROM mopCompetition cmp, oEvent oev ".
				"WHERE cmp.cid = '$cmp' ".
				"AND cmp.date=oev.date ".
				"AND cmp.name=LEFT(oev.name,CHAR_LENGTH(cmp.name)) ";
		$logsql = "$logsql <br> ---- SQL 7 --- $sql";

		$res = $link->query($sql);
        $row = $res->fetch_assoc();
        if (is_null($row['nameId'])) {
            $database="missing";
            $zerotime="0";
        } else {
            $database=$row['nameId'];
            $zerotime=$row['zerotime'];
        }

		// Stop if db with details not is found
		//if ($database=="missing") {
		//	die("Fejl - detaljer ikke fundet ".$logsql);
		//}

		$ord=0;
		if ($numlegs > 1) {
			// RESULTAT, STAFET

			//Multiple legs, relay etc.
			/*
			for ($k = 1; $k <= $numlegs; $k++) {
	        	$sql = "SELECT max(ord) FROM mopTeamMember tm, mopTeam t WHERE t.cls = '$cls' AND tm.leg=$k AND ".
	            	   "tm.cid = '$cmp' AND t.cid = '$cmp' AND tm.id = t.id";

				$res = $link->query($sql);
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

						$sql = "SELECT t.id AS id, cmp.name AS name, t.name AS team, t.org AS org, cmp.rt AS time, cmp.stat AS status, ".
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
						$sql = "SELECT team.id as id, cmp.name AS name, team.name AS team, team.org AS org, radio.rt AS time, 1 AS status, ".
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

					$logsql = "$logsql <br> ---- SQL 8 team $l --- $sql";
                    // echo $logsql ;
					$res = $link->query($sql);
					$resx = calculateResult($res, $link, $database);

					if (count($usedteams)==0) {
						# first set of teams in order
						$results = $resx;
                        for($k = 0; $k<count($resx); $k++) {
                            $r = $resx[$k];
                            // debug print_r($r);
                            $usedteams[] = $r['id'];
						}
                        //foreach($resx as $r){
						//	$usedteams[] = $r['id'];
						//}
					} else {
						$plstart = count($usedteams);
						//foreach($resx as $r){
                        for($k = 0; $k<count($resx); $k++) {
                            $r = $resx[$k];
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
							$rrow[] = 'club';
							$rrow[] = 'time';
							$rrow[] = 'after';
							$rrow[] = 'tottime';
							$rrow[] = 'totafter';

							$head = false;
						}

						/* team details */
						$rrow = array();

						$rrow['place'] = $r['place'];
						$rrow['club'] = $r['team'];
						$rrow['time'] = getmopTeam($link, $r['id'], $cmp); // Klub navn
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

						$logsql = "$logsql <br> ---- SQL 9 runners --- $sql";
						$res = $link->query($sql);
						$tottime = 0;
						$tempstat = 0;
						while ($rr = $res->fetch_assoc()) {

		        			$rrow['place'] = '';
		        			$rrow['team'] = $rr['leg'].". ".$rr['name'];

							$t = $rr['time']/10;
							if ($t > 0) {
			        			$rrow['time'] = sprintf("%d:%02d:%02d", $t/3600, ($t/60)%60, $t%60);
			        			$tottime  = $tottime + $t;
			        		}
							else {
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
				// RESULTAT, INDV.

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

										 if ($database != "missing") {
											 // HP 11.11.2018: Ændret til at bruge oRunner således at alle vises på resultatlisten
											 $sql = "SELECT cmp.Id AS id, cmp.Name AS name, org.name AS team, (cmp.FinishTime-cmp.StartTime)*10 AS time, cmp.status AS status, cmp.status AS SortStatus, 1 As SortIdx ".
														   "FROM ".$database.".oRunner cmp LEFT JOIN ".$database.".oClub org ON cmp.Club = org.Id ".
														   "WHERE cmp.Class = '$cls' ".
														   "AND cmp.status=1 AND cmp.Removed=0 ".
															 "UNION ".
											 			 	 "SELECT cmp.Id AS id, cmp.Name AS name, org.name AS team, if(cmp.FinishTime-cmp.StartTime<0, 0, (cmp.FinishTime-cmp.StartTime)*10) AS time, cmp.status As Status, if(cmp.FinishTime>0, 1, cmp.status) AS SortStatus, if(cmp.FinishTime>0, 1, 9) As SortIdx ".
														   "FROM ".$database.".oRunner cmp LEFT JOIN ".$database.".oClub org ON cmp.Club = org.Id ".
														   "WHERE cmp.Class = '$cls' ".
														   "AND cmp.status=0 AND cmp.Removed=0 ".
															 "UNION ".
															 "SELECT cmp.Id AS id, cmp.Name AS name, org.name AS team, (cmp.FinishTime-cmp.StartTime)*10 AS time, cmp.status AS status, cmp.status As SortStatus, 2 As SortIdx ".
															 "FROM ".$database.".oRunner AS cmp LEFT JOIN ".$database.".oClub AS org ON cmp.Club = org.Id ".
															 "WHERE cmp.Class = '$cls' ".
															 "AND cmp.status>1 AND cmp.Removed=0 ".
															 "ORDER BY SortIdx, SortStatus desc, Time asc";
										 }
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
					$logsql = "$logsql <br> ---- SQL 10 --- $sql";
					$res = $link->query($sql);
					$results = calculateResult($res, $link, $database);
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

					$logsql = "$logsql <br> ---- SQL 11 --- $sql";
					$res = $link->query($sql);
					$results = calculateResult($res, $link, $database);
				}
			}
		}
		$output = formatJSON2($results, true);
	}
	// elseif (($act == "result_mid") && ($cmp != "") && ($cls != "")) {
  //
	// 		// Test with: http://localhost/meos_mop/bootshow.php?action=result_mid&cmp=1&cls=1&debug=1
  //
	// 		// Get the name of the db with details and the zerotime
	// 		$sql = "SELECT cmp.name AS name, oev.name as oevname, oev.nameId AS nameId, oev.zerotime AS zerotime ".
	// 			"FROM mopCompetition cmp, oEvent oev ".
	// 			"WHERE cmp.cid = '$cmp' ".
	// 			"AND cmp.name=oev.name ";
	// 			$logsql = "$logsql <br> ---- SQL result_mid --- $sql";
  //
	// 		$res = $link->query($sql);
	// 		$results = $res;
  //
	// 		if ($row = mysql_fetch_assoc($results)) {
	// 					$database=$row['nameId'];
	// 					$zerotime=$row['zerotime'];
	// 				} else {
	// 					$database="missing";
	// 					$zerotime="missing";
	// 				}
  //
	// 		// Stop if db with details not is found
	// 		if ($database=="missing") {
	// 			die("Fejl");
	// 		}
  //
  // 		// Select the the runners
	// 		$sql = "SELECT oRu.Id As id, oRu.Name As name, oCl.Name as team, (oRu.FinishTime-oRu.StartTime) as time, '$zerotime'+oRu.FinishTime As lastseen, oRu.Status as status, oRu.CardNo AS cardno ".
	// 			"FROM ".$database.".oRunner AS oRu LEFT JOIN ".$database.".oClub AS oCl ON oRu.Club=oCl.Id ".
	// 			"WHERE Class='$cls' ".
	// 			"AND oRu.removed=0 ".
	// 			"ORDER BY status, time ";
	// 			$logsql = "$logsql <br> ---- SQL result_mid --- $sql";
  //
	// 		$res = $link->query($sql);
	// 	 	$results = $res;
  //
	// 		$head = true;
	// 		$rows = array();
	// 		while($r = mysql_fetch_assoc($results)) {
	// 			if ($head == true) {
	// 					$rrow = array();
  //
	// 						$rrow[] = 'check';
	// 						$rrow[] = 'name';
	// 						$rrow[] = 'team';
	// 						//$rrow[] = 'radio';
	// 						$rrow[] = 'finish';
	// 						$rrow[] = 'time';
	// 						$rrow[] = 'status';
  //
	// 						$head = false;
	// 			}
  //
	// 			/* Runner details */
  //
	// 			/* Have the runner punched CHECK */
	// 			$check="";
	// 			$sql = "SELECT '$zerotime'+Time As Time ".
	// 			 			"FROM ".$database.".oPunch ".
	// 			 			"WHERE CardNo=".$r['cardno']." ".
	// 			 			"AND Type=3 AND removed=0 ".
	// 			 			"ORDER BY Time";
	// 						$logsql = "$logsql <br> ---- SQL result_mid --- $sql<br>";
	// 			$resx = $link->query($sql);
	// 			while ($rowx = mysql_fetch_assoc($resx)) {
	// 			 			$check = gmdate("H:i", $rowx['Time']);
	// 			}
  //
	// 			/* Last radio punched */
	// 			$radio="";
	// 			$sql = "SELECT '$zerotime'+Time As Time, Type ".
	// 			 			"FROM ".$database.".oPunch ".
	// 			 			"WHERE CardNo=".$r['cardno']." ".
	// 			 			"AND Type>=31 AND removed=0 ".
	// 			 			"ORDER BY Time";
	// 						$logsql = "$logsql <br> ---- SQL result_mid --- $sql<br>";
	// 			$resx = $link->query($sql);
	// 			while ($rowx = mysql_fetch_assoc($resx)) {
	// 			 			$radio = sprintf("%03s: ",$rowx['Type']).gmdate("H:i:s", $rowx['Time']);
	// 			}
  //
	// 			/* Finish punched */
	// 			$finish="";
	// 			$sql = "SELECT '$zerotime'+Time As Time ".
	// 			 			"FROM ".$database.".oPunch ".
	// 			 			"WHERE CardNo=".$r['cardno']." ".
	// 			 			"AND Type>3 AND Type<31 AND removed=0 ".
	// 			 			"ORDER BY Time";
	// 						$logsql = "$logsql <br> ---- SQL result_mid --- $sql<br>";
	// 			$resx = $link->query($sql);
	// 			while ($rowx = mysql_fetch_assoc($resx)) {
	// 			 			$finish = gmdate("H:i:s", $rowx['Time']);
	// 			}
  //
	// 			$rrow = array();
	// 			$rrow['check'] = $check;
	// 			if (strpos($r['name'], ",")>0) {
	// 				$name = explode(",", $r['name']);
	// 				$rrow['name'] = $name[1]." ".$name[0];
	// 			} else {
	// 				$rrow['name'] = $r['name'];
	// 			}
	// 			$rrow['team'] = $r['team'];
	// 			$rrow['radio'] = $radio;
	// 			$rrow['finish'] = $finish;
  //
	// 			// Only show time if status=OK
	// 			if (getStatusString($r['status'])=="OK") {
	// 				$rrow['time'] = gmdate("H:i:s", $r['time']);
	// 			} else {
	// 				$rrow['time'] = "";
	// 			}
  //
	// 			$status = "";
	// 			if (getStatusString($r['status'])!="OK") { $status=getStatusString($r['status']); }
	// 			$rrow['status'] = $status;
  //
	// 			$rows[] = $rrow;
	// 		}
	// 		$results = $rows;
  //
	// 		$output = formatJSON2($results, true);
  //
	// }
	elseif (($act == "start") && ($cmp != "") && ($cls != "")) {
		// *** STARTLISTE

		/* Check number of legs in competition */
		$results = array();
		$sql = "SELECT max(leg) as cnt FROM mopTeamMember tm, mopTeam t WHERE tm.cid = '$cmp' AND t.cid = '$cmp' AND tm.id = t.id AND t.cls = $cls";
		$res = $link->query($sql);
		$r = $res->fetch_assoc();
		$numlegs = $r['cnt'];


		// Get the name of the db with details and the zerotime
		$sql = 	"SELECT cmp.name AS name, oev.name as oevname, oev.nameId AS nameId, oev.zerotime AS zerotime ".
				"FROM mopCompetition cmp, oEvent oev ".
				"WHERE cmp.cid = '$cmp' ".
				"AND cmp.date  = oev.date ".
				"AND cmp.name=LEFT(oev.name,CHAR_LENGTH(cmp.name)) ";

		$logsql = "$logsql <br> ---- SQL 12 --- $sql";

		$res = $link->query($sql);
		$results = $res;

		if ($row = $res->fetch_assoc()) {
			$database=$row['nameId'];
			$zerotime=$row['zerotime'];
		} else {
			$database="missing";
			$zerotime="0";
		}

		// Stop if db with details not is found
        //    if ($database=="missing") {
        //        die("Fejl - detaljer ikke fundet ".$logsql);
        //    }

		$ord=0;
		if ($numlegs >= 1) {
			// STARTLISTE, REALY

			//Multiple legs, relay etc.
			$msg="teams";
			$sql = 	"SELECT t.id AS id, t.name AS team, t.st % 864000 AS starttime, t.org as org ".
             	"FROM mopTeam t ".
         	   	"WHERE t.cls = '$cls' ".
             	"AND t.cid = '$cmp' ".
             	"ORDER BY t.name ASC";
			$logsql = "$logsql <br> ---- SQL 13 multiple --- $sql";
			$results = $link->query($sql);

			/* populate with runners */
			/* place, name, team, time, after, tottime, totstat */
			/* ["No.","Navn","Klub","Tid","&nbsp;","Samlet","&nbsp;"] */
			/* ["Pl.","Tur", "Navn" "Klub (hold)" "Start tid"]*/

			$head = true;
			$rows = array();
			while($r = $results->fetch_assoc()) {
				if ($head == true) {
	    			$rrow = array();

					$rrow[] = 'startnumber';
					$rrow[] = 'team';
					$rrow[] = 'club';
					$rrow[] = 'starttime';

					$head = false;
				}

				/* team details */
				$rrow = array();

				$rrow['startnumber'] = getBibTeam($link, $r['id'], $database);
				$rrow['club'] = $r['team'];
				$rrow['team'] = getClub($link, $r['org'], $database);
				$t = $r['starttime']/10;
				if ($t >= 86400)
					$t -= 86400;
				$rrow['starttime'] = sprintf("%d:%02d:%02d", $t/3600, ($t/60)%60, $t%60);

				$rows[] = $rrow;

				/* get runners */
				$rrow = array();
				$sql = 	"SELECT tm.leg, cmp.name AS name, tm.id As id ".
								"FROM (mopTeam t join mopTeamMember tm on t.id = tm.id) left join mopCompetitor cmp on tm.rid = cmp.id ".
								"WHERE tm.cid = '$cmp' ".
								"AND t.id='".$r['id']."' ".
								"ORDER BY tm.leg";
				$logsql = "$logsql <br> ---- SQL 14 --- $sql";

				$res = $link->query($sql);

				$tottime = 0;
				while ($rr = $res->fetch_assoc()) {
					$rrow = array();

					$rrow['startnumber'] = '';
					$n = $rr['name'];
					if (is_null($n))
					{
						$n = '--';
					}
					$rrow['team'] = "*  ".$rr['leg'].". ".$n;
					$rrow['club'] = '';
					$rrow['starttime'] = '';

					$rows[] = $rrow;

                    $logsql = "$logsql $n.";
				}
			}
			$results  = $rows;
		}
		else {
			if (is_null($numlegs)) {
				//No teams;
				$msg ="no teams";
				$sql = "SELECT cmp.id AS id, cmp.name AS name, org.name AS team, cmp.st % 864000 AS starttime ".
					   "FROM mopCompetitor cmp LEFT JOIN mopOrganization AS org ON cmp.org = org.id AND cmp.cid = org.cid ".
					   "WHERE cmp.cls = '$cls' ".
					   "AND cmp.cid = '$cmp' ".
					   "ORDER BY cmp.st % 864000, name ";
				$logsql = "$logsql <br> ---- SQL 15 --- $sql";
				$results = $link->query($sql);

				$head = true;
				$rows = array();
				while($r = $results->fetch_assoc()) {
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
					$rrow['startnumber'] = getBib($link, $r['id'], $database);
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

		if ($results != NULL)
		{
			foreach($results as $r) {
				echo implode(" ", $r)."\n";
			}
		}
		echo "--------------------\n";
	}
	echo $output;

// Finder klubnavn
function getmopTeam($link, $id, $cmp) {
	$org = "";
	$sql = 	"SELECT Org As Org ".
					"FROM mopTeam ".
					" WHERE cid = '$cmp' ".
					" AND Id= '$id' ";
	$res = $link->query($sql);
	While ($row = $res->fetch_assoc()) {
        $org = $row['Org'];
	}

	$name = "";
	$sql = 	"SELECT Name As Name ".
					"FROM mopOrganization ".
					" WHERE cid = '$cmp' ".
					" AND Id= '$org' ";
					//echo "<br>SQL: $sql";
	$res = $link->query($sql);
	While ($row = $res->fetch_assoc()) {
		$name = $row['Name'];
	}
	return $name;
}

function getBib($link, $id, $database) {
	// Bib/brystnummer
	$bib="";
    if ($database != "missing") {
        $sql = 	"SELECT Bib As Bib ".
                        "FROM ".$database.".oRunner ".
                        "WHERE Id= '$id' ";
        $resx = $link->query($sql);
        While ($rowx = $resx->fetch_assoc()) {
            $bib = $rowx['Bib'];
        }
    }
	return $bib;
}

function getBibTeam($link, $id, $database) {
	// Bib/brystnummer
	$bib="";
    if ($database != "missing") {
        $sql = 	"SELECT Bib As Bib ".
                        "FROM ".$database.".oTeam ".
                        "WHERE Id= '$id' ";
        $resx = $link->query($sql);
        While ($rowx = $resx->fetch_assoc()) {
            $bib = $rowx['Bib'];
        }
    }
	return $bib;
}

function getClub($link, $org, $database) {
    // Klub
    $club="";
    if ($database != "missing") {
        $sql = "SELECT Name As Name ".
                    "FROM ".$database.".oClub ".
                    "WHERE Id= '$org' ";
        $resx = $link->query($sql);
        While ($rowx = $resx->fetch_assoc()) {
            $club = $rowx['Name'];
        }
    }
	return $club;
}

function getCardNo($link, $id, $database) {
	// CardNo/SI Nummer
	$cardno="";
    if ($database != "missing") {
        $sql = 	"SELECT CardNo As CardNo ".
                        "FROM ".$database.".oRunner ".
                        "WHERE Id= '$id' ";
        $resx = $link->query($sql);
        While ($rowx = $resx->fetch_assoc()) {
            $cardno = $rowx['CardNo'];
        }
    }
	return $cardno;
}

function getPunch($link, $cardno, $database) {
    // Punch/Sidste stempling
    $punch="";
    if ($database != "missing") {
        $sql = "SELECT Type As Type ".
                    "FROM ".$database.".oPunch ".
                    "WHERE CardNo= '$cardno' ".
										"ORDER BY Modified ";
        $resx = $link->query($sql);
        While ($rowx = $resx->fetch_assoc()) {
            $punch = $rowx['Type'];
        }
    }
	return $punch;
}

/** format mys_sql result as json format. */

function formatJSON($sqldata) {

  if($sqldata instanceof ArrayIterator) {
    $rows = $sqldata;
  }
  else {
    $rows = array();
    while($r = $sqldata->fetch_assoc()) {
      $cells = array();
      foreach($r as $c) {
        $cells[] = $c;
      }

      $rows[] = $cells;
    }
  }
  return json_encode($rows);
}

function formatJSON2($sqldata, $head) {
    global $lang;

    $rows = array();
    foreach($sqldata as $r) {
      if ($head){
        $cells = array();
        foreach($r as $key => $cell) {
			if ($key != 'id')
          		$cells[] = $lang[$key];
        }
        $head = false;
		$rows[] = $cells;
      }
      $cells = array();
      foreach($r as $key => $c) {
		if ($key != 'id')
	        $cells[] = $c;
      }

      $rows[] = $cells;
    }

  return json_encode($rows);
}



function getArg($param) {
	if (isset($_GET[$param])) {
		return $_GET[$param];
	}

	return '';
}

// ------------------ functions --------------------

/** Connect to to MySQL */
function ConnectToDB() {
  $link = @new mysqli(MYSQL_HOSTNAME, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DBNAME);


  if (!$link) {
    die('Not connected : ' . $link->connect_error);
  }

  return $link;
}

function query($link, $sql) {
 $result = $link->query($sql);
 if ($result === TRUE)
   return $result;
 else
   die('Invalid query: ' . $link->error);

 return $result;
}

function getStatusString($status) {
  switch($status) {
    case 0:
      return "--"; //Unknown, running?
    case 1:
      return "OK";
    case 20:
      return "DNS"; // Did not start;
    case 21:
      return "CANCEL"; // Cancelled entry;
    case 3:
      return "MP"; // Missing punch
    case 4:
      return "DNF"; //Did not finish
    case 5:
      return "DQ"; // Disqualified
    case 6:
      return "OT"; // Overtime
    case 99:
      return "NP"; //Not participating;
  }
}

function calculateResult($res, $link, $database) {

  $out = array();
  $place = 0;
  $count = 0;
  $lastTime = -1;
  $bestTime = -1;
  $lastTeam = -1;
  $totalResult = array();
  $hasTotal = false;

	$changePlace = 0;

  while ($r = $res->fetch_assoc()) {

    if ($lastTeam == $r['id']) {
      $out[$count]['name'] .= " / " . $r['name'];
      continue;
    }
    else
      $lastTeam = $r['id'];

    $count++;
    $t = $r['time']/10;
    if ($bestTime == -1)
      $bestTime = $t;
    if ($lastTime != $t) {
			$place = $count;
			$place = $place-$changePlace;
      $lastTime = $t;
    }

    $row = array();
    if ($r['status'] == 1) {
      $row['place'] = $place.".";
      $row['name'] = $r['name'];
      $row['team'] = $r['team'];
      if ($t > 0)
        $row['time'] = sprintf("%d:%02d:%02d", $t/3600, ($t/60)%60, $t%60);
      else
        $row['time'] = "OK"; // No timing
			// Tid efter
      $after = $t - $bestTime;
      if ($after > 3600)
        $row['after'] = sprintf("+%d:%02d:%02d", $after/3600, ($after/60)%60, $after%60);
      elseif ($after > 0)
        $row['after'] = sprintf("+%d:%02d", ($after/60)%60, $after%60);
      else
        $row['after'] = "";
    }
    else {
      $row['place'] = "-";
      $row['name'] = $r['name'];
      $row['team'] = $r['team'];
      $row['time'] = getStatusString($r['status']);
      $row['after'] = "";

			// Her vises status - check, remote mål (tid er baseret på radio aflæst måltid)
			$lastpunch = 0;
			$lastpunch = getPunch($link, getCardNo($link, $r['id'], $database), $database);
			if ($lastpunch>0) {
				if ($lastpunch==3) {
					$row['place'] = "<img src='/check.png' width='16'>";
					//$row['place'] = "CHK";
				}
				elseif ($lastpunch<31) {
					$changePlace++;
					$row['place'] = "<img src='/finish.png' width='16'>";
					//$row['place'] = "MÅL";
  				$row['time'] = sprintf("%d:%02d:%02d", $t/3600, ($t/60)%60, $t%60);
					// Tid efter
		      $after = $t - $bestTime;
		      if ($after > 3600)
		        $row['after'] = sprintf("+%d:%02d:%02d", $after/3600, ($after/60)%60, $after%60);
		      elseif ($after > 0)
		        $row['after'] = sprintf("+%d:%02d", ($after/60)%60, $after%60);
		      else
		        $row['after'] = "";
				}
			}
    }

    if (isset($r['tottime'])) {
      $hasTotal = true;
      if (($r['totstat'] == 1) && ($r['status'] == 1)) {
        $tt = $r['tottime']/10;
        if ($tt > 0)
          $row['tottime'] = sprintf("%d:%02d:%02d", $tt/3600, ($tt/60)%60, $tt%60);
        else
          $row['tottime'] = "--"; // No timing
      }
      else {
        $row['tottime'] = getStatusString($r['totstat']);
      }
      if (($r['totstat'] == 1) && ($r['status'] == 1))
        $totalResult[$count] = ($r['totstat']-1) * 10000000 + $r['tottime'];
      else
        $totalResult[$count] = 10000000 * 100 * ($r['totstat'] == 0 ? 27 : $r['totstat']);
    }
		$row['id'] = $lastTeam;

		// Viser ikke linier hvor plac='-' og time='--'
		if (($row['place']=="-") && ($row['time']=="--"))
			;
		else
    	$out[$count] = $row;
  }

  if ($hasTotal) {
    array_multisort($totalResult, $out);
    $place = 0;
    $lastTime = -1;
    $bestTime = -1;

    for($k = 0; $k<$count; $k++) {
      if ($totalResult[$k] < 10000000) {
        $t = $totalResult[$k];
        if ($bestTime == -1)
          $bestTime = $t;
        if ($lastTime != $t) {
          $place = $k+1;
          $lastTime = $t;
        }
        if ($out[$k]['place'] > 0)
          $out[$k]['time'].=" (".substr($out[$k]['place'], 0, -1).")";

        $out[$k]['place'] = $place.".";
        $after = ($t - $bestTime)/10;
        if ($after > 3600)
          $out[$k]['totafter'] = sprintf("+%d:%02d:%02d", $after/3600, ($after/60)%60, $after%60);
        elseif ($after > 0)
          $out[$k]['totafter'] = sprintf("+%d:%02d", ($after/60)%60, $after%60);
        else
          $out[$k]['totafter'] = '';
      }
      else {
        $out[$k]['place'] = '';
        $out[$k]['totafter'] = '';
      }
    }
  }

  return $out;
}

?>
