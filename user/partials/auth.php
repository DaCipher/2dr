<?php

if (!isset($_SESSION['login']) || $_SESSION['login'] != true) {

    header("location: ../account/signin.php?postLogin=.." . $_SERVER['REQUEST_URI']);

    // exit();
    return false;

} else {

    $data = getSingleRecord('investment', 'user_id', $_SESSION['id']);

    $user = getSingleRecord('users', 'id', $_SESSION['id']);

    if ($user['status'] === 'active') {

        if ($_SESSION['role'] !== 'user') {

            header('location: ../admin');

        }

    } elseif ($user['status'] === 'verify' || $user['status'] === 'verifying') {

        header('location: ./verification.php');
        // return false;

    } else {

        header('location: ../logout');
        return false;

    }

}

