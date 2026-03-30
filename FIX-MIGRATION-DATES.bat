@echo off
echo Renaming migration files...

cd database\migrations

if exist "2024_01_22_000010_add_recurring_tasks_to_tasks_table.php" (
    ren "2024_01_22_000010_add_recurring_tasks_to_tasks_table.php" "2026_01_23_000010_add_recurring_tasks_to_tasks_table.php"
    echo Renamed recurring tasks migration
)

if exist "2024_01_22_000011_create_task_templates_table.php" (
    ren "2024_01_22_000011_create_task_templates_table.php" "2026_01_23_000011_create_task_templates_table.php"
    echo Renamed task templates migration
)

if exist "2024_01_22_000012_create_communications_table.php" (
    ren "2024_01_22_000012_create_communications_table.php" "2026_01_23_000012_create_communications_table.php"
    echo Renamed communications migration
)

cd ..\..

echo.
echo Done! Now run: FRESH-MIGRATION-WITH-SEED.bat
pause
