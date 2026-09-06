# EquipTrack — macOS + Windows

ระบบยืม–คืนครุภัณฑ์ด้วย CodeIgniter 3 สำหรับ XAMPP โดยใช้ **โค้ด ZIP ชุดเดียวกันทั้ง macOS และ Windows**

## ตำแหน่งโปรเจกต์

- Windows: `C:\xampp\htdocs\equiptrack`
- macOS: `/Applications/XAMPP/xamppfiles/htdocs/equiptrack`

โค้ดไม่ hard-code ตำแหน่งทั้งสองแบบ แต่ใช้ `FCPATH` และ `DIRECTORY_SEPARATOR` จึงย้ายระหว่าง Mac/Windows ได้โดยไม่แก้ Controller หรือ Model

## ติดตั้ง

1. แตก ZIP เป็นโฟลเดอร์ `equiptrack` ใน `htdocs`
2. Start Apache และ MySQL ใน XAMPP
3. เปิด phpMyAdmin แล้ว Import `database/equiptrack_db.sql`
4. เปิด `http://localhost/equiptrack/`

รายละเอียดแยกตามระบบอยู่ใน `INSTALL_MAC_WINDOWS.txt`

## ฐานข้อมูล

ค่าเริ่มต้น:

- Host: `127.0.0.1`
- Port: `3306`
- User: `root`
- Password: ว่าง
- Database: `equiptrack_db`

สามารถ override ผ่าน `EQUIPTRACK_DB_HOST`, `EQUIPTRACK_DB_PORT`, `EQUIPTRACK_DB_USER`, `EQUIPTRACK_DB_PASS`, `EQUIPTRACK_DB_NAME`

## บัญชีเริ่มต้น

- Admin: `admin` / `admin`
- Staff: `staff` / `staff`
- Student: `student` / `student`

ควรเปลี่ยนรหัสผ่านหลังติดตั้ง

## ความเข้ากันได้ที่ปรับไว้

- base URL ตรวจ host/port/path อัตโนมัติ
- ใช้ `index.php` routing จึงไม่ต้องพึ่ง mod_rewrite
- Session เก็บใน `ci_sessions`
- มี safeguard สร้าง `ci_sessions` หากตารางหาย
- Upload ใช้ absolute path จาก `FCPATH`
- รูปผู้ใช้เก็บใน `uploads`
- รูปครุภัณฑ์เก็บใน `devices`
- ไม่ใช้ path แบบ `C:\...` หรือ `/Applications/...` ในโค้ดระบบ
- ป้องกันการรัน PHP ในโฟลเดอร์อัปโหลด
- รองรับฐานข้อมูล MySQL/MariaDB ของ XAMPP

## ถ้าอัปโหลดรูปไม่ได้บน macOS

ให้ตรวจ owner/permission ของ `uploads` และ `devices`; คำสั่งแนะนำอยู่ใน `INSTALL_MAC_WINDOWS.txt`

## ก่อนนำขึ้น Hosting จริง

- เปลี่ยนรหัสผ่านบัญชีตัวอย่าง
- เปลี่ยน `encryption_key`
- ตั้ง `ENVIRONMENT` เป็น `production`
- ใช้บัญชีฐานข้อมูลเฉพาะระบบแทน root
- จำกัด permission ตาม user ของ Apache/PHP
# equiptrack
