******************************************************************************************************
* Henrik Poulsen, Malthe Poulsen
* FIF Hillerød Orientering
*
* 11.2017
******************************************************************************************************

Installation af lokal webserver (Apache) og MySQL database:

  1.  Download og installer XAMPP, det skal være en version hvor PHP er < version 7.0, da PHP fra version 7.0 anvender MySQLi og ikke MySQL.
      En del af de funktioner der anvendes ligger i ..\meos_mop\ og disse er skrevet af udvikleren af MeOS

      En fungerende XAMPP kan hentes fra https://www.apachefriends.org/xampp-files/5.6.32/xampp-win32-5.6.32-0-VC11-installer.exe


  2.  Apache webserveren opsættes til at bruge ..\GitHub som root dictory

      I dit GitHub skal du have følgende:
      ..\GitHub\MeOS_Live
                  Indeholder GitHub repository
      ..\GitHub\MeOS_Live_Config\config_da.php
                  Indeholder konfigurationen til MySQL connection, således at denne ikke uploades til GitHub

  3.  Test forbindelse til MySQL ved at starte MeOS og bruge funktionen 'Databaseforbindelse...'

            NB: Brug IP adressen på din PC og ikke 'localhost' eller '127.0.0.1'

      Hvis du vil oprette en bruger med navnet 'meos', der kan logge på MySQL uden password med fulde rettigheder, kan du udføre disse komandore på din MySQL server:
            CREATE USER 'meos';
            GRANT ALL PRIVILEGES ON *.* TO 'meos';

      Når du kan forbinde til databasen, kan du åben et løb og vælge at overføre dette til serveren.

      Samtidig med at løbet overføres til serveren oprettes databasen 'meosmain' samt tilhørende databaser.

      For at få dannet de tabeller som programmerne der viser startlister og resultatlister bruger, skal der køres en autofunktion (Resultater online) fra MeOS.
      Når denne funktion har overført oplysninger til serveren, vil der været oprettet nogle tebeller med navnet 'mop%' i databasen 'meosmain'. 

  4.  Flytning til produktion:
      1. Filer fra ..\GitHub\MeOS_Live\produktion\version xx.yy\ kopieres til Webserver
      2. Kopier ..\GitHub\MeOS_Live_Config\config_da.php til webserver\meos_mop
      3. Tilret filen webserver\meos_mop\config_da.php således at følgende oplysninger passer til den MySQL server du bruger:
            // MySQL server configuration
            define("MYSQL_HOSTNAME", "");       [IP adresse eller DNS navn på MySQL server]
            define("MYSQL_DBNAME", "MeOSMain"); [Navn på MeOS tabellen på MySQL server (skal normalt ikke rettes)]
            define("MYSQL_USERNAME", "");       [Brugernavn på den bruger der logger ind på MySQL server]
            define("MYSQL_PASSWORD", "");       [Password for brugeren der logger ind på MySQL serveren, kan evt. være blank]
      4. Ret filen webserver\meos_mop\functions.php, således at nedenstående linie remarkes:
            // Denne linie fjernes i produktion
            set_include_path($_SERVER["DOCUMENT_ROOT"].'/MeOS_Live_Config');
