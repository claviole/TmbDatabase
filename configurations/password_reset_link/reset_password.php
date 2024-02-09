<?php
include "../connection.php";
$pepper = $PEPPER; // Replace with your actual pepper

function isPasswordComplexEnough($password) {
    // Implement password complexity requirements here
    // Example: Minimum 8 characters, at least one uppercase, one lowercase, one number, and one special character
    return strlen($password) >= 8 &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/[a-z]/', $password) &&
           preg_match('/[0-9]/', $password) &&
           preg_match('/[\W]/', $password);
}

if ($_GET) {
    $token = $_GET['token'];

    // Check if the token exists in the database
    $stmt = $database->prepare("SELECT * FROM `password_resets` WHERE `token` = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $email = $result->fetch_assoc()['email'];

        if ($_POST) {
            $new_password = $_POST['new-password'];
            $confirm_password = $_POST['confirm-password'];

            if ($new_password === $confirm_password) {
                if (isPasswordComplexEnough($new_password)) {
                    // Hash the new password with the pepper
                    $hashed_password = password_hash($pepper . $new_password, PASSWORD_DEFAULT);

                    // Update the password in the database
                    $stmt = $database->prepare("UPDATE `Users` SET `password` = ? WHERE `email` = ?");
                    $stmt->bind_param("ss", $hashed_password, $email);
                    $stmt->execute();

                    // Delete the token from the database
                    $stmt = $database->prepare("DELETE FROM `password_resets` WHERE `token` = ?");
                    $stmt->bind_param("s", $token);
                    $stmt->execute();

                    // Redirect to the main index page
                    header("Location: ../../index.php");
                    exit();
                } else {
                    // Display an enhanced and informative error message
                    echo '<div class="error-message">
                            The new password does not meet the complexity requirements:<br>
                            - At least 8 characters long<br>
                            - Contains at least one uppercase letter<br>
                            - Contains at least one lowercase letter<br>
                            - Contains at least one number<br>
                            - Contains at least one special character
                          </div>';
                }
            } else {
                echo "New password and confirm password do not match.";
            }
        }
    } else {
        echo "Invalid password reset token.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
         .error-message {
        color: #D8000C; /* Red color for error messages */
        background-color: #FFD2D2; /* Light red background */
        border: 1px solid #D8000C;
        margin: 20px 0;
        padding: 10px;
        border-radius: 5px;
        text-align: left; /* Align text to the left for the list */
        font-weight: bold;
    }
        body {
            background-image: url('../../images/steel_coils.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h1 {
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
    <h1 style="width: 100%; text-align: center;">
        <img src="../../images/home_page_company_header.png" alt="company header" style="max-width: 100%; height: auto;">
    </h1>
    <form action="" method="POST">
        <label for="new-password">New Password:</label>
        <input type="password" id="new-password" name="new-password" required>
        <label for="confirm-password">Confirm New Password:</label>
        <input type="password" id="confirm-password" name="confirm-password" required>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>