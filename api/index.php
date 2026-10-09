<?php

// Pastikan direktori temporary storage tersedia di serverless Vercel
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/storage/app/public',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Salin database sqlite awal ke /tmp jika belum ada di /tmp
$tmpDb = '/tmp/database.sqlite';
$sourceDb = __DIR__ . '/../database/database.sqlite';

if (!file_exists($tmpDb) && file_exists($sourceDb)) {
    copy($sourceDb, $tmpDb);
} elseif (!file_exists($tmpDb)) {
    touch($tmpDb);
}

// Pastikan DB_DATABASE mengarah ke /tmp/database.sqlite jika menggunakan SQLite
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=' . $tmpDb);
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = $tmpDb;

// Teruskan request ke Laravel public/index.php
require __DIR__ . '/../public/index.php';
