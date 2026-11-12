<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SCFMS - Smart College Feedback Management System</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #333;
        }

        /* =========================
           NAVBAR
        ========================= */

        .navbar {
            width: 100%;
            min-height: 70px;

            background: #ffffff;

            padding: 0 30px;

            display: flex;
            align-items: center;

            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);

            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* =========================
           LOGO
        ========================= */

        .logo {
            text-decoration: none;

            font-size: 25px;
            font-weight: bold;

            color: #667eea;

            letter-spacing: 1px;

            white-space: nowrap;

            margin-right: 35px;
        }

        .logo:hover {
            color: #5568d8;
        }

        /* =========================
           NAVIGATION
        ========================= */

        .nav-links {
            display: flex;

            align-items: center;

            gap: 18px;

            margin-left: auto;

            flex-wrap: nowrap;
        }

        .nav-links a {
            text-decoration: none;

            color: #444;

            font-size: 13px;

            font-weight: 600;

            white-space: nowrap;

            padding: 8px 0;

            transition: 0.2s;
        }

        .nav-links a:hover {
            color: #667eea;
        }

        /* =========================
           HOME
        ========================= */

        .home-link {
            color: #667eea !important;
        }

        /* =========================
           LOGOUT / REGISTER
        ========================= */

        .logout {
            background: #667eea;

            color: white !important;

            padding: 8px 14px !important;

            border-radius: 7px;
        }

        .logout:hover {
            background: #5568d8;

            color: white !important;
        }

        /* =========================
           TABLET
        ========================= */

        @media (max-width: 1200px) {

            .navbar {
                padding: 0 20px;
            }

            .logo {
                margin-right: 20px;
            }

            .nav-links {
                gap: 13px;
            }

            .nav-links a {
                font-size: 12px;
            }

        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 900px) {

            .navbar {
                flex-direction: column;

                padding: 15px 20px;

                gap: 15px;
            }

            .logo {
                margin-right: 0;
            }

            .nav-links {

                margin-left: 0;

                justify-content: center;

                flex-wrap: wrap;

                gap: 10px 18px;

            }

        }

        @media (max-width: 500px) {

            .logo {
                font-size: 21px;
            }

            .nav-links {
                gap: 8px 12px;
            }

            .nav-links a {
                font-size: 12px;
            }

            .logout {
                padding: 7px 12px !important;
            }

        }

    </style>

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->

<nav class="navbar">


    <!-- LOGO -->

    <a href="index.php" class="logo">
        SCFMS
    </a>


    <!-- NAVIGATION -->

    <div class="nav-links">


        <!-- HOME -->

        <a href="index.php" class="home-link">
            Home
        </a>


        <?php if (isset($_SESSION["user"])): ?>


            <!-- COMMON -->

            <a href="dashboard.php">
                Dashboard
            </a>


            <a href="notifications.php">
                Notifications
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

            <a href="logout.php" class="logout">
                Logout
            </a>


        <?php else: ?>


            <!-- NOT LOGGED IN -->

            <a href="login.php">
                Login
            </a>

            <a href="register.php" class="logout">
                Register
            </a>


        <?php endif; ?>


    </div>

</nav>