<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="css/style.css">
    <meta charset="utf-8">
    <title>Live</title>
    <script type="text/javascript" src="js/script.js"></script>
  </head>

  <?php
    //Udregning af måltid (Oscar B.)
    $zero = 54000;
    $start = 67500-$zero;
    $finish = 16164;

    $timeint = ($finish - $start);
    $timereal = gmdate("H:i:s", $timeint);
   ?>

  <body>
    <h1>Liveresultater</h1>
    <?php echo "Din tid er $timereal"; ?>
  </body>
</html>
