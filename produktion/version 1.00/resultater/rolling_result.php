<html>
  <head>
  	<meta http-equiv="Content-Type" content="text/html;charset=UFT-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>FIF Hillerød Orientering - Live</title>
	<link href="css/bootstrap.min.css" rel="stylesheet"/>
	<link href="js/bootstrap.min.js" rel="stylesheet"/>
	<link href="css/ddscrollspy.css" rel="stylesheet"/>
	
	
<!--[if lt IE 9]><script src="./docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

	<script type="text/javascript" src="js/jquery-1.11.0.js"></script>
	<script type="text/javascript" src="js/rolling-scripts.js"></script>

<script src="js/ddscrollspy.js">

/*
* DD ScrollSpy Menu Script (c) Dynamic Drive (www.dynamicdrive.com)
* Last updated: Dec 29th, 12'
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
*/

</script>

<script>
var pixelspersecond = 32; //16
var numberofscrolls = 1;
var dur;
var minreloadinterval = 60000; // millisecs
var topbottomdelay = 10000; //millisecs
var scrollduration; // millisecs
var headerOffset;
<?php
date_default_timezone_set('Europe/Copenhagen');
$now   = new DateTime();
echo "var starttime = new Date('".$now->format('c')."');";
?>
elapsetime = 0;

jQuery(function($){ // on document load

	filldata();


	$('#horizontalmenu').ddscrollSpy({ // initialize first demo
		scrolltopoffset: -50
	})
	
	klokken = document.getElementById("loadtime");
	h = window.innerHeight;
	headerOffset=document.body.offsetHeight - h;
	scrollduration =1000*headerOffset/pixelspersecond;
	dur = (numberofscrolls*(scrollduration + 2*topbottomdelay + 1000 + 500)).toFixed(0);
	if (dur < minreloadinterval)
	{
		dur = minreloadinterval;
	}
	
	setTimeout(setKlokken);
	startAnimation();
})


function setKlokken()
{
	elapsetime += 1000; // one second since last call
	t = ((dur - elapsetime)/1000).toFixed(0);
	if (t < 0) {t=0;}
	klokken.innerHTML = starttime.toLocaleTimeString() + "<br>refresh: " +t;
	setTimeout(setKlokken, 1000);
}

function startAnimation()
{	
	//n = (new Date()).getTime();
	if (elapsetime >= (dur - topbottomdelay))
	{ 
		window.scrollTo(0,0);
		location.reload();
	}
	else
	{
		$('body').delay(topbottomdelay).animate(  {scrollTop: headerOffset}, 
							{
							duration: scrollduration, 
							easing: "linear" ,
							done : function() { $('body').delay(topbottomdelay).animate({scrollTop:0}, {duration:1000, easing: "linear", done : function(){ setTimeout(startAnimation, 500);}}); }
							}
						 );
	}
}


function filldata()
{

<?php
	$action = $_GET['action'];
	$cmp = $_GET['cmp'];
	$cls = $_GET['cls'];
    $radio='finish';
    $leg='';
	$multiple= '';
	if (isset($_GET['multiple']))
	    $multiple = $_GET['multiple'];
    
	foreach ($cls as $selectedCls) {
		echo "loadFinishResult('".$action."', '".$cmp."', '".$selectedCls."', '".$leg."', '".$radio."', '".$multiple."');";
	}
?>


}

</script>
</head>

<body>


<!-- Scroll Spy Menu 1 -->
<span onclick="javascript:location='rolling_setup.htm'" title="SETUP" style="position:fixed;top:0;left:10;z-index:20" id="loadtime">klokken</span>
<ul id="horizontalmenu" class="underlinemenu">
<!--
	<li><a href="#D-10">D-10</a></li>
	<li><a href="#D-12">D-12</a></li>
	<li><a href="#D-14">D-14</a></li>
	<li><a href="#D-16">D-16</a></li>
	<li><a href="#D-20">D-20</a></li>
-->
</ul>


<br style="clear:left" />

<!-- Contents that Scroll Spy Menu 1 is spying on -->

<div id="resultContainer">
<!--
<div id="D-10">
		<h2>D-10</h2>
		<table class='table-striped table-condensed table-bordered' width="100%">
		<thead><tr><th>Pl.</th>
		<th>Navn</th>
		<th>Hold</th>
		<th>Tid</th>
		<th>&nbsp;</th>
		</tr><thead>
		<tbody><tr><td align="right" width="6%">1.</td><td width="32%">Kaia Jørgensen</td><td width="32%">Tisvilde Hegn OK</td><td align="right" width="15%">0:10:33</td><td align="right" width="15%"></td></tr><tr><td align="right" width="6%">2.</td><td width="32%">Sigrid Høyer Staugaard</td><td width="32%">FIF Hillerød Orientering</td><td align="right" width="15%">0:10:49</td><td align="right" width="15%">+0:16</td></tr><tr><td>3.</td><td>Emma Emilsson</td><td>OK Skogsfalken</td><td>0:11:01</td><td>+0:28</td></tr><tr><td>4.</td><td>Johanne Schøier Kovsted</td><td>Tisvilde Hegn OK</td><td>0:11:58</td><td>+1:25</td></tr><tr><td>5.</td><td>Ellen K. Larsen</td><td>Søllerød OK</td><td>0:12:35</td><td>+2:02</td></tr><tr><td>6.</td><td>Lisa Swartz</td><td>OK Orion</td><td>0:13:12</td><td>+2:39</td></tr><tr><td>7.</td><td>Amalie Ertmann</td><td>Allerød OK</td><td>0:13:36</td><td>+3:03</td></tr><tr><td>8.</td><td>Hedda Kärner Grooss</td><td>Allerød OK</td><td>0:14:05</td><td>+3:32</td></tr><tr><td>9.</td><td>Katrine Krogh Madsen</td><td>OK Roskilde</td><td>0:14:06</td><td>+3:33</td></tr><tr><td>10.</td><td>Liva Heydorn Andersen</td><td>OK Roskilde</td><td>0:15:40</td><td>+5:07</td></tr></tbody></table>
</div>		
-->
</div>	
<br>
<br>
</body>
</html>