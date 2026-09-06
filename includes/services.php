<?php
declare(strict_types=1);

function normalize_device_ids(array $ids): array {
    $out=[];
    foreach($ids as $id){ $id=trim((string)$id); if($id!=='' && (int)scalar('SELECT COUNT(*) FROM tbl_devices WHERE d_id=?',[$id])===1)$out[$id]=true; }
    $ids=array_keys($out); sort($ids,SORT_STRING); return $ids;
}

function create_student_request(int $memberId,array $deviceIds,string $reason): void {
    $deviceIds=normalize_device_ids($deviceIds);$reason=trim($reason);
    if($memberId<1||!$deviceIds||$reason==='')throw new RuntimeException('กรุณาเลือกครุภัณฑ์และระบุวัตถุประสงค์');
    if((int)scalar('SELECT COUNT(*) FROM tbl_member WHERE m_id=? AND ref_pid NOT IN (1,3)',[$memberId])!==1)throw new RuntimeException('บัญชีผู้ยืมไม่ถูกต้อง');
    db_transaction(function(PDO $pdo)use($memberId,$deviceIds,$reason){
        foreach($deviceIds as $deviceId){
            $st=$pdo->prepare('SELECT no,ref_t_id,ref_s_id,d_id FROM tbl_devices WHERE d_id=? AND ref_s_id=1 FOR UPDATE');$st->execute([$deviceId]);$d=$st->fetch();
            if(!$d)throw new RuntimeException('มีครุภัณฑ์บางรายการไม่พร้อมให้ยืมแล้ว กรุณาเลือกใหม่');
            $st=$pdo->prepare("SELECT COUNT(*) FROM tbl_devices_service WHERE ref_d_id=? AND ser_status='pending'");$st->execute([$deviceId]);if((int)$st->fetchColumn()>0)throw new RuntimeException('มีครุภัณฑ์บางรายการถูกส่งคำขอไปแล้ว');
            $st=$pdo->prepare("INSERT INTO tbl_devices_service(ref_t_id,ref_d_id,ref_m_id,ser_reason,ser_status,ser_request_date) VALUES(?,?,?,?,'pending',NOW())");$st->execute([$d['ref_t_id'],$deviceId,$memberId,$reason]);
        }
    });
}

function approve_request_tx(int $serId,int $staffId,string $staffName): void {
    db_transaction(function(PDO $pdo)use($serId,$staffId,$staffName){
        $st=$pdo->prepare('SELECT * FROM tbl_devices_service WHERE ser_id=? FOR UPDATE');$st->execute([$serId]);$r=$st->fetch();if(!$r||$r['ser_status']!=='pending')throw new RuntimeException('คำขอนี้ถูกดำเนินการไปแล้ว');
        $st=$pdo->prepare('SELECT d_id,ref_s_id FROM tbl_devices WHERE d_id=? FOR UPDATE');$st->execute([$r['ref_d_id']]);$d=$st->fetch();if(!$d||(int)$d['ref_s_id']!==1)throw new RuntimeException('ครุภัณฑ์ไม่พร้อมให้ยืม');
        $st=$pdo->prepare("UPDATE tbl_devices_service SET ser_status='approved',ser_date_lend=CURDATE(),ser_approved_at=NOW(),ser_staff_id_lend=?,ser_staff_name_lend=? WHERE ser_id=? AND ser_status='pending'");$st->execute([$staffId,$staffName,$serId]);if($st->rowCount()!==1)throw new RuntimeException('อนุมัติคำขอไม่สำเร็จ');
        $st=$pdo->prepare('UPDATE tbl_devices SET ref_s_id=2 WHERE d_id=? AND ref_s_id=1');$st->execute([$r['ref_d_id']]);if($st->rowCount()!==1)throw new RuntimeException('เปลี่ยนสถานะครุภัณฑ์ไม่สำเร็จ');
    });
}

function reject_request_tx(int $serId,int $staffId,string $staffName): void {
    db_transaction(function(PDO $pdo)use($serId,$staffId,$staffName){
        $st=$pdo->prepare('SELECT ser_status FROM tbl_devices_service WHERE ser_id=? FOR UPDATE');$st->execute([$serId]);$r=$st->fetch();if(!$r||$r['ser_status']!=='pending')throw new RuntimeException('คำขอนี้ถูกดำเนินการไปแล้ว');
        $st=$pdo->prepare("UPDATE tbl_devices_service SET ser_status='rejected',ser_rejected_at=NOW(),ser_staff_id_lend=?,ser_staff_name_lend=? WHERE ser_id=? AND ser_status='pending'");$st->execute([$staffId,$staffName,$serId]);if($st->rowCount()!==1)throw new RuntimeException('ปฏิเสธคำขอไม่สำเร็จ');
    });
}

