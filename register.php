<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require "db.php";

$message = "";
$error = "";

// ==============================
// REGISTRATION PROCESS
// ==============================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form values
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $department = trim($_POST["department"] ?? "");
    $semester = trim($_POST["semester"] ?? "");


    // ==============================
    // 1. EMPTY FIELD VALIDATION
    // ==============================

    if (
        $name == "" ||
        $email == "" ||
        $password == "" ||
        $department == "" ||
        $semester == ""
    ) {

        $error = "All fields are required.";

    }


    // ==============================
    // 2. NAME VALIDATION
    // ==============================

    elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {

        $error = "Name should contain only letters.";

    }


    // ==============================
    // 3. EMAIL VALIDATION
    // ==============================

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Enter a valid email.";

    }


    // ==============================
    // 4. PASSWORD VALIDATION
    // ==============================

    elseif (strlen($password) < 6) {

        $error = "Password must contain at least 6 characters.";

    }


    // ==============================
    // 5. SEMESTER VALIDATION
    // ==============================

    elseif (
        !is_numeric($semester) ||
        $semester < 1 ||
        $semester > 8
    ) {

        $error = "Semester must be between 1 and 8.";

    }


    else {

        // ==============================
        // CHECK EMAIL ALREADY EXISTS
        // ==============================

        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->bind_param("s", $email);

        $check->execute();

        $result = $check->get_result();


        if ($result->num_rows > 0) {

            $error = "Email already exists.";

        }


        else {

            // ==============================
            // PASSWORD HASHING
            // ==============================

            $hash = hash("sha256", $password);


            // ==============================
            // INSERT STUDENT
            // ==============================

            $stmt = $conn->prepare(
                "INSERT INTO users
                (name, email, password, role, department, semester)
                VALUES (?, ?, ?, 'student', ?, ?)"
            );


            $stmt->bind_param(
                "sssss",
                $name,
                $email,
                $hash,
                $department,
                $semester
            );


            // ==============================
            // REGISTRATION SUCCESS
            // ==============================

            if ($stmt->execute()) {

                $message =
                    "Registration successful. You can login now.";

            }


            else {

                $error = "Registration failed.";

            }


            $stmt->close();

        }


        $check->close();

    }

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Student Registration</title>

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <style>

        /* ==============================
           RESET
        ============================== */

        * {
            box-sizing: border-box;
        }


        html,
        body {

            margin: 0;

            padding: 0;

            width: 100%;

            min-height: 100%;

        }


        /* ==============================
           BODY
        ============================== */

        body {

            font-family: Arial, sans-serif;

            background: linear-gradient(
                135deg,
                #667eea,
                #764ba2
            );

            min-height: 100vh;

        }


        /* ==============================
           NAVBAR AREA
        ============================== */

        .navbar-wrapper {

            width: 100%;

        }


        /* ==============================
           MAIN CONTAINER
        ============================== */

        .page-container {

            width: 100%;

            min-height: calc(100vh - 65px);

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 35px 20px;

        }


        /* ==============================
           REGISTRATION CARD
        ============================== */

        .card {

            width: 420px;

            max-width: 100%;

            background: #ffffff;

            padding: 35px;

            border-radius: 15px;

            box-shadow:
                0 10px 30px
                rgba(0, 0, 0, 0.20);

        }


        /* ==============================
           HEADING
        ============================== */

        h2 {

            text-align: center;

            margin: 0 0 25px 0;

            color: #333333;

            font-size: 28px;

        }


        /* ==============================
           LABEL
        ============================== */

        label {

            display: block;

            margin-top: 15px;

            margin-bottom: 6px;

            color: #444444;

            font-weight: bold;

            font-size: 14px;

        }


        /* ==============================
           INPUT
        ============================== */

        .input {

            display: block;

            width: 100%;

            height: 46px;

            padding: 12px;

            border: 1px solid #dddddd;

            border-radius: 8px;

            font-size: 15px;

            font-family: Arial, sans-serif;

            outline: none;

            background: #ffffff;

        }


        .input:focus {

            border-color: #667eea;

            box-shadow:
                0 0 5px
                rgba(102, 126, 234, 0.30);

        }


        /* ==============================
           REGISTER BUTTON
        ============================== */

        button {

            display: block;

            width: 100%;

            height: 46px;

            padding: 0;

            margin-top: 25px;

            border: none;

            border-radius: 8px;

            background: #667eea;

            color: #ffffff;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;

        }


        button:hover {

            background: #5568d8;

        }


        /* ==============================
           LOGIN BUTTON
        ============================== */

        .login-btn {

            display: block;

            width: 100%;

            height: 46px;

            line-height: 44px;

            text-align: center;

            margin-top: 12px;

            padding: 0;

            border: 1px solid #667eea;

            border-radius: 8px;

            color: #667eea;

            text-decoration: none;

            font-weight: bold;

            font-size: 14px;

        }


        .login-btn:hover {

            background: #667eea;

            color: #ffffff;

        }


        /* ==============================
           ERROR BOX
        ============================== */

        .error-box {

            width: 100%;

            background: #ffe5e5;

            color: #d00000;

            padding: 10px;

            border-radius: 7px;

            text-align: center;

            margin-bottom: 15px;

            font-size: 14px;

        }


        /* ==============================
           SUCCESS BOX
        ============================== */

        .success {

            width: 100%;

            background: #e5ffe9;

            color: #16852d;

            padding: 10px;

            border-radius: 7px;

            text-align: center;

            margin-bottom: 15px;

            font-size: 14px;

        }


        /* ==============================
           MOBILE RESPONSIVE
        ============================== */

        @media (max-width: 600px) {

            .page-container {

                padding: 25px 15px;

            }


            .card {

                width: 100%;

                padding: 25px 20px;

            }


            h2 {

                font-size: 24px;

            }

        }

    </style>

