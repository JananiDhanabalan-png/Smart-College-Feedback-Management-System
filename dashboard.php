<?php

require "db.php";
require "auth.php";


login_required();

include "header.php";


$forms = $conn->query(
    "SELECT COUNT(*) total FROM feedback_forms"
)->fetch_assoc()["total"];


$feedback = $conn->query(
    "SELECT COUNT(*) total FROM feedback"
)->fetch_assoc()["total"];


$courses = $conn->query(
    "SELECT COUNT(*) total FROM courses"
)->fetch_assoc()["total"];

?>

<style>

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f5f7fb;
    }

    .dashboard {
        width: 90%;
        max-width: 1100px;
        margin: 40px auto;
    }

    .dashboard-title {
        margin-bottom: 30px;
    }

    .dashboard-title h1 {
        margin: 0;
        color: #222;
        font-size: 32px;
    }

    .dashboard-title p {
        margin-top: 8px;
        color: #777;
        font-size: 15px;
    }


    /* Statistics Cards */

    .grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 12px;

        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);

        text-align: center;
    }

    .stat-card h3 {
        margin: 0;
        color: #555;
        font-size: 16px;
        font-weight: normal;
    }

    .stat-card h2 {
        margin: 12px 0 0;
        font-size: 32px;
        color: #667eea;
    }


    /* Welcome Card */

    .welcome-card {
        background: white;
        padding: 30px;
        border-radius: 12px;

        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);

        margin-bottom: 30px;
    }

    .welcome-card h2 {
        margin-top: 0;
        color: #333;
    }

    .welcome-card p {
        color: #666;
        font-size: 16px;
        margin: 10px 0;
    }

    .role {
        font-weight: bold;
        color: #667eea;
    }


    /* Buttons */

    .actions {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: stretch;
}

.actions .btn {
    flex: 1 1 220px;
    min-width: 220px;
    min-height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 14px 20px;
}
    .btn {
        display: inline-block;

        padding: 12px 22px;

        background: #667eea;
        color: white;

        text-decoration: none;

        border-radius: 7px;

        margin-right: 10px;

        font-weight: bold;
    }

    .btn:hover {
        background: #5568d8;
    }


    /* Mobile */

    @media (max-width: 768px) {

        .grid {
            grid-template-columns: 1fr;
        }

        .dashboard {
            width: 92%;
            margin: 25px auto;
        }

        .dashboard-title h1 {
            font-size: 27px;
        }

    }

</style>


<div class="dashboard">


    <!-- Dashboard Title -->

    <div class="dashboard-title">

        <h1>Dashboard</h1>

        <p>
            Welcome to the College Feedback System
        </p>

    </div>


    <!-- Statistics -->

    <div class="grid">


        <div class="stat-card">

            <h3>
                Feedback Forms
            </h3>

            <h2>
                <?= $forms ?>
            </h2>

        </div>


        <div class="stat-card">

            <h3>
                Feedback Submitted
            </h3>

            <h2>
                <?= $feedback ?>
            </h2>

        </div>


        <div class="stat-card">

            <h3>
                Courses
            </h3>

            <h2>
                <?= $courses ?>
            </h2>

        </div>


    </div>


    <!-- Welcome -->

    <div class="welcome-card">

        <h2>
            Welcome,
            <?= h($_SESSION["user"]["name"]) ?> 👋
        </h2>


        <p>

            Role:

            <span class="role">
                <?= h($_SESSION["user"]["role"]) ?>
            </span>

        </p>


        <?php if ($_SESSION["user"]["role"] == "student"): ?>

            <div class="actions">

                <a
                    class="btn"
                    href="feedback.php"
                >
                    Give Feedback
                </a>

            </div>

        <?php endif; ?>


        <!-- <?php if ($_SESSION["user"]["role"] == "admin"): ?>

            <div class="actions">

                <a
                    class="btn"
                    href="reports.php"
                >
                    View Reports
                </a>

                <a class="btn" href="users.php">
    Manage Users
</a>

            </div>

        <?php endif; ?> -->

        <?php if ($_SESSION["user"]["role"] == "admin"): ?>

<div class="actions">

    <a class="btn" href="reports.php">
        📊 View Reports
    </a>

    <a class="btn" href="users.php">
        👥 Manage Users
    </a>

    <a class="btn" href="cycles.php">
        📅 Feedback Cycles
    </a>

    <a class="btn" href="export.php">
        📥 Export Report
    </a>

</div>

<?php endif; ?>


        <?php if ($_SESSION["user"]["role"] == "faculty"): ?>

            <div class="actions">

                <!-- <a
                    class="btn"
                    href="reports.php"
                >
                    View Feedback
                </a> -->
                <a class="btn" href="faculty_feedback.php">
    View Feedback
</a>

            </div>

        <?php endif; ?>


    </div>


</div>


<?php

include "footer.php";

?>