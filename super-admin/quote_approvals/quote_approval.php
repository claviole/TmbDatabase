<?php
session_start();
include '../../connection.php';

// Fetch quotes for dropdown
$result = $database->query("SELECT `invoice_id`, `Customer Name` FROM `invoice` WHERE `approval_status` = 'Awaiting Approval'");
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
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.quote-list {
    width: 80%;
    margin: 20px auto;
    padding: 20px;
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
    margin-right: 10px;
}

.approve-quote, .deny-quote {
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
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
    </style>
</head>
<body>
    <div class="quote-list">
        <?php foreach($quotes as $quote): ?>
            <div class="quote">
                <span class="quote-id"><?= $quote['invoice_id'] ?></span>
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
