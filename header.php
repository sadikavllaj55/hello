<?php
session_start();

require 'includes/functions.inc.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <base href="<?= BASE_URL ?>/">
    <!-- This file has been downloaded from Bootsnipp.com. Enjoy! -->
    <title>ATIS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="img/favicon.ico">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body style="background-color: darkgrey">
<div class="wrapper dark-header" style="margin-bottom:2em;overflow:visible;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">ATIS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuBar" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="navbar-nav nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

            </ul>
            <?php if (!is_logged_in()): ?>
            <div class="collapse navbar-collapse justify-content-end" id="menuBar">
                <ul class="navbar-nav nav">

                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php">Sign Up</a>
                    </li>
                </ul>
            </div>
            <?php else: ?>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav nav">
                    <li class="nav-item">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                Blog
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="post.php">Create New...</a></li>
                                <li><a class="dropdown-item" href="myposts.php">My Posts</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="edit.php">
                            <img src="<?= BASE_URL . ($_SESSION['user']['profile_image'] ?? '/img/missing_profile.png') ?>" width="30" height="30" class="rounded-circle me-2"><?= $_SESSION['user']['username']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="includes/actions/logout.inc.php">Log Out</a>
                    </li>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </nav>
</div>
