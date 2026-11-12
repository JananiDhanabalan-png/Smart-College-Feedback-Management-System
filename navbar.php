<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<style>

    * {
        box-sizing: border-box;
    }

    .main-navbar {
        width: 100%;
        min-height: 70px;
        background: white;

        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 0 5%;

        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);

        position: sticky;
        top: 0;
        z-index: 9999;
    }

    /* LOGO */

    .site-logo {
        text-decoration: none;
        font-size: 25px;
        font-weight: bold;
        color: #667eea;
        letter-spacing: 1px;
        white-space: nowrap;
    }

    /* LINKS */

    .main-nav-links {
        display: flex;
        align-items: center;
        gap: 22px;
        flex-wrap: wrap;
    }

    .main-nav-links a {
        text-decoration: none;
        color: #444;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        transition: 0.2s;
    }

    .main-nav-links a:hover {
        color: #667eea;
    }

    /* LOGIN */

    .nav-login {
        color: #667eea !important;
        border: 1px solid #667eea;
        padding: 8px 15px;
        border-radius: 7px;
    }

    .nav-login:hover {
        background: #f1f3ff;
    }

    /* REGISTER */

    .nav-register {
        background: #667eea;
        color: white !important;
        padding: 9px 15px;
        border-radius: 7px;
    }

    .nav-register:hover {
        background: #5568d8;
    }

    /* LOGOUT */

    .nav-logout {
        background: #ef4444;
        color: white !important;
        padding: 9px 15px;
        border-radius: 7px;
    }

    .nav-logout:hover {
        background: #dc2626;
    }

    /* MOBILE */

    @media (max-width: 950px) {

        .main-navbar {
            flex-direction: column;
            padding: 15px 4%;
            gap: 14px;
        }

        .main-nav-links {
            justify-content: center;
            gap: 10px 16px;
        }
    }

    @media (max-width: 500px) {

        .site-logo {
            font-size: 21px;
        }

        .main-nav-links a {
            font-size: 12px;
        }
    }

</style>


<nav class="main-navbar">

    <!-- LOGO -->

    <a href="index.php" class="site-logo">
        SCFMS
    </a>


    <div class="main-nav-links">

        <!-- HOME -->

        <a href="index.php">
            Home
        </a>


        <?php if (!isset($_SESSION["user"])): ?>

            <!-- NOT LOGGED IN -->

            <a href="login.php" class="nav-login">
                Login
            </a>

            <a href="register.php" class="nav-register">
                Register
            </a>


        <?php else: ?>

            <!-- LOGGED IN -->

            <a href="dashboard.php">
                Dashboard
            </a>


            <!-- STUDENT -->

            <?php if ($_SESSION["user"]["role"] == "student"): ?>

                <a href="feedback.php">
                    Give Feedback
                </a>

                <a href="my_feedback.php">
                    My Feedback
                </a>

            <?php endif; ?>


            <!-- FACULTY -->

            <?php if ($_SESSION["user"]["role"] == "faculty"): ?>

                <a href="faculty_feedback.php">
                    View Feedback
                </a>

            <?php endif; ?>


            <!-- COMMON -->

            <a href="notifications.php">
                Notifications
            </a>


            <!-- ADMIN -->

            <?php if ($_SESSION["user"]["role"] == "admin"): ?>

                <a href="reports.php">
                    Reports
                </a>

                <a href="users.php">
                    Users
                </a>

                <a href="cycles.php">
                    Cycles
                </a>

                <a href="form_builder.php">
                    Form Builder
                </a>

                <a href="analytics.php">
                    Analytics
                </a>

                <a href="export.php">
                    Export
                </a>

            <?php endif; ?>


            <!-- LOGOUT -->

            <a href="logout.php" class="nav-logout">
                Logout
            </a>

        <?php endif; ?>

    </div>

</nav>