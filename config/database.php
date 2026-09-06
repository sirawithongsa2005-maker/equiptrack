<?php
return [
    'host' => getenv('EQUIPTRACK_DB_HOST') ?: '127.0.0.1',
    'port' => getenv('EQUIPTRACK_DB_PORT') ?: '3306',
    'name' => getenv('EQUIPTRACK_DB_NAME') ?: 'equiptrack_db',
    'user' => getenv('EQUIPTRACK_DB_USER') ?: 'root',
    'pass' => getenv('EQUIPTRACK_DB_PASS') !== false ? getenv('EQUIPTRACK_DB_PASS') : '',
    'charset' => 'utf8mb4',
];
