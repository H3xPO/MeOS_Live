@ECHO OFF
ECHO:
setlocal
set port=8181
netsh http add urlacl url=http://*:%port%/ user=everyone >nul
netsh http add urlacl url=http://*:%port%/ user=alle >nul
ECHO Din firewall tillader nu trafik gennem port %port%
endlocal
pause >nul