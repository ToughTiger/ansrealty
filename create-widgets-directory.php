<?php
// Create Widgets directory
$widgetsDir = __DIR__ . '/../app/Filament/Widgets';

if (!is_dir($widgetsDir)) {
    mkdir($widgetsDir, 0755, true);
    echo "✅ Widgets directory created: {$widgetsDir}\n";
} else {
    echo "✅ Widgets directory already exists\n";
}

echo "\nReady to create widget files!\n";
