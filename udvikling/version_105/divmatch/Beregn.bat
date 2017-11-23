@echo off
rem c:
rem cd \temp\divisionsturnering

:start
cls
ECHO.
ECHO Beregner %time%

:: denne linje vil lave en uafhængig kopi af xml-filen til brug for divisionsmatch
copy xml\resultat.xml xml\matchresultat.xml >nul

:: hver match beregner og udskriver egen html
"%ProgramFiles(x86)%\Anders Klinting\Divisionsmatchberegning\Divisionsmatch.exe" -d "match1.divi" -c "xml\matchresultat.xml" -e "html\match1.htm" -f www
ECHO:
"%ProgramFiles(x86)%\Anders Klinting\Divisionsmatchberegning\Divisionsmatch.exe" -d "match2.divi" -c "xml\matchresultat.xml" -e "html\match2.htm" -f www
ECHO:
"%ProgramFiles(x86)%\Anders Klinting\Divisionsmatchberegning\Divisionsmatch.exe" -d "match3.divi" -c "xml\matchresultat.xml" -e "html\match3.htm" -f www

ECHO.
ECHO kopierer til overview
:: vi kopierer html og css til hhv overview og results
copy html\match*.htm html\overview\*.*
copy html\overview.css* html\overview\divi.css

ECHO.
ECHO kopierer til resultat
copy html\match*.htm html\resultat\*.*
copy html\resultat.css* html\resultat\divi.css


rem venter 300 sekunder
set t=300
ECHO.
ECHO %time% Venter %t% sekunder ...
ping 1.1.1.1 -n 1 -w %t%000 >nul

:: start over
goto start


:end
pause

