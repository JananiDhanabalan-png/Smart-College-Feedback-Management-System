<?php

require "db.php";
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($email == "" || $password == "") {

        $error = "Please enter email and password.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, name, email, password, role, department, semester
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && hash("sha256", $password) == $user["password"]) {

            unset($user["password"]);

            $_SESSION["user"] = $user;

            header("Location: dashboard.php");
            exit;

        } else {

            $error = "Invalid email or password.";

        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>College Feedback - Login</title>

    <style>

        body {
            font-family: Arial;
            background: #f2f2f2;
            margin: 0;
        }

        .login-box {
            width: 350px;
            margin: 100px auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #555;
        }

        .register {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
        }

        .error {
            color: red;
            text-align: center;
        }

    </style>

</head>

<body>
    <?php include "navbar.php"; ?>

<div class="login-box">

    <h2>College Feedback</h2>

    <h3>Login</h3>

    <?php if ($error != "") { ?>

        <p class="error">
            <?php echo $error; ?>
        </p>

    <?php } ?>

    <form method="POST">

        <label>Email</label>

        <input
            type="email"
            name="email"
            placeholder="Enter your email"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Enter your password"
            required
        >

        <button type="submit">
            Login
        </button>

    </form>

    <a class="register" href="register.php">
        Create Account
    </a>

</div>

</body>

</html>