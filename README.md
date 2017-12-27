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

    Individuel: Startnummer, Navn, Klub, Start tid

    Stafet:     Holdnummer, Hold navn, Klub, Start tid

                * 1. Løber 1

                * 2. Løber 2    
3.  Rullende resultater

    Skærmen opdeles i 2 kolonner, der kan vise forskellige klasse resultater

    Individuel: Placering, Navn, Klub, Tid, Efter

    Stafet:     Placering, Hold navn, Klub, Samlet tid, Tid efter

                1. Løber 1, Tid for løber

                2. Løber 2, Tid for løber                
4.  Stafet, formelding

    Viser løbere der passerer en given radiopost
5.  Divisionsmatch - oversigt

    Skærmen opdeles i 2-3 kolonner hvor der i hver kolonne vises det samlede matchresultat
6.  Divisionsmatch - resultat

    Skærmen viser alle resultater fra een match


## PRODUKTION/VERSION_105
Baseret på afvikling fra XAMPP

Beskrivelse af webserver/MySQL setup i /webserver/readme.txt

Ny INDEX.PHP lavet

## ToDo
Eksisterende startliste udvides med følgende:
1.  Placering viser følgende:

    Nummer      = placering efter aflæsning

    Nummer+gul  = midlertidig placering efter mål inden aflæsning

    Sort klat   = løberene har stemplet check

    Tid         = Starttid tildelt eller fra stempling af enhed (radio)
2.  Radio fjernes

3.  Tid skal beregnes fra starttid (målstemplinger uden startid vises ikke)

#### Ændrede/tilføjede features
2.  Rullende startlister (indv. og stafet)

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
