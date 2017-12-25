# MeOS-Live

Dette er en samling af forskellige løsninger til visning af resultater m.v.
fra orienteringsløb afviklet med løbsprogrammet MeOS.

Ideen er at disse resultater vises på en større skærm på stævnepladsen, således
at startlister og resultatformidling bliver elektronisk.

Overordnet er løsningen baseret på PHP sider placeret på en webserver. Disse sider henter
data i MeOS løbsdatabasen (MySQL) og præsenterer dem i en browser.

#### Løsningen indeholder følgende features i seneste version
1.  Visning af information fra filen /info/index.html
    Kan f.eks. bruges til at fremhæve oplysninger fra instruktionen
2.  Rullende startlister
    Skærmen opdeles i 2 kolonner, der kan vise forskellige klasse startlister
    Viser: Startnummer, Navn, Klub, Start tid
3.  Rullende resultater - foreløbige
    Resultat baseret på målstempling, der kontrolleres altså ikke for fejlklip. Forudsætter at der er en radiopost på målposten.
    Viser: CHK (tid),  Navn, Klub, Sidste radio eller Mål, Tid, Status
4.  Rullende resultater
    Skærmen opdeles i 2 kolonner, der kan vise forskellige klasse resultater
    Viser: Placering, Navn, Klub, Tid, Efter
5.  Stafet, formelding
    Viser løbere der passerer en given radiopost
6.  Divisionsmatch - oversigt
    Skærmen opdeles i 2-3 kolonner hvor der i hver kolonne vises det samlede matchresultat
7.  Divisionsmatch - resultat
    Skærmen viser alle resultater fra een match


## PRODUKTION/VERSION_105
Baseret på afvikling fra XAMPP

Beskrivelse af webserver/MySQL setup i /webserver/readme.txt

Ny INDEX.PHP lavet

#### Ændrede/tilføjede features
2.  Rullende startlister
    Disse indeholder nu også et evt. startnummer
3.  Rullende resultater - foreløbige
    Liste, der viser et foreløbigt resultat baseret på målstempling, der kontrolleres altså ikke for fejlklip.
    Listen tænkes brugt når der er lang mellem mål og stævnecenter med aflæsning, det forudsættes at der er en radiopost på målposten.
4.  Rullende resultater
    (tidligere 3.)


## PRODUKTION/VERSION_100
Første version i GitHub, porteret fra ZIP og Dropbox deling.

Baseret på afvikling på USBWebserver

#### Følgende features findes:
1.  Visning af information fra filen /info/index.html
    Kan f.eks. bruges til at fremhæve oplysninger fra instruktionen
2.  Rullende startlister
    Skærmen opdeles i 2 kolonner, der kan vise forskellige klasse startlister
3.  Rullende resultater
    Skærmen opdeles i 2 kolonner, der kan vise forskellige klasse resultater
4.  Stafet, formelding
    Viser løbere der passerer en given radiopost
5.  Divisionsmatch - oversigt
    Skærmen opdeles i 2-3 kolonner hvor der i hver kolonne vises det samlede matchresultat
6.  Divisionsmatch - resultat
    Skærmen viser alle resultater fra een match
