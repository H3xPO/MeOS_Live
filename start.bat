@ECHO OFF
ECHO:
setlocal
for /f "usebackq tokens=2 delims=:" %%f in (`ipconfig ^| findstr /c:"IPv4 Address"`) do set ip0=%%f
set thisFolder=%~dp0
set /p ip=IP adressen for denne pc er %ip0%, angiv denne eller tryk [Enter] for at bruge 'localhost':
IF "%ip%" == "" set ip=localhost
set webFolder=%thisFolder%udvikling\version_200
call set webFolder=%%webFolder:IISExpress=Web%%
cd "%thisFolder%"IISExpress
start iisexpress.exe /config:"%thisFolder%IISExpress\AppServer\applicationhost.config"
timeout 1
start http://%ip%:8181/Default.aspx
endlocal