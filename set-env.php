<?php
// set-env.php - Set the correct .env file based on environment

$railwayEnv = getenv('RAILWAY_ENVIRONMENT');

if ($railwayEnv) {
    // Running on Railway, use .env.railway
    copy(__DIR__ . '/.env.railway', __DIR__ . '/.env');
    echo "Using .env.railway for Railway environment\n";
} else {
    // Running locally, use .env.local
    copy(__DIR__ . '/.env.local', __DIR__ . '/.env');
    echo "Using .env.local for local environment\n";
}
