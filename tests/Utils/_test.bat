call ../config.bat

for %%f in (test.*.php) do %php% -q "%%f" > "output\%%f.html"

IF NOT "%diff%"=="" ( start "" %diff% output ref )
