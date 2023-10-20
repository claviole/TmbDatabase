<?php
session_start();
include '../../connection.php';

// Fetch quotes for dropdown
$result = $database->query("SELECT `invoice_id`, `Customer Name`,`version` FROM `invoice` WHERE `approval_status` = 'Awaiting Approval'");
$quotes = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Quote Approvals</title>
    <style>
     body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .quote-list {
    position: relative; /* Add this line */
    width: 80%;
    margin: 20px auto;
    padding: 5px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
    overflow-y: auto;
    max-height: 400px;
}


        .quote {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .quote:last-child {
            border-bottom: none;
        }

        .quote-id, .customer-name {
            font-weight: 500;
            color: #333;
        }

        .approve-quote, .deny-quote {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: 700;
        }

        .approve-quote {
            background-color: #4CAF50;
            color: white;
        }

        .approve-quote:hover {
            background-color: #45a049;
        }

        .deny-quote {
            background-color: #f44336;
            color: white;
        }

        .deny-quote:hover {
            background-color: #da190b;
        }

        .quote-files {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }

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
        .quote-header {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background-color: #ddd;
}

.quote-id-header, .customer-name-header {
    font-weight: bold;
}
.quote, .quote-header {
    display: grid;
    grid-template-columns: 1fr 2fr 1fr 1fr 1fr; /* Adjust as needed */
    gap: 10px; /* Adjust as needed */
}
.quote-id {
    color: blue;
    cursor: pointer;
}
.quote-header {
    position: sticky; /* Change this line */
    top: 0px; /* Adjust as needed */
    width: 100%; /* Add this line */
    background-color: #fff; /* Add this line */
    z-index: 10; /* Add this line */
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background-color: #ddd;
}
    </style>
</head>
<body>
<div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Dashboard</a>
</div>
<div class="quote-list">
    <div class="quote-header">
        <span class="quote-id-header">Quote#</span>
        <span class="version-header">Version</span>
        <span class="customer-name-header">Customer Name</span>
        <span></span> <!-- Empty placeholders for buttons -->
        <span></span>
    </div>
    <?php foreach($quotes as $quote): ?>
        <div class="quote">
            <span class="quote-id"><?= $quote['invoice_id'] ?></span>
            <span class="version"><?= $quote['version'] ?></span>
            <span class="customer-name"><?= $quote['Customer Name'] ?></span>
            <button class="approve-quote">Approve</button>
            <button class="deny-quote">Deny</button>
        </div>
    <?php endforeach; ?>
</div>
<div class="quote-files">
        <!-- Files will be loaded here -->
    </div>
    <!-- Your scripts here -->
    <script>
    $(".quote").click(function() {
    var quoteId = $(this).find(".quote-id").text();
    $.ajax({
        url: 'fetch_quote_files.php',
        method: 'POST',
        data: {quoteId:quoteId},
        success: function(data) {
            $(".quote-files").html(data);
        }
    });
});

$(".approve-quote").click(function() {
    var quoteId = $(this).siblings(".quote-id").text();
    $.ajax({
        url: 'approve_quote.php',
        method: 'POST',
        data: {quoteId:quoteId},
        success: function(data) {
            alert("Quote approved successfully");
            location.reload();
        }
    });
});

$(".deny-quote").click(function() {
    var quoteId = $(this).siblings(".quote-id").text();
    $.ajax({
        url: 'deny_quote.php',
        method: 'POST',
        data: {quoteId:quoteId},
        success: function(data) {
            alert("Quote denied successfully");
            location.reload();
        }
    });
});

</script>
</body>
</html>