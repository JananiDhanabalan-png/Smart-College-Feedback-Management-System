
<?php

require "db.php";
require "auth.php";

admin_required();

$message = "";
$error = "";


// Create Feedback Cycle
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $start = $_POST["start_date"];
    $end = $_POST["end_date"];
    $status = $_POST["status"];


    if ($title == "" || $start == "" || $end == "") {

        $error = "All fields are required.";

    } elseif ($end < $start) {

        $error = "End date must be after start date.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO feedback_cycles
            (title, start_date, end_date, status)
            VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssss",
            $title,
            $start,
            $end,
            $status
        );

        if ($stmt->execute()) {

            $message = "Feedback cycle created successfully.";

        } else {

            $error = "Failed to create feedback cycle.";

        }
    }
}


// Get all feedback cycles
$r = $conn->query(
    "SELECT *
     FROM feedback_cycles
     ORDER BY id DESC"
);


include "header.php";

?>


<style>

    .cycle-page {
        width: 90%;
        max-width: 1050px;
        margin: 45px auto;
    }


    .page-header {
        margin-bottom: 30px;
    }


    .page-header h1 {
        margin: 0;
        color: #222;
        font-size: 32px;
    }


    .page-header p {
        color: #777;
        margin-top: 8px;
        font-size: 15px;
    }


    .card {
        background: white;
        padding: 28px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 25px;
    }


    .card h2 {
        margin-top: 0;
        color: #333;
        font-size: 21px;
    }


    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }


    .form-group {
        display: flex;
        flex-direction: column;
    }


    .form-group.full {
        grid-column: 1 / -1;
    }


    label {
        margin-bottom: 7px;
        font-weight: bold;
        color: #444;
    }


    .input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 7px;
        font-size: 14px;
        outline: none;
    }


    .input:focus {
        border-color: #667eea;
    }


    button {
        margin-top: 20px;
        padding: 12px 22px;
        border: none;
        border-radius: 7px;
        background: #667eea;
        color: white;
        font-weight: bold;
        font-size: 15px;
        cursor: pointer;
    }


    button:hover {
        background: #5568d8;
    }


    .success {
        background: #e5ffe9;
        color: #16852d;
        padding: 12px 15px;
        border-radius: 7px;
        margin-bottom: 20px;
    }


    .error {
        background: #ffe5e5;
        color: #d00000;
        padding: 12px 15px;
        border-radius: 7px;
        margin-bottom: 20px;
    }


    .table-container {
        overflow-x: auto;
    }


    table {
        width: 100%;
        border-collapse: collapse;
    }


    th {
        background: #f5f7fb;
        color: #444;
        padding: 14px;
        text-align: left;
        font-size: 14px;
    }


    td {
        padding: 14px;
        border-bottom: 1px solid #eee;
        color: #555;
        font-size: 14px;
    }


    tr:last-child td {
        border-bottom: none;
    }


    .status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        background: #eef0ff;
        color: #667eea;
        font-size: 13px;
        font-weight: bold;
    }


    .empty {
        text-align: center;
        color: #777;
        padding: 25px;
    }


    @media (max-width: 700px) {

        .cycle-page {
            width: 92%;
            margin: 30px auto;
        }


        .page-header h1 {
            font-size: 27px;
        }


        .form-grid {
            grid-template-columns: 1fr;
        }


        .form-group.full {
            grid-column: auto;
        }


        .card {
            padding: 20px;
        }

    }

</style>


<div class="cycle-page">


    <!-- Header -->

    <div class="page-header">

        <h1>
            Feedback Cycle Management
        </h1>

        <p>
            Create and manage feedback collection periods.
        </p>

    </div>


    <!-- Messages -->

    <?php if ($message != ""): ?>

        <div class="success">

            <?= h($message) ?>

        </div>

    <?php endif; ?>


    <?php if ($error != ""): ?>

        <div class="error">

            <?= h($error) ?>

        </div>

    <?php endif; ?>


    <!-- Create Cycle -->

    <div class="card">

        <h2>
            Create New Feedback Cycle
        </h2>


        <form method="POST">

            <div class="form-grid">


                <div class="form-group full">

                    <label>
                        Cycle Title
                    </label>

                    <input
                        class="input"
                        type="text"
                        name="title"
                        placeholder="Example: Semester 6 Feedback"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Start Date
                    </label>

                    <input
                        class="input"
                        type="date"
                        name="start_date"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        End Date
                    </label>

                    <input
                        class="input"
                        type="date"
                        name="end_date"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Status
                    </label>

                    <select
                        class="input"
                        name="status"
                        required
                    >

                        <option value="Scheduled">
                            Scheduled
                        </option>

                        <option value="Active">
                            Active
                        </option>

                        <option value="Closed">
                            Closed
                        </option>

                    </select>

                </div>


            </div>


            <button type="submit">
                Create Cycle
            </button>

        </form>

    </div>


    <!-- Existing Cycles -->

    <div class="card">

        <h2>
            Existing Feedback Cycles
        </h2>


        <div class="table-container">

            <?php if ($r->num_rows == 0): ?>

                <div class="empty">
                    No feedback cycles found.
                </div>

            <?php else: ?>

                <table>

                    <tr>

                        <th>
                            Title
                        </th>

                        <th>
                            Start Date
                        </th>

                        <th>
                            End Date
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>


                    <?php while ($x = $r->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?= h($x["title"]) ?>
                            </td>

                            <td>
                                <?= h($x["start_date"]) ?>
                            </td>

                            <td>
                                <?= h($x["end_date"]) ?>
                            </td>

                            <td>

                                <span class="status">

                                    <?= h($x["status"]) ?>

                                </span>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </table>

            <?php endif; ?>

        </div>

    </div>


</div>


<?php

include "footer.php";

?>
