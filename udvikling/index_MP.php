<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Live</title>
  </head>

  <?php
    //Kald funktions fil
    include "function_MP.php";

    //Udregning af måltid (Oscar B.)

    //Nultid i Meos
    $zero = 54000;
    //Start tid for løber
    $start = 67500-$zero;
    //Radio stempling på mål for løber
    $finish = 16164;
    //Løbstid for løber i sek
    $timeint = timeintberegn($finish, $start);
   ?>

  <body>
    <h1>Liveresultater</h1>  
    <?php echo "<br>Din tid er ". timeformat($timeint); ?>
  </body>
</html>
