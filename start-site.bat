@echo off
setlocal
rem Starts the website on this computer using PHP, then opens it in your browser.
rem Double-click this file. Close the window that opens to stop the website.

cd /d "%~dp0"
set "PORT=8081"
set "PHP="

where php >nul 2>nul && set "PHP=php"
if not defined PHP (
    for /d %%D in ("C:\wamp64\bin\php\php8*" "C:\wamp\bin\php\php8*" "C:\xampp\php" "C:\php") do (
        if exist "%%~D\php.exe" set "PHP=%%~D\php.exe"
    )
)

if not defined PHP (
    echo PHP was not found on this computer.
    echo Install WampServer from https://www.wampserver.com/ or PHP from https://windows.php.net/download/
    pause
    exit /b 1
)

echo Starting the Betterment People Solutions website at http://127.0.0.1:%PORT%/
echo Keep this window open while you use the site. Close it to stop the site.
echo.

rem Open the browser a moment after the server starts.
start "" /b powershell -NoProfile -WindowStyle Hidden -Command "Start-Sleep -Seconds 2; Start-Process 'http://127.0.0.1:%PORT%/'"

"%PHP%" -S 127.0.0.1:%PORT% router.php
echo.
echo The website stopped. If it says the port is in use, close any other copy of the site and try again.
pause
