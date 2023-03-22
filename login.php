<?php
require_once 'config.php';
include_once 'analytics.txt';

if (isset($_SESSION['user_token'])) {
    header("Location: dashboard.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unlock Reddit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100;200;300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Roboto Slab', serif;
        }
    </style>
</head>

<body
    class="bg-neutral-900 h-[calc(100vh-128px)] sm:h-[calc(100vh-160px)] md:h-[calc(100vh-224px)] lg:h-[calc(100vh-240px)] xl:h-[calc(100vh-256px)] 2xl:h-[calc(100vh-288px)]">

    <div><h2 class="mt-8 text-2xl text-center font-semibold md:text-left md:ml-10"><span style="color: #ffdb84;">Unlock</span><span style="color: #379bf7;"> Reddit</span></h2></div>

    <h1 style="color: #379bf7;" class="text-6xl h-48 text-center mt-24 px-4 sm:mt-40 md:mt-36 md:h-40 lg:mt-36 lg:h-36 2xl:mt-36">
        <a href="" class="typewrite text-center" data-period="2000"
            data-type='[ "Run Out of Blog Post Ideas?", "Never Again!" ]'>
            <span class="wrap"></span>
        </a>
    </h1>

    <div class="text-center mt-12 md:mt-8 lg:mt-6 xl:mt-4 2xl:mt-2">
    <?php
    echo "<a href='" . $client->createAuthUrl() . "'>
    <button class=\"bg-yellow-300 hover:bg-white font-bold text-black rounded-lg p-2 text-lg\">Get Blog Post Ideas</button>
    </a>";
    ?>
    </div>

    <script src="typewriter.js" charset="utf-8"></script>
</body>

</html>