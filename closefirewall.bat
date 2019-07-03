@ECHO OFF
ECHO:
setlocal
set port=8181
netsh http delete urlacl url=http://*:%port%/ >nul
ECHO Din firewall blokerer igen port %port%
endlocal
pause >nul
