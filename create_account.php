<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/signup.css">
        
    <title>Create Account</title>
    <style>
        .container{
            animation: transitionIn-X 0.5s;
        }
    </style>
</head>
<body>
<?php

// Unset all the server side variables
session_start();
$_SESSION["user"]="";
$_SESSION["usertype"]="";

// Set the new timezone
date_default_timezone_set('America/Chicago');
$date = date('Y-m-d');

$_SESSION["date"]=$date;

// Import database connection
include "connection.php";

if($_POST){
    $result = $database->query("SELECT * FROM users");

    $fname=$_SESSION['personal']['fname'];
$lname=$_SESSION['personal']['lname'];
$name=$fname." ".$lname;
$email=$_POST['newemail']; // changed from 'new_email'
$new_password=$_POST['newpassword']; // changed from 'new_password'
$cpassword=$_POST['cpassword'];

    if($new_password==$cpassword){
        $sqlmain="SELECT * FROM `users` WHERE `email`=?";
        $stmt=$database->prepare($sqlmain);
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result=$stmt->get_result();
        if($result->num_rows==1){
            $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
        }
        else{
            $database->query("INSERT INTO `users`(`username`, `email`, `password`, `user_type`) VALUES ('$name','$email','$new_password','user')");

            $_SESSION["user"]=$email;
            $_SESSION["usertype"]="user";
            $_SESSION["name"]=$name;

            header('location: User/index.php');
            $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>';

            
        }
    }
    else{$error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Conformation Error! Reconform Password</label>';

    }



   
}
else{
    //header('location: signup.php');
    $error='<label for="promter" class="form-label"></label>';
}

?>


    <center>
    <div class="__layout">
             
             <div class="text-black flex items-start md:items-center justify-center min-h-screen bg-image px-4 py-8 md:pt-0 bg-gray-50">
                 <div class="flex items-center justify-center flex-col">
                    Styule
                     <div class="flex items-start content-start justify-start h-full overflow-hidden bg-white rounded-lg shadow card mt-4 rounded-lg shadow max-w-md p-8 w-full">
                     <div class="w-full">
                             <div class="text-center w-full min-w-[300px]">
                             <h2 class="font-medium text-xl">
                            Log in or sign up in seconds
                        </h2>
                        <p class="text-sm text-gray-700 mb-4">
                            Login or create an account to get started
                        </p>
                        <div class="w-full">
                        <table class="space-y-3">

            <tr>
                <form action="" method="POST" >
                <td class="label-td" colspan="2">
                    <label for="newemail" class="block text-sm font-medium leading-5 text-gray-900 text-gray-700">Email: </label>
                </td>
            </tr>
            <tr>
            <td class="relative rounded-md shadow-sm">
                    <input type="email" name="newemail" class="form-input block w-full rounded border p-2 sm:text-sm sm:leading-5 border-black/20 opacity-100 bg-dark/10 border-black/20 text-black" placeholder="Email Address" required>
                </td>
                
            </tr>
            <tr>
                <td class="flex space-x-4">
                    <!-- Create Password Input -->
                    <div class="w-1/2">
                        <label for="newpassword" class="text-left pt-4 block text-sm font-medium leading-5 text-gray-900 text-gray-700">Create New Password:</label>
                        <input type="password" name="newpassword" class="form-input block w-full rounded border p-2 sm:text-sm sm:leading-5 border-black/20 opacity-100 bg-dark/10 border-black/20 text-black" placeholder="New Password" required>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="w-1/2">
                        <label for="cpassword" class="text-left pt-4 block text-sm font-medium leading-5 text-gray-900 text-gray-700">Confirm Password:</label>
                        <input type="password" name="cpassword" class="form-input block w-full rounded border p-2 sm:text-sm sm:leading-5 border-black/20 opacity-100 bg-dark/10 border-black/20 text-black" placeholder="Confirm Password" required>
                    </div>
</td>
            </tr>
            

     
            <tr>
                
                <td colspan="2">
                    <?php echo $error ?>

                </td>
            </tr>
            
            <tr>
                <td colspan="2">
                    <br>
                    <div>
                        <input type="submit" value="Sign Up" class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-[#1B145D] px-6 py-4 text-sm font-bold text-white transition-all duration-200 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">

                    </div> 
                    <br>
                    <label for="" class="sub-text" style="font-weight: 280;">Already have an account&#63; </label>
                    <a href="login.php" class="hover-link1 non-style-link">Login</a>
                    <br><br><br>
                </td>
            </tr>

            </tr>
        </table>


                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</center>
<script src="https://cdn.tailwindcss.com"></script>

</body>
</html>