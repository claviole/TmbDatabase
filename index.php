<?php

    //Unset all the server side variables
    session_start();
    $_SESSION["user"]="";
    $_SESSION["usertype"]="";

    // Set the new timezone
    date_default_timezone_set('America/Chicago');
    $date = date('Y-m-d');

    $_SESSION["date"]=$date;

    //import database connection
        include "connection.php";
        if($_POST){
            $useremail=$_POST['useremail'];
            $userpassword=$_POST['userpassword'];
            $sql="SELECT * FROM `Users` WHERE `email`='$useremail' AND `password`='$userpassword'";
            $result=$database->query($sql);
            if($result->num_rows>0){
                $row=$result->fetch_assoc();
                $_SESSION["user"]=$row["username"];
                $_SESSION["user_type"]=$row["user_type"];
                if($_SESSION["user_type"]=="Sales"){
                    header("Location: sales/index.php");
                }
                elseif($_SESSION["user_type"]=="super-admin"){
                    header("Location: super-admin/index.php");
                }
                elseif($_SESSION["user_type"]=="Human Resources"){
                    header("Location: human_resources/index.php");
                  
                }
            }
            else{
                echo "<script>alert('Login Failed')</script>";
            }
        }
    ?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
        
    <title>Login</title>

    
    
</head>
<body style="background-image: url('images/steel_coils.jpg'); background-size: cover;">
     <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="images/home_page_company_header.png" alt="company header" width="30%" height="20%" > 
    </h1>
    <center>
    <div class="__layout">
             
             <div  class="text-black flex items-start  justify-center min-h-screen  px-4 py-8 md:pt-0 ">
                 <div class="flex items-center justify-center flex-col">
                    
                    <div class="flex items-start content-start justify-start h-full overflow-hidden bg-white rounded-lg shadow card mt-4 rounded-lg shadow max-w-md p-8 w-full">
                     <div class="w-full">
                             <div class=" w-full min-w-[300px]">
                             <h2 class="font-medium text-xl">
                             Welcome Back!                        </h2>
                        <p class="text-sm text-gray-700 mb-4">
                        Login with your details to continue                        </p>
                        <div class="w-full">
                            <div>

        <table class="space-y-6">

            <div class="space-y-5 text-left">
                <form action="" method="POST" >

                <div class="mb-4">
                    <label for="useremail" class="block text-base font-medium text-gray-900">Email: </label>
                    <div class="relative mt-2.5 text-gray-600 focus-within:text-gray-400">
                        <input type="email" name="useremail" class="block w-full rounded-md border border-gray-200 bg-gray-50 py-4 pl-10 pr-4 text-black placeholder-gray-500 caret-blue-600 transition-all duration-200 focus:border-blue-600 focus:bg-white focus:outline-none" placeholder="Email Address" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="userpassword" class="block text-base font-medium text-gray-900">Password: </label>
                    <div class="relative mt-2.5 text-gray-600 focus-within:text-gray-400">
                        <input type="Password" name="userpassword" class="block w-full rounded-md border border-gray-200 bg-gray-50 py-4 pl-10 pr-4 text-black placeholder-gray-500 caret-blue-600 transition-all duration-200 focus:border-blue-600 focus:bg-white focus:outline-none" placeholder="Password" required>
                    </div>
                </div>

                <tr>
                    <td class="mb-4">
                    </td>
                </tr>
                <tr>
                <td colspan="2">
                        <br>
                        <div>
                            <input type="submit" value="Login" class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-[#1B145D] px-6 py-4 text-sm font-bold text-white transition-all duration-200 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                        </div>
                        </br>
                        <label for="" class="sub-text" style="font-weight: 280;">Don't have an account&#63; </label>
                        <a href="signup.php" class="hover-link1 non-style-link">Sign Up</a>
                        <br><br><br>
                </td>
            </tr>    
                </form>

            </div>
            
        

                        
   
    
                        
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