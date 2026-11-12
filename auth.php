<?php

session_start();

function login_required()
{
    if (!isset($_SESSION["user"])) {

        header("Location: login.php");
        exit;

    }
}


function admin_required()
{
    login_required();

    if ($_SESSION["user"]["role"] != "admin") {

        die("Admin access only.");

    }
}


function h($text)
{
    return htmlspecialchars(
        $text ?? "",
        ENT_QUOTES,
        "UTF-8"
    );
}

?>