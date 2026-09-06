<?php

declare(strict_types=1);

function e(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }

function base_path_url(): string {
    static $base = null;
    if ($base !== null) return $base;
    $doc = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
    $root = realpath(ROOT_PATH);
    if ($doc && $root) {
        $docN = rtrim(str_replace('\\','/',$doc), '/');
        $rootN = str_replace('\\','/',$root);
        if (stripos($rootN, $docN) === 0) {
            $rel = substr($rootN, strlen($docN));
            return $base = rtrim('/' . trim($rel, '/'), '/');
        }
    }
    return $base = '/equiptrack';
}

function url(string $path=''): string {
    $base = base_path_url();
    return ($base === '' ? '' : $base) . ($path === '' ? '/' : '/' . ltrim($path, '/'));
}
function asset(string $path): string { return url('assets/' . ltrim($path,'/')); }
function upload_url(string $folder, string $file): string { return url('uploads/'.trim($folder,'/').'/'.rawurlencode(basename($file))); }

function redirect(string $path): never { header('Location: ' . (preg_match('~^https?://~',$path) ? $path : url($path))); exit; }

function fetch_one(string $sql, array $params=[]): ?array { $st=db()->prepare($sql); $st->execute($params); $r=$st->fetch(); return $r ?: null; }
function fetch_all(string $sql, array $params=[]): array { $st=db()->prepare($sql); $st->execute($params); return $st->fetchAll(); }
function execute_sql(string $sql, array $params=[]): bool { $st=db()->prepare($sql); return $st->execute($params); }
function scalar(string $sql, array $params=[]): mixed { $st=db()->prepare($sql); $st->execute($params); return $st->fetchColumn(); }

function csrf_token(): string {
    if (empty($_SESSION['_csrf'])) $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['_csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="_csrf" value="'.e(csrf_token()).'">'; }
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
    $posted = (string)($_POST['_csrf'] ?? '');
    if ($posted === '' || !hash_equals((string)($_SESSION['_csrf'] ?? ''), $posted)) {
        http_response_code(419);
        exit('คำขอหมดอายุหรือไม่ถูกต้อง กรุณาย้อนกลับและลองใหม่');
    }
}

function flash(string $type, string $message, string $title=''): void { $_SESSION['_flash'][] = compact('type','message','title'); }
function pull_flashes(): array { $v=$_SESSION['_flash'] ?? []; unset($_SESSION['_flash']); return is_array($v)?$v:[]; }

function current_user(): ?array {
    static $cache = '__unset__';
    if ($cache !== '__unset__') return is_array($cache) ? $cache : null;
    $id=(int)($_SESSION['user_id'] ?? 0);
    if ($id<1) return $cache=null;
    $cache=fetch_one('SELECT m.*,p.pname FROM tbl_member m JOIN tbl_position p ON p.pid=m.ref_pid WHERE m.m_id=? LIMIT 1',[$id]);
    if (!$cache) { unset($_SESSION['user_id']); return $cache=null; }
    return $cache;
}
function role_id(): int { return (int)(current_user()['ref_pid'] ?? 0); }
function is_admin(): bool { return role_id()===1; }
function is_staff(): bool { return role_id()===3; }
function is_borrower(): bool { $r=role_id(); return $r>0 && !in_array($r,[1,3],true); }
function require_login(): void { if (!current_user()) redirect('auth/login.php'); }
function require_roles(array $roles): void { require_login(); if (!in_array(role_id(),$roles,true)) { http_response_code(403); render_error('ไม่มีสิทธิ์เข้าถึงหน้านี้','บัญชีของคุณไม่มีสิทธิ์ใช้งานเมนูนี้'); } }
function require_staff_or_admin(): void { require_roles([1,3]); }
function require_borrower(): void { require_login(); if(!is_borrower()) { http_response_code(403); render_error('ไม่มีสิทธิ์เข้าถึงหน้านี้','เมนูนี้สำหรับผู้ยืม'); } }
function home_for_role(): string { return is_admin()?'admin/dashboard.php':(is_staff()?'staff/dashboard.php':'student/dashboard.php'); }

function post(string $key, string $default=''): string { return trim((string)($_POST[$key] ?? $default)); }
function int_post(string $key): int { return (int)($_POST[$key] ?? 0); }
function get_int(string $key): int { return max(0,(int)($_GET[$key] ?? 0)); }

