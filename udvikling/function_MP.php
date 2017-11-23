<?php
  //Omregner sekunder til H:M:S format
  function timeformat($timeint){
    return gmdate("H:i:s", $timeint);
  }

  //Beregmer $timeint (løbstid i sekunder)
  function timeintberegn($finish, $start){
     return $finish - $start;
  }
?>
