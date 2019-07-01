set webFolder=%CD%\udvikling\version_200
call set webFolder=%%webFolder:IISExpress=Web%%
cd IISExpress
start iisexpress.exe /path:"%webFolder%" /port:8181
timeout 1
start http://localhost:8181/Default.aspx