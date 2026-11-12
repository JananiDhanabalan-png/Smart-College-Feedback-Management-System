
<?php

require "db.php";
require "auth.php";

login_required();

include "header.php";

// Get all common notifications
$stmt = $conn->prepare(
    "SELECT message, created_at
     FROM notifications
     ORDER BY id DESC"
);

$stmt->execute();

$result = $stmt->get_result();

?>

<style>

    .notification-page {
        width: 90%;
        max-width: 900px;
        margin: 45px auto;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 32px;
        color: #222;
    }

    .page-header p {
        margin-top: 8px;
        color: #777;
        font-size: 15px;
    }

    .notification-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .notification-card {
        background: #ffffff;
        padding: 22px 25px;
        border-radius: 12px;
        border-left: 5px solid #667eea;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: 0.2s;
    }

    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(0, 0, 0, 0.10);
    }

    .notification-top {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .notification-icon {
        width: 38px;
        height: 38px;
        background: #eef0ff;
        color: #667eea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .notification-title {
        font-size: 16px;
        font-weight: bold;
        color: #333;
    }

    .notification-message {
        margin: 0 0 15px 50px;
        color: #555;
        font-size: 15px;
        line-height: 1.6;
    }

    .notification-date {
        margin-left: 50px;
        color: #999;
        font-size: 13px;
    }

    .empty-notification {
        background: white;
        padding: 50px 25px;
        text-align: center;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .empty-icon {
        font-size: 45px;
        margin-bottom: 15px;
    }

    .empty-notification h2 {
        margin: 0 0 8px;
        color: #333;
    }

    .empty-notification p {
        margin: 0;
        color: #888;
    }

    @media (max-width: 600px) {

        .notification-page {
            width: 92%;
            margin: 30px auto;
        }

        .page-header h1 {
            font-size: 27px;
        }

        .notification-card {
            padding: 18px;
        }

        .notification-message,
        .notification-date {
            margin-left: 0;
        }

    }

</style>


<div class="notification-page">

    <div class="page-header">

        <h1>Notifications</h1>

        <p>
            Stay updated with important feedback system notifications.
        </p>

    </div>


    <?php if ($result->num_rows == 0): ?>

        <div class="empty-notification">

            <div class="empty-icon">
                🔔
            </div>

            <h2>No Notifications</h2>

            <p>
                You don't have any notifications at the moment.
            </p>

        </div>

    <?php else: ?>

        <div class="notification-list">

            <?php while ($n = $result->fetch_assoc()): ?>

                <div class="notification-card">

                    <div class="notification-top">

                        <div class="notification-icon">
                            🔔
                        </div>

                        <div class="notification-title">
                            Feedback Notification
                        </div>

                    </div>


                    <p class="notification-message">

                        <?= nl2br(h($n["message"])) ?>

                    </p>


                    <div class="notification-date">

                        🕒
                        <?= h($n["created_at"]) ?>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    <?php endif; ?>

</div>


<?php

include "footer.php";

?>
