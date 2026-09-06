
<?php
require dirname(__DIR__) . '/includes/bootstrap.php';

/* ถ้าล็อกอินอยู่แล้ว ให้ไปหน้าหลัก */
if (current_user()) {
    redirect(home_for_role());
}

$error = '';

/* =========================
   LOGIN PROCESS
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

    $username = trim(post('username'));
    $password = (string)($_POST['password'] ?? '');

    if ($username === '' || $password === '') {

        $error = 'กรุณากรอกชื่อผู้ใช้และรหัสผ่าน';

    } else {

        $user = fetch_one(
            'SELECT m.*, p.pname
             FROM tbl_member m
             JOIN tbl_position p ON p.pid = m.ref_pid
             WHERE m.m_username = ?
             LIMIT 1',
            [$username]
        );

        $valid = false;

        if ($user) {

            $storedPassword = (string)$user['m_password'];

            /* รองรับรหัสผ่าน SHA1 เก่า */
            if (preg_match('/^[a-f0-9]{40}$/i', $storedPassword)) {

                $valid = hash_equals(
                    strtolower($storedPassword),
                    sha1($password)
                );

                /* Login สำเร็จแล้วเปลี่ยนเป็น password_hash */
                if ($valid) {
                    execute_sql(
                        'UPDATE tbl_member
                         SET m_password = ?
                         WHERE m_id = ?',
                        [
                            password_hash($password, PASSWORD_DEFAULT),
                            $user['m_id']
                        ]
                    );
                }

            } else {

                $valid = password_verify(
                    $password,
                    $storedPassword
                );

                if (
                    $valid &&
                    password_needs_rehash(
                        $storedPassword,
                        PASSWORD_DEFAULT
                    )
                ) {

                    execute_sql(
                        'UPDATE tbl_member
                         SET m_password = ?
                         WHERE m_id = ?',
                        [
                            password_hash($password, PASSWORD_DEFAULT),
                            $user['m_id']
                        ]
                    );
                }
            }
        }

        if ($valid) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = (int)$user['m_id'];

            flash(
                'success',
                'เข้าสู่ระบบเรียบร้อย',
                'ยินดีต้อนรับ'
            );

            /* แยกหน้าตามสิทธิ์ */
            if ((int)$user['ref_pid'] === 1) {

                redirect('admin/dashboard.php');

            } elseif ((int)$user['ref_pid'] === 3) {

                redirect('staff/dashboard.php');

            } else {

                redirect('student/dashboard.php');
            }

        } else {

            $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>เข้าสู่ระบบ | EquipTrack</title>

<style>

/* ========================================
   RESET
======================================== */

* {
    box-sizing: border-box;
}

html,
body {
    margin: 0;
    padding: 0;
    min-height: 100%;
}

body {

    min-height: 100vh;

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 24px;

    font-family:
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        "Noto Sans Thai",
        Tahoma,
        Arial,
        sans-serif;

    color: #172033;

    background:
        radial-gradient(
            circle at 15% 15%,
            rgba(37, 99, 235, .14),
            transparent 32%
        ),
        radial-gradient(
            circle at 85% 85%,
            rgba(59, 130, 246, .10),
            transparent 30%
        ),
        #f5f8fc;
}


/* ========================================
   LOGIN CARD
======================================== */

.etx-login-card {

    width: 100%;
    max-width: 450px;

    padding: 38px;

    background: #ffffff;

    border: 1px solid #e5eaf2;
    border-radius: 24px;

    box-shadow:
        0 25px 65px rgba(15, 23, 42, .10),
        0 4px 14px rgba(15, 23, 42, .04);

}


/* ========================================
   BRAND
======================================== */

.etx-brand {

    display: flex;
    align-items: center;

    gap: 12px;

    margin-bottom: 30px;

}

.etx-brand-icon {

    width: 48px;
    height: 48px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 15px;

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #1d4ed8
        );

    color: #fff;

    font-size: 17px;
    font-weight: 800;

    box-shadow:
        0 10px 24px
        rgba(37, 99, 235, .24);

}

.etx-brand-text strong {

    display: block;

    margin: 0;

    color: #0f172a;

    font-size: 21px;
    font-weight: 750;

    line-height: 1.15;

}

.etx-brand-text span {

    display: block;

    margin-top: 3px;

    color: #94a3b8;

    font-size: 11px;
    font-weight: 600;

    letter-spacing: .5px;

}


/* ========================================
   TITLE
======================================== */

.etx-login-header {

    margin-bottom: 27px;

    text-align: left;

}

.etx-login-header h1 {

    margin:
        0
        0
        7px;

    color: #0f172a;

    font-size: 28px;
    font-weight: 750;

    letter-spacing: -.5px;

}

.etx-login-header p {

    margin: 0;

    color: #64748b;

    font-size: 14px;

    line-height: 1.7;

}


/* ========================================
   ALERT
======================================== */

.etx-alert {

    display: flex;
    align-items: flex-start;

    gap: 11px;

    margin-bottom: 20px;

    padding: 13px 14px;

    color: #b42318;

    background: #fff5f4;

    border: 1px solid #fecdca;
    border-radius: 12px;

    font-size: 13px;

    line-height: 1.5;

}