function full_name(array $m): string { return trim(($m['m_fname']??'').' '.($m['m_name']??'').' '.($m['m_lname']??'')); }
function text_len(string $value): int { return function_exists('mb_strlen') ? mb_strlen($value,'UTF-8') : strlen($value); }
function first_char(string $value): string { if($value==='')return ''; return function_exists('mb_substr') ? mb_substr($value,0,1,'UTF-8') : substr($value,0,1); }
function user_initial(array $m): string { $name=trim((string)($m['m_name']??'')); return $name!==''?first_char($name):'U'; }

function upload_image(string $field, string $folder, ?string $oldFile=null): ?string {
    if (empty($_FILES[$field]['name'])) return $oldFile;
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('อัปโหลดรูปไม่สำเร็จ กรุณาลองใหม่');
    if ((int)$_FILES[$field]['size'] > (int)app_config('upload_max_bytes')) throw new RuntimeException('รูปมีขนาดใหญ่เกิน 5 MB');
    $finfo=new finfo(FILEINFO_MIME_TYPE); $mime=$finfo->file($_FILES[$field]['tmp_name']);
    $allowed=(array)app_config('allowed_image_mimes');
    if (!isset($allowed[$mime])) throw new RuntimeException('รองรับเฉพาะ JPG, PNG และ WEBP');
    $dir=ROOT_PATH.'/uploads/'.trim($folder,'/');
    if (!is_dir($dir) && !mkdir($dir,0775,true) && !is_dir($dir)) throw new RuntimeException('ไม่สามารถสร้างโฟลเดอร์อัปโหลดได้');
    if (!is_writable($dir)) throw new RuntimeException('โฟลเดอร์ uploads/'.trim($folder,'/').' ไม่มีสิทธิ์เขียนไฟล์');
    $filename=date('YmdHis').'_'.bin2hex(random_bytes(8)).'.'.$allowed[$mime];
    if (!move_uploaded_file($_FILES[$field]['tmp_name'],$dir.'/'.$filename)) throw new RuntimeException('ไม่สามารถบันทึกรูปลงโฟลเดอร์อัปโหลดได้');
    if ($oldFile) {
        $old=basename($oldFile); $oldPath=$dir.'/'.$old;
        if (is_file($oldPath) && $old!==$filename) @unlink($oldPath);
    }
    return $filename;
}

function device_image(array $d): string {
    $f=basename((string)($d['d_img']??''));
    if ($f!=='' && is_file(ROOT_PATH.'/uploads/devices/'.$f)) return upload_url('devices',$f);
    return '';
}
function member_image(array $m): string {
    $f=basename((string)($m['m_img']??''));
    if ($f!=='' && is_file(ROOT_PATH.'/uploads/members/'.$f)) return upload_url('members',$f);
    return '';
}

function status_label(string $status): array {
    return match($status) {
        'pending'=>['รออนุมัติ','warning'], 'approved'=>['อนุมัติแล้ว','primary'], 'borrowed'=>['กำลังยืม','primary'],
        'returned'=>['คืนแล้ว','success'], 'rejected'=>['ปฏิเสธ','danger'], 'cancelled'=>['ยกเลิก','muted'], default=>[$status,'muted']
    };
}
function badge_service(string $status): string { [$t,$c]=status_label($status); return '<span class="badge badge-'.$c.'">'.e($t).'</span>'; }
function thai_date(?string $date, bool $withTime=false): string {
    if(!$date) return '-'; $ts=strtotime($date); if(!$ts) return '-';
    return date($withTime?'d/m/Y H:i':'d/m/Y',$ts);
}

function render_header(string $title, string $active=''): void { require ROOT_PATH.'/includes/header.php'; }
function render_footer(): void { require ROOT_PATH.'/includes/footer.php'; }
function render_error(string $title,string $message): never {
    echo '<!doctype html><html lang="th"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="'.e(asset('css/app.css')).'"><body><div class="standalone"><div class="error-card"><div class="error-code">!</div><h1>'.e($title).'</h1><p>'.e($message).'</p><a class="btn btn-primary" href="'.e(url('')).'">กลับหน้าหลัก</a></div></div></body></html>'; exit;
}

function db_transaction(callable $fn): mixed {
    $pdo=db(); $pdo->beginTransaction();
    try { $v=$fn($pdo); $pdo->commit(); return $v; }
    catch(Throwable $e){ if($pdo->inTransaction())$pdo->rollBack(); throw $e; }
}

function pending_count(): int { return (int)scalar("SELECT COUNT(*) FROM tbl_devices_service WHERE ser_status='pending'"); }
function active_loan_count(): int { return (int)scalar("SELECT COUNT(*) FROM tbl_devices_service WHERE ser_status IN ('approved','borrowed')"); }

function client_ip(): string { return substr($_SERVER['REMOTE_ADDR']??'',0,45); }
