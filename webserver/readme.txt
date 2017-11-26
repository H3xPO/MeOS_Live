******************************************************************************************************
* Henrik Poulsen, Malthe Poulsen
* FIF Hillerød Orientering
*
* 11.2017
******************************************************************************************************

Installation af lokal webserver (Apache) og MySQL database:

  1.  Download og installer XAMPP, det skal være en version hvor PHP er < version 7.0, da PHP fra version 7.0 anvender MySQLi_ og ikke MySQL_.
      En del af de funktioner der anvendes ligger i ..\meos_mop\ og disse er skrevet af udvikleren af MeOS.

      En fungerende XAMPP kan hentes fra https://www.apachefriends.org/xampp-files/5.6.32/xampp-win32-5.6.32-0-VC11-installer.exe

  2.  Apache webserveren opsættes til at bruge ..\GitHub\udvikling\version_xxx eller ..\GitHub\produktion\version_xxx som root dictory

      Der skal ændres i config filen httpd.conf for Apache i denne sektion. Hvor du skal ændre begge stier til der hvor dine filer er placeret
      OBS: Husk at genstarte din Apache server efter du har ændret i .conf filen.

      # DocumentRoot: The directory out of which you will serve your
      # documents. By default, all requests are taken from this directory, but
      # symbolic links and aliases may be used to point to other locations.
      #
      DocumentRoot "C:/xampp/htdocs"
      <Directory "C:/xampp/htdocs">

  3.  Test forbindelse til MySQL ved at starte MeOS og bruge funktionen 'Databaseforbindelse...'

            NB: Brug IP adressen på din PC og ikke 'localhost' eller '127.0.0.1'

      Hvis du vil oprette en bruger med navnet 'meos', der kan logge på MySQL uden password med fulde rettigheder, kan du udføre disse komandore på din MySQL server:
            CREATE USER 'meos';
            GRANT ALL PRIVILEGES ON *.* TO 'meos';

      Når du kan forbinde til databasen, kan du åben et løb og vælge at overføre dette til serveren.
      Husk at tilrette ''..\meos_mop\config_da.php' med de oplysninger som din MySQL kræver for at kunne logge ind og oprette en database.

      Samtidig med at løbet overføres til serveren oprettes databasen 'meosmain' samt tilhørende databaser.

      For at få dannet de tabeller som programmerne der viser startlister og resultatlister bruger, skal der køres en fil der danner disse tabeller.
      Filen ligger i '..\meos_mop' og hedder setup.php eller _setup.php.

      Når denne fil er kort korrekt, vil der været oprettet nogle tebeller med navnet 'mop%' i databasen 'meosmain'.

      Herefter kan der køres autofunktioner med Resultater online fra MeOS.

  4.  Flytning til produktion:
      1. Filer fra ..\GitHub\MeOS_Live\produktion\version_xxx\ kopieres til din wWebserver
      2. Tilret filen webserver\meos_mop\config_da.php således at følgende oplysninger passer til den MySQL server du bruger:
            // MySQL server configuration
            define("MYSQL_HOSTNAME", "");       [IP adresse eller DNS navn på MySQL server]
            define("MYSQL_DBNAME", "MeOSMain"); [Navn på MeOS tabellen på MySQL server (skal normalt ikke rettes)]
            define("MYSQL_USERNAME", "");       [Brugernavn på den bruger der logger ind på MySQL server]
            define("MYSQL_PASSWORD", "");       [Password for brugeren der logger ind på MySQL serveren, kan evt. være blank]