</head>


<body>


    <!-- ==============================
         NAVBAR
    ============================== -->

    <div class="navbar-wrapper">

        <?php include "navbar.php"; ?>

    </div>


    <!-- ==============================
         MAIN CONTENT
    ============================== -->

    <main class="page-container">


        <div class="card">


            <!-- TITLE -->

            <h2>
                Student Registration
            </h2>


            <!-- ERROR MESSAGE -->

            <?php if ($error != "") { ?>

                <div class="error-box">

                    <?php
                    echo htmlspecialchars($error);
                    ?>

                </div>

            <?php } ?>


            <!-- SUCCESS MESSAGE -->

            <?php if ($message != "") { ?>

                <div class="success">

                    <?php
                    echo htmlspecialchars($message);
                    ?>

                </div>

            <?php } ?>


            <!-- ==============================
                 REGISTRATION FORM
            ============================== -->

            <form method="POST">


                <!-- NAME -->

                <label for="name">
                    Name
                </label>

                <input
                    class="input"
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter your name"
                    value="<?php echo htmlspecialchars($_POST["name"] ?? ""); ?>"
                    required
                >


                <!-- EMAIL -->

                <label for="email">
                    Email
                </label>

                <input
                    class="input"
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>"
                    required
                >


                <!-- DEPARTMENT -->

                <label for="department">
                    Department
                </label>

                <input
                    class="input"
                    type="text"
                    id="department"
                    name="department"
                    placeholder="Enter your department"
                    value="<?php echo htmlspecialchars($_POST["department"] ?? ""); ?>"
                    required
                >


                <!-- SEMESTER -->

                <label for="semester">
                    Semester
                </label>

                <input
                    class="input"
                    type="number"
                    id="semester"
                    name="semester"
                    placeholder="Enter semester (1-8)"
                    min="1"
                    max="8"
                    value="<?php echo htmlspecialchars($_POST["semester"] ?? ""); ?>"
                    required
                >


                <!-- PASSWORD -->

                <label for="password">
                    Password
                </label>

                <input
                    class="input"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter password"
                    minlength="6"
                    required
                >


                <!-- REGISTER BUTTON -->

                <button type="submit">
                    Register
                </button>


                <!-- LOGIN LINK -->

                <a
                    class="login-btn"
                    href="login.php"
                >
                    Already have an account? Login
                </a>


            </form>


        </div>


    </main>


</body>

</html>

