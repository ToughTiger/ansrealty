@echo off
echo Backing up old homepage...
ren "resources\views\homepage.blade.php" "homepage-old.blade.php"

echo.
echo Old homepage backed up as: homepage-old.blade.php
echo.
echo Now create the new homepage with your custom design
echo.
pause