.etx-alert-icon {

    width: 22px;
    height: 22px;

    flex: 0 0 22px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-top: 1px;

    border-radius: 50%;

    color: #fff;
    background: #dc2626;

    font-size: 12px;
    font-weight: 800;

}


/* ========================================
   FORM
======================================== */

.etx-field {

    margin-bottom: 18px;

}

.etx-field label {

    display: block;

    margin-bottom: 7px;

    color: #334155;

    font-size: 13px;
    font-weight: 650;

    text-align: left;

}

.etx-input {

    position: relative;

    display: flex;
    align-items: center;

    width: 100%;
    height: 52px;

    overflow: hidden;

    background: #fff;

    border: 1px solid #dbe2ea;
    border-radius: 12px;

    transition:
        border-color .18s ease,
        box-shadow .18s ease;

}

.etx-input:focus-within {

    border-color: #3b82f6;

    box-shadow:
        0 0 0 4px
        rgba(59, 130, 246, .10);

}

.etx-input-icon {

    width: 48px;

    flex: 0 0 48px;

    text-align: center;

    color: #94a3b8;

    font-size: 16px;

}

.etx-input input {

    width: 100%;
    height: 100%;

    min-width: 0;

    padding: 0 12px 0 0;

    color: #0f172a;

    background: transparent;

    border: 0;
    outline: 0;

    font-family: inherit;
    font-size: 14px;

}

.etx-input input::placeholder {

    color: #a8b2c1;

}


/* ========================================
   PASSWORD BUTTON
======================================== */

.etx-password-button {

    height: 100%;

    flex: 0 0 auto;

    padding: 0 15px;

    color: #64748b;

    background: transparent;

    border: 0;

    font-family: inherit;
    font-size: 12px;
    font-weight: 650;

    cursor: pointer;

    transition:
        color .15s,
        background .15s;

}

.etx-password-button:hover {

    color: #2563eb;
    background: #f8fafc;

}


/* ========================================
   LOGIN BUTTON
======================================== */

.etx-login-button {

    width: 100%;
    height: 52px;

    display: flex;
    align-items: center;
    justify-content: center;

    gap: 9px;

    margin-top: 6px;

    color: #fff;

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #1d4ed8
        );

    border: 0;
    border-radius: 12px;

    font-family: inherit;
    font-size: 14px;
    font-weight: 700;

    cursor: pointer;

    box-shadow:
        0 10px 22px
        rgba(37, 99, 235, .20);

    transition:
        transform .15s ease,
        box-shadow .15s ease;

}

.etx-login-button:hover {

    transform: translateY(-1px);

    box-shadow:
        0 13px 26px
        rgba(37, 99, 235, .25);

}

.etx-login-button:active {

    transform: translateY(0);

}


/* ========================================
   DEMO ACCOUNTS
======================================== */

.etx-demo {

    margin-top: 27px;
    padding-top: 22px;

    border-top: 1px solid #edf0f4;

}

.etx-demo-header {

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 10px;

    margin-bottom: 11px;

}

.etx-demo-header strong {

    color: #334155;

    font-size: 12px;
    font-weight: 700;

}

.etx-demo-header span {

    color: #94a3b8;

    font-size: 10px;

}

.etx-demo-list {

    display: flex;
    flex-direction: column;

    gap: 8px;

}

.etx-demo-account {

    width: 100%;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 10px;

    padding: 10px 12px;

    color: inherit;
    background: #f8fafc;

    border: 1px solid #e8edf3;
    border-radius: 11px;

    font-family: inherit;

    text-align: left;

    cursor: pointer;

    transition:
        border-color .15s,
        background .15s,
        transform .15s;

}

.etx-demo-account:hover {

    background: #f0f6ff;

    border-color: #bfdbfe;

    transform: translateY(-1px);

}

.etx-demo-account strong {

    display: block;

    margin-bottom: 2px;

    color: #334155;

    font-size: 12px;
    font-weight: 700;

}

.etx-demo-account small {

    display: block;

    color: #64748b;

    font-size: 11px;

}

.etx-demo-use {

    flex: 0 0 auto;

    padding: 5px 8px;

    color: #2563eb;

    background: #fff;

    border: 1px solid #dbeafe;
    border-radius: 7px;

    font-size: 10px;
    font-weight: 700;

}


/* ========================================
   FOOTER
======================================== */

.etx-footer {

    margin-top: 21px;

    color: #a0aabc;

    text-align: center;

    font-size: 10px;

}


/* ========================================
   MOBILE
======================================== */

@media (max-width: 520px) {

    body {

        align-items: flex-start;

        padding: 18px 12px;

    }

    .etx-login-card {

        margin-top: 20px;

        padding: 27px 20px;

        border-radius: 19px;

    }

    .etx-login-header h1 {

        font-size: 25px;

    }

    .etx-brand {

        margin-bottom: 25px;

    }

}

</style>

</head>


<body>


