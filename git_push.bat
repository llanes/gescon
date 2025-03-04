@echo off
cd /d "%~dp0"
echo ============================================
echo    Subir Cambios al Repositorio Git
echo ============================================
echo.
echo ¿Deseas subir los cambios al repositorio? (S/N)
set /p respuesta=

if /i "%respuesta%"=="S" (
    echo.
    echo Subiendo cambios...
    echo.
    git add .
    git commit -m "Guardado automático: %date% %time%"
    git push origin main
    echo.
    echo Cambios subidos exitosamente.
) else (
    echo.
    echo No se subieron los cambios.
)
echo.
pause 