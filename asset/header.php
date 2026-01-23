<?php
// Session check
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="navbar">
    <nav class="nav-container">
        
        <a href="index.php" class="logo">
            <img src="asset/logo/logonav.png" alt="DreamTour Logo">
        </a>

        <ul class="nav-links">
            <li><a href="index.php" class="active">Home</a></li>
            </ul>

        <div class="nav-right">
            <?php if(isset($_SESSION['user_id'])): ?>

                <a href="profile.php" class="btn-profile">
                    <i class="fas fa-user-circle"></i> Profile
                </a>
                
                <!-- <span class="user-greeting">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span> -->
                
                
                <a href="logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>

            <?php else: ?>
                <a href="login.php" class="login-button">Login</a>
                <?php endif; ?>
        </div>

    </nav>
</header>