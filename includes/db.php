<?php
/**
 * Database connection (PDO / MySQL).
 *
 * Update the constants below to match your MySQL setup. If you're
 * using a hosting control panel (cPanel, Plesk, etc.) these come from
 * whatever database you create there.
 */
define('DB_HOST', getenv('GRD_DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('GRD_DB_NAME') ?: 'grd_hing');
define('DB_USER', getenv('GRD_DB_USER') ?: 'root');
define('DB_PASS', getenv('GRD_DB_PASS') ?: '');

function grd_db() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            // Fail loudly but without leaking connection details to visitors.
            http_response_code(500);
            die('Database connection failed. Check includes/db.php credentials and that MySQL is running.');
        }
    }
    return $pdo;
}
