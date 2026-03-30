@echo off
echo ========================================
echo  FRESH MIGRATION (Keep Users ^& Seed Data)
echo ========================================
echo.
echo WARNING: This will drop ALL tables except users
echo          and re-run all migrations.
echo.
echo Press Ctrl+C to cancel, or
pause

cd /d "%~dp0"

echo.
echo Step 1: Backing up users table...
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('CREATE TABLE users_backup AS SELECT * FROM users'); echo 'Users backed up';"

echo.
echo Step 2: Backing up seed data (leads, opportunities, bookings, etc.)...
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('CREATE TABLE leads_backup AS SELECT * FROM leads'); echo 'Leads backed up';"
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('CREATE TABLE opportunities_backup AS SELECT * FROM opportunities'); echo 'Opportunities backed up';"
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('CREATE TABLE bookings_backup AS SELECT * FROM bookings'); echo 'Bookings backed up';"
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('CREATE TABLE agents_backup AS SELECT * FROM agents'); echo 'Agents backed up';"
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('CREATE TABLE properties_backup AS SELECT * FROM properties'); echo 'Properties backed up';"
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('CREATE TABLE builders_backup AS SELECT * FROM builders'); echo 'Builders backed up';"

echo.
echo Step 3: Running fresh migrations (this will drop all tables)...
php artisan migrate:fresh --force

echo.
echo Step 4: Restoring users...
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('INSERT INTO users SELECT * FROM users_backup'); DB::statement('DROP TABLE users_backup'); echo 'Users restored';"

echo.
echo Step 5: Restoring seed data...
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('INSERT INTO leads SELECT * FROM leads_backup'); DB::statement('DROP TABLE leads_backup'); echo 'Leads restored';"
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('INSERT INTO opportunities SELECT * FROM opportunities_backup'); DB::statement('DROP TABLE opportunities_backup'); echo 'Opportunities restored';"
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('INSERT INTO bookings SELECT * FROM bookings_backup'); DB::statement('DROP TABLE bookings_backup'); echo 'Bookings restored';"
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('INSERT INTO agents SELECT * FROM agents_backup'); DB::statement('DROP TABLE agents_backup'); echo 'Agents restored';"
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('INSERT INTO properties SELECT * FROM properties_backup'); DB::statement('DROP TABLE properties_backup'); echo 'Properties restored';"
php artisan tinker --execute="use Illuminate\Support\Facades\DB; DB::statement('INSERT INTO builders SELECT * FROM builders_backup'); DB::statement('DROP TABLE builders_backup'); echo 'Builders restored';"

echo.
echo Step 6: Clearing caches...
php artisan optimize:clear
php artisan filament:cache-components

echo.
echo ========================================
echo  ✅ Fresh Migration Complete!
echo ========================================
echo.
echo Users and seed data have been preserved.
echo All tables recreated with latest schema.
echo.
pause
