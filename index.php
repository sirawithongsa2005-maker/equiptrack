<?php
require __DIR__.'/includes/bootstrap.php';
if (current_user()) redirect(home_for_role());
redirect('auth/login.php');
