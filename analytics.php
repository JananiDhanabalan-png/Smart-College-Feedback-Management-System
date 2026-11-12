<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "db.php";


/* ==============================
   TOTAL FEEDBACK
============================== */

$totalFeedback = 0;

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM feedback"
);

if ($result) {

    $row = mysqli_fetch_assoc($result);

    $totalFeedback = $row['total'];
}


/* ==============================
   TOTAL ANSWERS
============================== */

$totalAnswers = 0;

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM answers"
);

if ($result) {

    $row = mysqli_fetch_assoc($result);

    $totalAnswers = $row['total'];
}


/* ==============================
   OVERALL AVERAGE RATING
============================== */

$overallAverage = 0;

$result = mysqli_query(
    $conn,
    "SELECT AVG(rating) AS average FROM answers"
);

if ($result) {

    $row = mysqli_fetch_assoc($result);

    if ($row['average'] !== null) {

        $overallAverage = round(
            $row['average'],
            2
        );

    }
}


/* ==============================
   QUESTION-WISE AVERAGE
============================== */

$questionData = [];

$sql = "

    SELECT

        questions.question,

        questions.category,

        AVG(answers.rating) AS average_rating,

        COUNT(answers.id) AS total_answers

    FROM questions

    LEFT JOIN answers

        ON questions.id = answers.question_id

    GROUP BY questions.id

    ORDER BY questions.id ASC

";


$result = mysqli_query($conn, $sql);


if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $questionData[] = $row;

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Analytics Dashboard</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            font-family: Arial, sans-serif;

            background: #f4f6f9;

            margin: 0;

            padding: 0;

        }


        .container {

            width: 90%;

            max-width: 1100px;

            margin: 40px auto;

        }


        h1 {

            text-align: center;

            margin-bottom: 30px;

            color: #222;

        }


        .cards {

            display: flex;

            gap: 20px;

            justify-content: center;

            flex-wrap: wrap;

        }


        .card {

            background: white;

            width: 250px;

            padding: 25px;

            text-align: center;

            border-radius: 10px;

            box-shadow: 0 3px 10px rgba(0,0,0,0.1);

        }


        .card h2 {

            margin: 0;

            font-size: 35px;

            color: #2563eb;

        }


        .card p {

            margin-top: 10px;

            color: #666;

        }


        .table-box {

            background: white;

            margin-top: 35px;

            padding: 25px;

            border-radius: 10px;

            box-shadow: 0 3px 10px rgba(0,0,0,0.1);

            overflow-x: auto;

        }


        .table-box h2 {

            margin-top: 0;

            color: #333;

        }


        table {

            width: 100%;

            border-collapse: collapse;

        }


        th,
        td {

            padding: 12px;

            border-bottom: 1px solid #ddd;

            text-align: left;

        }


        th {

            background: #f1f1f1;

        }


        .rating {

            font-weight: bold;

            color: #2563eb;

        }


        .no-data {

            text-align: center;

            padding: 20px;

            color: #777;

        }


        @media(max-width: 700px) {

            .container {

                width: 95%;

                margin: 25px auto;

            }

            .card {

                width: 100%;

            }

        }

    </style>

</head>


<body>


<!-- ==============================
     NAVBAR
================================ -->

<?php include "navbar.php"; ?>


<div class="container">


    <h1>

        Analytics & Dashboard

    </h1>


    <!-- ==========================
         SUMMARY CARDS
    =========================== -->

    <div class="cards">


        <div class="card">

            <h2>

                <?php echo $totalFeedback; ?>

            </h2>

            <p>

                Total Feedback

            </p>

        </div>


        <div class="card">

            <h2>

                <?php echo $totalAnswers; ?>

            </h2>

            <p>

                Total Answers

            </p>

        </div>


        <div class="card">

            <h2>

                <?php echo $overallAverage; ?>/5

            </h2>

            <p>

                Average Rating

            </p>

        </div>


    </div>


    <!-- ==========================
         QUESTION ANALYTICS
    =========================== -->

    <div class="table-box">


        <h2>

            Question-wise Average Rating

        </h2>


        <?php if (count($questionData) > 0) { ?>


            <table>

                <thead>

                    <tr>

                        <th>
                            Question
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Average Rating
                        </th>

                        <th>
                            Total Answers
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php foreach ($questionData as $data) { ?>


                    <tr>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $data['question']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $data['category']
                            );

                            ?>

                        </td>


                        <td class="rating">


                            <?php

                            if ($data['average_rating'] !== null) {

                                echo round(
                                    $data['average_rating'],
                                    2
                                ) . "/5";

                            } else {

                                echo "No ratings";

                            }

                            ?>


                        </td>


                        <td>

                            <?php

                            echo $data['total_answers'];

                            ?>

                        </td>


                    </tr>


                <?php } ?>


                </tbody>

            </table>


        <?php } else { ?>


            <div class="no-data">

                No feedback data available.

            </div>


        <?php } ?>


    </div>


</div>


</body>

</html>