<div class="etx-login-card">


    <!-- =====================
         BRAND
    ====================== -->

    <div class="etx-brand">

        <div class="etx-brand-icon">
            ET
        </div>

        <div class="etx-brand-text">

            <strong>
                EquipTrack
            </strong>

            <span>
                EQUIPMENT MANAGEMENT
            </span>

        </div>

    </div>



    <!-- =====================
         TITLE
    ====================== -->

    <div class="etx-login-header">

        <h1>
            เข้าสู่ระบบ
        </h1>

        <p>
            กรุณากรอกชื่อผู้ใช้และรหัสผ่าน
            เพื่อเข้าสู่ระบบยืม–คืนครุภัณฑ์
        </p>

    </div>



    <!-- =====================
         ERROR ALERT
    ====================== -->

    <?php if ($error !== ''): ?>

        <div class="etx-alert">

            <div class="etx-alert-icon">
                !
            </div>

            <div>
                <?= e($error) ?>
            </div>

        </div>

    <?php endif; ?>



    <!-- =====================
         LOGIN FORM
    ====================== -->

    <form
        method="post"
        autocomplete="off"
        id="loginForm"
    >

        <?= csrf_field() ?>


        <!-- USERNAME -->

        <div class="etx-field">

            <label for="username">
                ชื่อผู้ใช้
            </label>

            <div class="etx-input">

                <div class="etx-input-icon">
                    ●
                </div>

                <input
                    id="username"
                    type="text"
                    name="username"
                    maxlength="50"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="กรอกชื่อผู้ใช้"
                    value="<?= e($_POST['username'] ?? '') ?>"
                >

            </div>

        </div>



        <!-- PASSWORD -->

        <div class="etx-field">

            <label for="password">
                รหัสผ่าน
            </label>

            <div class="etx-input">

                <div class="etx-input-icon">
                    ◆
                </div>

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="กรอกรหัสผ่าน"
                >

                <button
                    type="button"
                    class="etx-password-button"
                    id="passwordToggle"
                >
                    แสดง
                </button>

            </div>

        </div>



        <!-- LOGIN BUTTON -->

        <button
            type="submit"
            class="etx-login-button"
            id="loginButton"
        >

            <span>
                เข้าสู่ระบบ
            </span>

            <span>
                →
            </span>

        </button>

    </form>



    <!-- =====================
         DEMO ACCOUNT
    ====================== -->

    <div class="etx-demo">

        <div class="etx-demo-header">

            <strong>
                บัญชีสำหรับทดสอบ
            </strong>

            <span>
                กดเพื่อกรอกอัตโนมัติ
            </span>

        </div>


        <div class="etx-demo-list">


            <!-- ADMIN -->

            <button
                type="button"
                class="etx-demo-account"
                data-user="admin"
                data-pass="admin"
            >

                <span>

                    <strong>
                        ผู้ดูแลระบบ
                    </strong>

                    <small>
                        admin / admin
                    </small>

                </span>

                <span class="etx-demo-use">
                    ใช้บัญชีนี้
                </span>

            </button>



            <!-- STAFF -->

            <button
                type="button"
                class="etx-demo-account"
                data-user="staff"
                data-pass="staff"
            >

                <span>

                    <strong>
                        เจ้าหน้าที่
                    </strong>

                    <small>
                        staff / staff
                    </small>

                </span>

                <span class="etx-demo-use">
                    ใช้บัญชีนี้
                </span>

            </button>



            <!-- STUDENT -->

            <button
                type="button"
                class="etx-demo-account"
                data-user="student"
                data-pass="student"
            >

                <span>

                    <strong>
                        นักศึกษา
                    </strong>

                    <small>
                        student / student
                    </small>

                </span>

                <span class="etx-demo-use">
                    ใช้บัญชีนี้
                </span>

            </button>


        </div>

    </div>



    <div class="etx-footer">
        EquipTrack • Equipment Borrowing System
    </div>


</div>


<script>

/* =========================
   SHOW / HIDE PASSWORD
========================= */

const passwordInput =
    document.getElementById('password');

const passwordToggle =
    document.getElementById('passwordToggle');

passwordToggle.addEventListener(
    'click',
    function () {

        if (passwordInput.type === 'password') {

            passwordInput.type = 'text';

            passwordToggle.textContent = 'ซ่อน';

        } else {

            passwordInput.type = 'password';

            passwordToggle.textContent = 'แสดง';

        }

    }
);


/* =========================
   DEMO ACCOUNT
========================= */

document
    .querySelectorAll('.etx-demo-account')
    .forEach(function (button) {

        button.addEventListener(
            'click',
            function () {

                const username =
                    this.dataset.user;

                const password =
                    this.dataset.pass;

                document
                    .getElementById('username')
                    .value = username;

                document
                    .getElementById('password')
                    .value = password;

                document
                    .getElementById('username')
                    .focus();

            }
        );

    });


/* =========================
   PREVENT DOUBLE SUBMIT
========================= */

document
    .getElementById('loginForm')
    .addEventListener(
        'submit',
        function () {

            const button =
                document.getElementById('loginButton');

            button.disabled = true;

            button.innerHTML =
                '<span>กำลังเข้าสู่ระบบ...</span>';

            button.style.opacity = '.75';
            button.style.cursor = 'wait';

        }
    );

</script>


</body>
</html>
