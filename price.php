<?php
require_once 'config.php';
include_once 'analytics.txt';
require_once 'router.php';

$user_email = $userinfo['email'];
date_default_timezone_set('Asia/Kolkata');



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>unlock reddit</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-green-200 to-green-400">
    <div class="mt-4">
        <h2 class="ml-8 text-2xl font-semibold">Unlock Reddit</h2>
    </div>
    <div class="grid grid-cols-1 justify-items-center">

        <div class="h-56 shadow-lg w-80 mt-8 bg-slate-800 rounded-lg">
            <h1 class="text-slate-200 text-center text-2xl mt-4">Free</h1>
            <h2 class="text-slate-200 text-center text-4xl font-semibold mt-4">$0 / Month</h2>
            <h3 class="text-slate-200 text-center mt-4">No Credit Card Required!</h3>
            <div class="flex justify-center mt-6">
                <button class="bg-yellow-400 text-center w-48 py-1 hover:bg-slate-100 hover:font-semibold rounded-lg" onclick="redirect_free_dashboard('true','false')">Select</button>
            </div>
        </div>

        <div class="h-56 shadow-lg w-80 mt-16 bg-slate-800 rounded-lg">
            <h1 class="text-slate-200 text-center text-2xl mt-4">Pro</h1>
            <h2 class="text-slate-200 text-center text-4xl font-semibold mt-4">$9 / Month</h2>
            <h3 class="text-slate-200 text-center mt-4">Get Unlimited Content Ideas</h3>
            <div class="flex justify-center mt-6">
                <button class="bg-yellow-400 text-center w-48 py-1 hover:bg-slate-100 hover:font-semibold rounded-lg" onclick="redirect_pro_dashboard('false','true')">Select</button>
            </div>
        </div>

    </div>

    <script>
        function redirect_free_dashboard(freeStatus, premiumStatus) {
            var user_email = '<?php echo $user_email; ?>';
            var signup_date = '<?php echo date('d-m-Y'); ?>';

            fetch("validate-price.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        // your expected POST request payload goes here
                        dtsignup: signup_date,
                        email: user_email,
                        free: freeStatus,
                        premium: premiumStatus
                    }),
                })
                .then((res) => res.json())
                .then((data) => {
                    // enter you logic when the fetch is successful
                    console.log(data);
                })
                .catch((error) => {
                    // enter your logic for when there is an error (ex. error toast)
                    console.log(error);
                });

            window.location.href = "dashboard.php";
        }

        function redirect_pro_dashboard(freeStatus, premiumStatus) {
            alert("Thanks for your interest! Now Enjoy the Pro version for FREE!");
            var user_email = '<?php echo $user_email; ?>';
            var signup_date = '<?php echo date('d-m-Y'); ?>';

            fetch("validate-price.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        // your expected POST request payload goes here
                        dtsignup: signup_date,
                        email: user_email,
                        free: freeStatus,
                        premium: premiumStatus
                    }),
                })
                .then((res) => res.json())
                .then((data) => {
                    // enter you logic when the fetch is successful
                    console.log(data);
                })
                .catch((error) => {
                    // enter your logic for when there is an error (ex. error toast)
                    console.log(error);
                });
            window.location.href = "dashboard.php";
        }
    </script>
</body>

</html>