<?php

require 'config/function.php';

if (isset($_POST['login_btn'])) {
    $usernameInput = validate($_POST['username']);
    $passwordInput = validate($_POST['password']);
    $rememberMe = isset($_POST['remember_me']); // Check if "Remember Me" is checked

    $username = filter_var($usernameInput, FILTER_SANITIZE_EMAIL);
    $password = filter_var($passwordInput, FILTER_SANITIZE_STRING);

    if ($username != '' && $password != '') {
        $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if (password_verify($password, $row['password'])) {

                if ($row['is_verified'] == 0) {
                    redirect('login.php', 'Please verify your email before logging in.', 'warning');
                }

                if ($row['role'] == 'admin') {
                    if ($row['is_ban'] == 1) {
                        redirect('login.php', 'Your account has been banned. Please contact admin.', 'warning');
                    }

                    $_SESSION['auth'] = true;
                    $_SESSION['loggedInUserRole'] = $row['role'];
                    $_SESSION['loggedInUser'] = [
                        'userId' => $row['id'],
                        'email' => $row['email']
                    ];

                    // Handle "Remember Me" cookies
                    if ($rememberMe) {
                        setcookie('username', $username, time() + (86400 * 30), "/"); // 30 days
                        setcookie('password', $passwordInput, time() + (86400 * 30), "/"); // 30 days
                    } else {
                        setcookie('username', '', time() - 3600, "/");
                        setcookie('password', '', time() - 3600, "/");
                    }

                    redirect('admin/index.php', 'Logged in successfully', 'success');
                } elseif ($row['role'] == 'user') {
                    if ($row['is_ban'] == 1) {
                        redirect('login.php', 'Your account has been banned. Please contact admin.', 'warning');
                    }

                    $_SESSION['auth'] = true;
                    $_SESSION['loggedInUserRole'] = $row['role'];
                    $_SESSION['loggedInUser'] = [
                        'userId' => $row['id'],
                        'email' => $row['email']
                    ];

                    $user_id = validate($_SESSION['loggedInUser']['userId']);
                    logActivity($user_id, "Logged in");

                    // Handle "Remember Me" cookies
                    if ($rememberMe) {
                        setcookie('username', $username, time() + (86400 * 30), "/"); // 30 days
                        setcookie('password', $passwordInput, time() + (86400 * 30), "/"); // 30 days
                    } else {
                        setcookie('username', '', time() - 3600, "/");
                        setcookie('password', '', time() - 3600, "/");
                    }

                    redirect('index.php', 'Logged in successfully', 'success');
                }
            } else {
                redirect('login.php', 'Invalid username or Password', 'warning');
            }
        } else {
            redirect('login.php', 'Something went wrong', 'error');
        }
    } else {
        redirect('login.php', 'All fields are necessary.', 'warning');
    }
}

?>