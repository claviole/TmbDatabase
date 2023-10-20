<?php
session_start();
include '../../connection.php';

// Fetch quotes for dropdown
$result = $database->query("SELECT `invoice_id`, `Customer Name` FROM `invoice` ");
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
    width: 80%;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
    overflow-y: auto;  /* Add this line */
    max-height: 400px; /* Adjust this value as needed */
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
    grid-template-columns: 1fr 3fr 1fr 1fr; /* Adjust as needed */
    gap: 10px; /* Adjust as needed */
}
.quote-id {
    color: blue;
    cursor: pointer;
}
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
    <!-- ... -->
    <div class="quote-list">
        <input type="text" id="quote-search" placeholder="Search quotes...">
        <div class="quote-header">
            <!-- ... -->
        </div>
    <?php foreach($quotes as $quote): ?>
        <div class="quote">
            <span class="quote-id"><?= $quote['invoice_id'] ?></span>
            <span class="customer-name"><?= $quote['Customer Name'] ?></span>
          
        </div>
    <?php endforeach; ?>
</div>
<div class="quote-files">
        <!-- Files will be loaded here -->
    </div>
    <!-- Your scripts here -->
    <script>
          $('#quote-search').on('input', function() {
            var search = $(this).val().toLowerCase();
            $('.quote').each(function() {
                var quoteId = $(this).find('.quote-id').text().toLowerCase();
                var customerName = $(this).find('.customer-name').text().toLowerCase();
                if (quoteId.includes(search) || customerName.includes(search)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
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



</script>
</body>
</html>
