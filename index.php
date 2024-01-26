<?php
    //Unset all the server side variables
    session_start();
    $_SESSION["user"]="";
    $_SESSION["usertype"]="";
    $_SESSION["location_code"]="";

    // Set the new timezone
    date_default_timezone_set('America/Chicago');
    $date = date('m-d-Y');

    $_SESSION["date"]=$date;

    //import database connection
    include "configurations/connection.php";
    $pepper = $PEPPER; // Replace with your actual pepper

    if($_POST){
        $useremail=$_POST['useremail'];
        $userpassword=$_POST['userpassword'];

        // Prepare the statement
        $stmt = $database->prepare("SELECT * FROM `Users` WHERE `email` = ?");
        $stmt->bind_param("s", $useremail);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();
        if($result->num_rows>0){
            $row=$result->fetch_assoc();
            if(password_verify($pepper . $userpassword, $row['password'])) {
                $_SESSION["user"]=$row["username"];
                $_SESSION["user_type"]=$row["user_type"];
                $_SESSION["location_code"]=$row["location_code"];
                $_SESSION["user_id"]=$row["id"];
                if($_SESSION["user_type"]=="sales"){
                    header("Location: super-admin/index.php");
                }
                elseif($_SESSION["user_type"]=="super-admin"){
                    header("Location: super-admin/index.php");
                }
                elseif($_SESSION["user_type"]=="human-resources"){
                    header("Location: super-admin/index.php");
                }
                elseif($_SESSION["user_type"]=="maintenance-tech"){
                    header("Location: super-admin/index.php");
                }
                elseif($_SESSION["user_type"]=="floor-user"){
                    header("Location: maintenance/orange_tag_db.php");
                }
            } else {
                echo "<script>alert('Login Failed')</script>";
            }
        }
    }
?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        
    <title>Login</title>
    <style>
        .modal-content {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
}

.modal-header {
    border-bottom: 1px solid #dee2e6;
    background-color: #e9ecef;
}

.modal-body {
    padding: 2em;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
    justify-content: flex-end;
}

#forgotPasswordForm .form-group {
    margin-bottom: 1em;
}

#forgotPasswordForm .form-control {
    border-radius: 0.25em;
    border: 1px solid #ced4da;
}

#resetPassword {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
}

#resetPassword:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}
#forgotPasswordModal .modal-content {
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    #forgotPasswordModal .modal-header {
        background-color: #007bff;
        color: #fff;
    }

    #forgotPasswordModal .modal-footer .btn {
        border-radius: 5px;
    }

    #forgotPasswordModal .modal-footer .btn-primary {
        background-color: #28a745;
        border-color: #28a745;
    }

    #forgotPasswordModal .modal-footer .btn-primary:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    #forgotPasswordModal .modal-footer .btn-secondary {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    #forgotPasswordModal .modal-footer .btn-secondary:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
</style>
    
    
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
                        <a href="#" data-toggle="modal" data-target="#forgotPasswordModal" class="hover-link1 non-style-link">Forgot Password?</a>
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
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="forgotPasswordForm">
          <div class="form-group">
            <label for="email" class="col-form-label">Enter your email:</label>
            <input type="email" class="form-control" id="email" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="resetPassword">Reset Password</button>
      </div>
    </div>
  </div>
</div>
</center>
<script src="https://cdn.tailwindcss.com"></script>
<script>
 
    // Function to detect if the user is on a mobile device
    function isMobileDevice() {
        return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
    };

    // Function to detect if the device is in landscape mode
    function isLandscape() {
        return window.innerWidth > window.innerHeight;
    };

    // If the user is on a mobile device and not in landscape mode, show a notification
    if (isMobileDevice() && !isLandscape()) {
        alert("For the best experience, please use this application in landscape mode.");
    }



    $(document).ready(function() {
  $('#resetPassword').click(function() {
    var email = $('#email').val();

    $.ajax({
      url: 'configurations/reset_password.php',
      method: 'POST',
      data: { email: email },
      success: function(response) {
        // Close the modal
        $('#forgotPasswordModal').modal('hide');

        // Show a success message
        Swal.fire({
          icon: 'success',
          title: 'Password Reset',
          text: 'A new password has been sent to your email. It may take 5-10 minutes to arrive in your inbox.',
          confirmButtonText: 'OK'
        });
      },
      error: function(jqXHR, textStatus, errorThrown) {
        // Handle any errors
        console.error(textStatus, errorThrown);

        // Show an error message
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred while resetting your password. Please try again.',
          confirmButtonText: 'OK'
        });
      }
    });
  });
});
</script>
</body>
</html>