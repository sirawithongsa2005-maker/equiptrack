<?php
declare(strict_types=1);

if (defined('EQUIPTRACK_BOOTSTRAPPED')) {
    return;
}
define('EQUIPTRACK_BOOTSTRAPPED', true);
define('ROOT_PATH', dirname(__DIR__));

$app = require ROOT_PATH . '/config/app.php';
date_default_timezone_set($app['timezone'] ?? 'Asia/Bangkok');

function app_config(?string $key = null) {
    static $cfg = null;
    if ($cfg === null) $cfg = require ROOT_PATH . '/config/app.php';
    return $key === null ? $cfg : ($cfg[$key] ?? null);
}

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $cfg = require ROOT_PATH . '/config/database.php';
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $cfg['host'], $cfg['port'], $cfg['name'], $cfg['charset']);
    try {
        $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        $pdo->exec("SET time_zone = '+07:00'");
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        $safe = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        echo '<!doctype html><html lang="th"><meta charset="utf-8"><title>EquipTrack - Database</title><style>body{font-family:system-ui,Tahoma,sans-serif;background:#f5f7fb;color:#172033;margin:0;padding:40px}.box{max-width:760px;margin:auto;background:white;border:1px solid #e6eaf0;border-radius:20px;padding:28px;box-shadow:0 20px 60px #14213d12}code{background:#f1f5f9;padding:3px 7px;border-radius:6px}.err{color:#b42318;background:#fff1f0;border:1px solid #ffd2cf;padding:12px;border-radius:10px}</style><div class="box"><h1>เชื่อมต่อฐานข้อมูลไม่ได้</h1><p>เปิด Apache + MySQL ใน XAMPP แล้ว Import <code>database/equiptrack_db.sql</code> ผ่าน phpMyAdmin</p><p>ค่ามาตรฐาน: ฐานข้อมูล <code>equiptrack_db</code>, ผู้ใช้ <code>root</code>, รหัสผ่านว่าง</p><div class="err">'.$safe.'</div></div></html>';
        exit;
    }
}

final class DbSessionHandler implements SessionHandlerInterface {
    public function __construct(private PDO $pdo) {}
    public function open(string $path, string $name): bool { return true; }
    public function close(): bool { return true; }
    public function read(string $id): string|false {
        try {
            $st = $this->pdo->prepare('SELECT data FROM app_sessions WHERE id=? AND expires_at > NOW() LIMIT 1');
            $st->execute([$id]);
            $v = $st->fetchColumn();
            return $v === false ? '' : (string)$v;
        } catch (Throwable $e) { return ''; }
    }
    public function write(string $id, string $data): bool {
        try {
            $ttl = max(1440, (int)ini_get('session.gc_maxlifetime'));
            $st = $this->pdo->prepare('INSERT INTO app_sessions (id,data,ip_address,user_agent,expires_at) VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE data=VALUES(data),ip_address=VALUES(ip_address),user_agent=VALUES(user_agent),expires_at=VALUES(expires_at),updated_at=CURRENT_TIMESTAMP');
            $expiry = date('Y-m-d H:i:s', time() + $ttl);
            return $st->execute([$id, $data, substr($_SERVER['REMOTE_ADDR'] ?? '',0,45), substr($_SERVER['HTTP_USER_AGENT'] ?? '',0,255), $expiry]);
        } catch (Throwable $e) { return false; }
    }
    public function destroy(string $id): bool {
        try { $st=$this->pdo->prepare('DELETE FROM app_sessions WHERE id=?'); return $st->execute([$id]); }
        catch (Throwable $e) { return false; }
    }
    public function gc(int $max_lifetime): int|false {
        try { return $this->pdo->exec('DELETE FROM app_sessions WHERE expires_at <= NOW()'); }
        catch (Throwable $e) { return false; }
    }
}

// Native PHP sessions are stored in MySQL, avoiding macOS/Windows temp-folder permission problems.
try {
    db()->exec("CREATE TABLE IF NOT EXISTS app_sessions (id varchar(128) NOT NULL, data longblob NOT NULL, ip_address varchar(45) NOT NULL DEFAULT '', user_agent varchar(255) NOT NULL DEFAULT '', expires_at datetime NOT NULL, updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (id), KEY idx_session_expiry (expires_at)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
} catch (Throwable $e) { /* The SQL installer will surface database permission issues. */ }
$handler = new DbSessionHandler(db());
session_set_save_handler($handler, true);
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ini_set('session.cookie_secure', '1');
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once ROOT_PATH . '/includes/functions.php';
