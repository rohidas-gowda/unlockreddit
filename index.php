<?php
require_once 'config.php';
include_once 'analytics.txt';

if (isset($_SESSION['user_token'])) {
    header("Location: dashboard.php");
} else {
    header("Location: login.php");
}