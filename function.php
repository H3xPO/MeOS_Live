<?php
  //Omregner sekunder til H:M:S format
  function timeformat($timeint){
    return gmdate("H:i:s", $timeint);
  }
?>
