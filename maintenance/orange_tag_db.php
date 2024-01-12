<?php
session_start();
include '../configurations/connection.php'; 
date_default_timezone_set('America/Chicago');
  // Query to get the total count of tickets
  $count_query = "SELECT COUNT(*) as total FROM orange_tag";
  $count_result = mysqli_query($database, $count_query);
  $data = mysqli_fetch_assoc($count_result);
  $count = $data['total']+1;

    $tag_author= $_SESSION['user'];
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <style>
        .return-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1B145D;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-weight: 700;
        }

        .return-button:hover {
            background-color: #111;
        }

        .return-button-container {
            text-align: right;
            margin-right: 10px;
        }
        .accident-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1B145D;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-weight: 700;
        }

        .accident-button:hover {
            background-color: #111;
        }

        .accident-button-container {
            text-align: right;
            margin-right: 10px;
        }
        .table-auto {
    background-color: white;
    border-collapse: separate;
    border-spacing: 0;
    overflow: auto;
    width: 100%;
    height: 50%;
    border: 2px solid #1B145D; /* Add this line to add a border */
}

.table-auto th, .table-auto td {
    border: 1px solid lightgray;
    padding: 8px;
}

    .modal-content {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    .modal-header {
        border-bottom: 1px solid #dee2e6;
        background-color: #e9ecef;
    }

    .modal-title {
        color: #495057;
    }

    .modal-body {
        padding: 2em;
    }

    .form-control {
        border-radius: 0;
    }

    .form-group label {
        font-weight: bold;
        color: #495057;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    .table-auto tbody tr .accident-id {
    cursor: pointer;
}

.table-auto tbody tr:hover {
    background-color: #e0e0e0;
}
.modal-dialog.custom-modal {
        max-width: 1000px; /* Adjust this value to set the width of your form */
    }
    </style>

    <title>Maintenance Dashboard</title>
    <!-- Add your CSS styles here -->
</head>
<body style="background-image: url('../images/steel_coils.jpg'); background-size: cover;">
<div class="return-button-container">
    <a href="index.php" class="return-button">Return to Maintenance Home</a>
</div>
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../images/home_page_company_header.png" alt="company header" width="30%" height="20%" > 
</div>
     
    </h1>
    
    <div class ="flex justify-center">
    <button class="btn btn-primary" data-toggle="modal" data-target="#newTicketModal">New Maintenance Ticket</button>
    <button class="btn btn-secondary" onclick="window.location.href='view_closed.php'">View Closed</button>
    </div>

    <?php
    $query = "SELECT * FROM orange_tag";
    $result = mysqli_query($database, $query);
    ?>
    <div class="scrollable-table">
    <table class="table employee-table">
        <thead>
            <tr>
                <!-- Add your table headers here -->
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <!-- Add your table data here -->
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </div>

    <!-- New Ticket Modal -->
<div class="modal fade" id="newTicketModal" tabindex="-1" role="dialog" aria-labelledby="newTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal" role="document"> <!-- Add the custom-modal class here -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newTicketModalLabel">New Maintenance Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="basic-info-tab" data-toggle="tab" href="#basic-info" role="tab" aria-controls="basic-info" aria-selected="true">Basic Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="false">Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="review-info-tab" data-toggle="tab" href="#review-info" role="tab" aria-controls="review-info" aria-selected="false">Review Info</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="basic-info" role="tabpanel" aria-labelledby="basic-info-tab">
                        <form id="new-ticket-form-basic-info">
                        <div class="form-group">
                            <label for="orange_tag_id">Orange Tag ID</label>
                            <input type="text" class="form-control" id="orange_tag_id" name="orange_tag_id" value="CH-<?php echo $count; ?>" required readonly>
                        </div>
                            <div class="form-group">
                                <label for="ticket_type">Ticket Type</label>
                                <input type="text" class="form-control" id="ticket_type" name="ticket_type" required>
                            </div>
                            <div class="form-group">
                                <label for="originator">Originator</label>
                                <input type="text" class="form-control" id="originator" name="originator" value="<?php echo $tag_author; ?>" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                            </div>
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <input type="number" class="form-control" id="priority" name="priority" required>
                            </div>
                            <div class="form-group">
                                <label for="section">Section</label>
                                <input type="text" class="form-control" id="section" name="section" required>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                        <form id="new-ticket-form-details">
                            <!-- Add your details form fields here -->
                        </form>
                    </div>
                    <div class="tab-pane fade" id="review-info" role="tabpanel" aria-labelledby="review-info-tab">
                        <form id="new-ticket-form-review-info">
                            <!-- Add your review info form fields here -->
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-ticket">Save Ticket</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>