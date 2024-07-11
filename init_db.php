<?php
$db = new SQLite3('mfa.db');

$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY,
    username TEXT UNIQUE,
    password TEXT,
    secret TEXT
)");

echo "Database initialized.\n";
