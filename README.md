# MeOS Live

Dette er en samling af forskellige løsninger til visning af resultater m.v.
fra orienteringsløb afviklet med løbsprogrammet MeOS.

Ideen er at disse resultater vises på en større skærm på stævnepladsen, således
at startlister og resultatformidling bliver elektronisk.

#### Gældende for version 2.00 og senere - opdateret 01.07.2019
Udviklingen er version 2.00 er påbegyndt. Den største ændring er skiftet fra PHP til ASP.NET, samtidig med at der ikke længere læses direkte fra MeOS løbsdatabasen. Istedet benyttes den indbyggede 'Informationsserver' i MeOS, der via MeOS REST API giver adgang til løbsdata.

#### Gældende for versioner tidligere end 2.00
Overordnet er løsningen baseret på PHP sider placeret på en webserver. Disse sider henter
data i MeOS løbsdatabasen (MySQL) og præsenterer dem i en browser.

#### Løsningen indeholder følgende features i seneste version
1.  Visning af information fra filen /info/index.html
    <br>Kan f.eks. bruges til at fremhæve oplysninger fra instruktionen
2.  Rullende startlister
    <br>Skærmen opdeles i 2 kolonner, der kan vise forskellige klasse startlister
    <br>Individuel: Startnummer, Navn, Klub, Start tid
    <br>Stafet:     Holdnummer, Hold navn, Klub, Start tid
                    <br>1. Løber 1
                    <br>2. Løber 2    
3.  Rullende resultater
    <br>Skærmen opdeles i 2 kolonner, der kan vise forskellige klasse resultater
    <br>Individuel: Placering, Navn, Klub, Tid, Efter
    <br>Stafet:     Placering, Hold navn, Klub, Samlet tid, Tid efter
                    <br>1. Løber 1, Tid for løber
                    <br>2. Løber 2, Tid for løber                
4.  Stafet, formelding
    <br>Viser løbere der passerer en given radiopost
5.  Divisionsmatch - oversigt
    <br>Skærmen opdeles i 2-3 kolonner hvor der i hver kolonne vises det samlede matchresultat
6.  Divisionsmatch - resultat
    <br>Skærmen viser alle resultater fra een match
	
Specielt for individuelle resultater vises følgende i kolonnen 'Placering':
1.  <img src="https://github.com/P0lle/MeOS_Live/blob/master/udvikling/version_110/check.png" width="16">		Når løberen har stemplet CHECK (såfremt denne CHECK er en radiopost)
2.  <img src="https://github.com/P0lle/MeOS_Live/blob/master/udvikling/version_110/finish.png" width="16">		Når løberen har stemplet MÅL (såfremt denne enhed er en radiopost)
		<br>Der beregnes en foreløbig tid baseret på tid for målstempling-starttid
	
## PRODUKTION/VERSION_110
Baseret på afvikling fra XAMPP

Beskrivelse af webserver/MySQL setup i /webserver/readme.txt

Ny INDEX.PHP lavet

## ToDo


## PRODUKTION/VERSION_100
Første version i GitHub, porteret fra ZIP og Dropbox deling.

Baseret på afvikling på USBWebserver

#### Følgende features findes:
1.  Visning af information fra filen /info/index.html
    <br>Kan f.eks. bruges til at fremhæve oplysninger fra instruktionen
2.  Rullende startlister
    <br>Skærmen opdeles i 2 kolonner, der kan vise forskellige klasse startlister
3.  Rullende resultater
    <br>Skærmen opdeles i 2 kolonner, der kan vise forskellige klasse resultater
4.  Stafet, formelding
    <br>Viser løbere der passerer en given radiopost
5.  Divisionsmatch - oversigt
    <br>Skærmen opdeles i 2-3 kolonner hvor der i hver kolonne vises det samlede matchresultat
6.  Divisionsmatch - resultat
    <br>Skærmen viser alle resultater fra een match
