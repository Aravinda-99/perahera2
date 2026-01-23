<?php
// 1. Session Start (Session eka thibunoth witharai destroy karanna puluwan)
session_start();

// 2. Session Variables ain kirima (Empty array ekak assign karanawa)
$_SESSION = array();

// 3. Session Cookie eka delete kirima (Browser ekenuth session key eka makanawa)
// Meka godak wadagath security ekata
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Session eka destroy kirima (Server eken makanawa)
session_destroy();

// 5. Home page ekata redirect kirima
header("Location: index.php");
exit();
?>