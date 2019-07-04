@ECHO OFF
ECHO:
setlocal
for /f "usebackq tokens=2 delims=:" %%f in (`ipconfig ^| findstr /c:"IPv4 Address"`) do set ip0=%%f
for /f "tokens=* delims= " %%a in ("%ip0%") do set ip=%%a
set thisFolder=%~dp0
set webFolder=%thisFolder%udvikling\version_200
call set webFolder=%%webFolder:IISExpress=Web%%
cd "%thisFolder%"IISExpress
start iisexpress.exe /config:"%thisFolder%IISExpress\AppServer\applicationhost.config"
timeout 1
start http://%ip%:8181/
endlocal