function cancel_student_request_tx(int $serId,int $memberId): void {
    db_transaction(function(PDO $pdo)use($serId,$memberId){
        $st=$pdo->prepare('SELECT ref_m_id,ser_status FROM tbl_devices_service WHERE ser_id=? FOR UPDATE');$st->execute([$serId]);$r=$st->fetch();
        if(!$r||(int)$r['ref_m_id']!==$memberId||$r['ser_status']!=='pending')throw new RuntimeException('ยกเลิกคำขอนี้ไม่ได้');
        $st=$pdo->prepare("UPDATE tbl_devices_service SET ser_status='cancelled' WHERE ser_id=? AND ref_m_id=? AND ser_status='pending'");$st->execute([$serId,$memberId]);if($st->rowCount()!==1)throw new RuntimeException('ยกเลิกคำขอไม่สำเร็จ');
    });
}

function create_direct_loan_tx(array $deviceIds,int $memberId,string $reason,int $staffId,string $staffName): void {
    $deviceIds=normalize_device_ids($deviceIds);$reason=trim($reason);
    if(!$deviceIds||$memberId<1||$reason==='')throw new RuntimeException('กรุณาเลือกผู้ยืม อุปกรณ์ และระบุวัตถุประสงค์');
    if((int)scalar('SELECT COUNT(*) FROM tbl_member WHERE m_id=? AND ref_pid NOT IN (1,3)',[$memberId])!==1)throw new RuntimeException('ผู้ยืมไม่ถูกต้อง');
    db_transaction(function(PDO $pdo)use($deviceIds,$memberId,$reason,$staffId,$staffName){
        foreach($deviceIds as $deviceId){
            $st=$pdo->prepare('SELECT ref_t_id,d_id FROM tbl_devices WHERE d_id=? AND ref_s_id=1 FOR UPDATE');$st->execute([$deviceId]);$d=$st->fetch();if(!$d)throw new RuntimeException('มีครุภัณฑ์บางรายการไม่พร้อมให้ยืม');
            $st=$pdo->prepare("SELECT COUNT(*) FROM tbl_devices_service WHERE ref_d_id=? AND ser_status='pending'");$st->execute([$deviceId]);if((int)$st->fetchColumn()>0)throw new RuntimeException('มีคำขอยืมที่กำลังรออนุมัติสำหรับอุปกรณ์บางรายการ');
            $st=$pdo->prepare('UPDATE tbl_devices SET ref_s_id=2 WHERE d_id=? AND ref_s_id=1');$st->execute([$deviceId]);if($st->rowCount()!==1)throw new RuntimeException('ไม่สามารถล็อกครุภัณฑ์เพื่อยืมได้');
            $st=$pdo->prepare("INSERT INTO tbl_devices_service(ref_t_id,ref_d_id,ref_m_id,ser_reason,ser_status,ser_request_date,ser_approved_at,ser_date_lend,ser_staff_id_lend,ser_staff_name_lend) VALUES(?,?,?,?,'borrowed',NOW(),NOW(),CURDATE(),?,?)");$st->execute([$d['ref_t_id'],$deviceId,$memberId,$reason,$staffId,$staffName]);
        }
    });
}

function return_loan_tx(int $serId,int $returnStatus,int $staffId,string $staffName): void {
    if(!in_array($returnStatus,[1,3],true))throw new RuntimeException('สถานะหลังคืนไม่ถูกต้อง');
    db_transaction(function(PDO $pdo)use($serId,$returnStatus,$staffId,$staffName){
        $st=$pdo->prepare('SELECT * FROM tbl_devices_service WHERE ser_id=? FOR UPDATE');$st->execute([$serId]);$loan=$st->fetch();if(!$loan||!in_array($loan['ser_status'],['approved','borrowed'],true))throw new RuntimeException('รายการนี้รับคืนไม่ได้');
        $st=$pdo->prepare('SELECT d_id FROM tbl_devices WHERE d_id=? FOR UPDATE');$st->execute([$loan['ref_d_id']]);if(!$st->fetch())throw new RuntimeException('ไม่พบครุภัณฑ์');
        $st=$pdo->prepare("UPDATE tbl_devices_service SET ser_date_return=CURDATE(),ser_staff_id_return=?,ser_staff_name_return=?,ser_status='returned' WHERE ser_id=? AND ser_status IN ('approved','borrowed')");$st->execute([$staffId,$staffName,$serId]);if($st->rowCount()!==1)throw new RuntimeException('บันทึกรับคืนไม่สำเร็จ');
        $st=$pdo->prepare('UPDATE tbl_devices SET ref_s_id=? WHERE d_id=?');$st->execute([$returnStatus,$loan['ref_d_id']]);
    });
}
