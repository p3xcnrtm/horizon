<?php
require('db.php');
session_start();
ob_start(); // Start output buffering

// If form submitted, process login
if (isset($_POST['email'])) {
    // Remove backslashes
    $email = stripslashes($_REQUEST['email']);
    $email = mysqli_real_escape_string($con, $email);
    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($con, $password);

    // Checking if user exists in the database
    $query = "SELECT * FROM `users` WHERE email=? AND password=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $email, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $profits_value = $row['profits'];
        $last_updated = $row['last_updated'] ?? ''; // Use an empty string if NULL

if (!empty($last_updated)) {
    $last_update_time = strtotime($last_updated);
} else {
    $last_update_time = 0; // Fallback to a default time
}
        $current_time = time();

        // Update profits if more than a day has passed
        if (($current_time - $last_update_time) >= 86400) {
            $updated_profits = $profits_value + ($profits_value * 0.005);
            $update_query = "UPDATE `users` SET profits=?, last_updated=NOW() WHERE email=?";
            $stmt = mysqli_prepare($con, $update_query);
            mysqli_stmt_bind_param($stmt, 'ds', $updated_profits, $email);
            mysqli_stmt_execute($stmt);
            $_SESSION['profits'] = number_format($updated_profits, 2);
        } else {
            $_SESSION['profits'] = number_format($profits_value, 2);
        }

        // Save session variables
        $_SESSION['username'] = $row['username'];
        $_SESSION['xlm'] = $row['xlm'];
        $_SESSION['xrp'] = $row['xrp'];
        $_SESSION['btc'] = $row['btc'];
        $_SESSION['usdt'] = $row['usdt'];
        $_SESSION['shiba'] = $row['shiba'];
        $_SESSION['eth'] = $row['eth'];
        $_SESSION['ltc'] = $row['ltc'];
        $_SESSION['dodge'] = $row['dodge'];
        $_SESSION['trn_date'] = $row['trn_date'];
        $_SESSION['email'] = $row['email'];

        // Capture IP address, user agent, and current time
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $last_login_time = date('Y-m-d H:i:s');

        // Update user login details
        $update_query = "UPDATE `users` SET ip_address=?, user_agent=?, last_login_time=? WHERE email=?";
        $stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($stmt, 'ssss', $ip_address, $user_agent, $last_login_time, $email);
        mysqli_stmt_execute($stmt);

        header("Location: dashboard.php");
    } else {
    exit(); // Stop execution after the redirect
        echo "<div class='form'>
                <h3>Email or Password is incorrect.</h3>
                <br/>Incorrect Email or Password. Click here to <a href='login.php'>Login</a></div>";
    }
} else {
	ob_end_flush(); // End output buffering
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Horizon Capital</title>
  <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <style>
    .nav-link {
      position: relative;
    }
    .nav-link:after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -2px;
      left: 0;
      background-color: #fff;
      transition: width 0.3s ease;
    }
    .nav-link:hover:after {
      width: 100%;
    }
  </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-700 min-h-screen flex flex-col">

  <!-- Header / Navigation -->
  <nav class="w-full z-50 bg-white/90 backdrop-blur-md shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-20 items-center">
        <div class="flex-shrink-0 flex items-center">
          <span class="text-2xl font-bold text-indigo-800">HORIZON</span>
        </div>
       
      </div>
    </div>
  </nav>

  <!-- Main Content (centered form) -->
  <main class="flex-grow flex items-center justify-center py-16">
    <div class="bg-white rounded-xl shadow-xl p-8 max-w-md w-full">
      <h2 class="text-2xl font-bold mb-6 text-indigo-800 text-center">Login to Horizon</h2>
      <form id="loginForm" class="space-y-5" method="POST" action="">
        <div>
          <label for="email" class="block text-gray-700 mb-1">Email</label>
          <input id="email" type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" name="email" required>
          <div id="emailError" class="text-red-500 text-sm mt-1 hidden">Please enter a valid email.</div>
        </div>
        <div>
          <label for="password" class="block text-gray-700 mb-1">Password</label>
          <input id="password" type="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" name="password" required>
          <div id="passwordError" class="text-red-500 text-sm mt-1 hidden">Password is required.</div>
        </div>
        <button type="submit" name="user_login" class="w-full py-3 bg-indigo-700 text-white rounded-lg hover:bg-indigo-800 transition">Login</button>
      </form>
      <div class="text-center mt-4">
        <span class="text-gray-600">Don't have an account?</span>
        <a href="sign up.php" class="text-indigo-700 font-semibold hover:underline">Sign Up</a>
      </div>
    </div>
  </main>

 
    </div>
 
  <script>AOS.init();</script>
  <script>feather.replace();</script>
  <script>
    // Example login redirect logic
    const form = document.getElementById('loginForm');
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const email = document.getElementById('email');
      const password = document.getElementById('password');
      let valid = true;
      if (!email.value.includes('@')) {
        document.getElementById('emailError').style.display = 'block';
        valid = false;
      } else {
        document.getElementById('emailError').style.display = 'none';
      }
      if (password.value.trim() === '') {
        document.getElementById('passwordError').style.display = 'block';
        valid = false;
      } else {
        document.getElementById('passwordError').style.display = 'none';
      }
       if (!valid) {
        window.location.href = "login.php";
      }
    });
  </script>
</body>
</html>
 <?php
}
?>
