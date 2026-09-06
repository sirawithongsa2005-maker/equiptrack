# EquipTrack — Native PHP + MySQL

เวอร์ชันนี้เขียนใหม่เป็น **PHP ล้วน (Native PHP)** ไม่ใช้ CodeIgniter และไม่ใช้ PHP Framework

## โครงสร้าง
- `config/` ตั้งค่าระบบและฐานข้อมูล
- `includes/` ฟังก์ชันกลาง, PDO, Auth, CSRF, Session, Layout
- `admin/` ผู้ดูแลระบบ
- `staff/` เจ้าหน้าที่ / ครุภัณฑ์ / ยืม–คืน
- `student/` ผู้ยืม
- `reports/` รายงานและ Export CSV
- `assets/` CSS + JavaScript ของ EquipTrack
- `uploads/devices/` รูปครุภัณฑ์
- `uploads/members/` รูปผู้ใช้
- `database/equiptrack_db.sql` ฐานข้อมูลติดตั้งใหม่ไฟล์เดียว

## ติดตั้ง macOS + XAMPP
1. วางโฟลเดอร์ที่ `/Applications/XAMPP/xamppfiles/htdocs/equiptrack`
2. เปิด Apache และ MySQL
3. เข้า `http://localhost/phpmyadmin/`
4. Import `database/equiptrack_db.sql`
5. เปิด `http://localhost/equiptrack/`

หากอัปโหลดรูปไม่ได้บน Mac:
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/equiptrack
sudo chown -R daemon:staff uploads
sudo chmod -R 775 uploads
```

## ติดตั้ง Windows + XAMPP
1. วางโฟลเดอร์ที่ `C:\xampp\htdocs\equiptrack`
2. เปิด Apache และ MySQL
3. Import `database\equiptrack_db.sql` ใน phpMyAdmin
4. เปิด `http://localhost/equiptrack/`

## บัญชีเริ่มต้น
- Admin: `admin` / `admin`
- Staff: `staff` / `staff`
- Student: `student` / `student`

## ตรวจระบบ
เปิด `http://localhost/equiptrack/setup_check.php`

## ฐานข้อมูล
ค่าเริ่มต้นใน `config/database.php`:
- Host: `127.0.0.1`
- Port: `3306`
- Database: `equiptrack_db`
- Username: `root`
- Password: ว่าง

สามารถใช้ Environment Variables: `EQUIPTRACK_DB_HOST`, `EQUIPTRACK_DB_PORT`, `EQUIPTRACK_DB_NAME`, `EQUIPTRACK_DB_USER`, `EQUIPTRACK_DB_PASS`

## ความปลอดภัย/ความเสถียร
- PDO Prepared Statements
- `password_hash()` / `password_verify()`
- CSRF token ทุก POST
- Session เก็บใน MySQL (`app_sessions`) ไม่พึ่ง temp folder ของ OS
- Transaction + `FOR UPDATE` ในกระบวนการยืม–คืน
- จำกัด MIME/ขนาดไฟล์อัปโหลด
- ปิดการรัน PHP ในโฟลเดอร์ uploads
