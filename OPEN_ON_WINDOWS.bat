@echo off
setlocal
chcp 65001 >nul
set "PROJECT_DIR=%~dp0"
for %%I in ("%PROJECT_DIR:~0,-1%") do set "PROJECT_NAME=%%~nxI"
echo =============================================
echo EquipTrack - Windows XAMPP Launcher
echo =============================================
echo Project: %PROJECT_DIR%
echo.
if exist "C:\xampp\xampp-control.exe" (
  echo Opening XAMPP Control Panel...
  start "" "C:\xampp\xampp-control.exe"
) else (
  echo XAMPP Control Panel was not found at C:\xampp\xampp-control.exe
  echo If XAMPP is installed elsewhere, open it manually and start Apache + MySQL.
)
echo.
echo Opening EquipTrack...
start "" "http://localhost/%PROJECT_NAME%/"
exit /b 0
