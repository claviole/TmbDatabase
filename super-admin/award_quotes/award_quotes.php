<?php
session_start();
include '../../connection.php';

// Fetch quotes for dropdown
$result = $database->query("SELECT `invoice_id`, `Customer Name`,`version`,`award_total` FROM `invoice` WHERE `approval_status` = 'Approved' AND `award_status` ='pending'");
$quotes = $result->fetch_all(MYSQLI_ASSOC);

$award_result = $database->query("SELECT `invoice_id`, `Customer Name`,`version`,`award_total` FROM `invoice` WHERE `approval_status` = 'Approved' AND `award_status` ='Awarded'");
$awarded_quotes = $award_result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Quote Awards</title>
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
    padding: 0px;
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

        .quote-id, .customer-name, .version, .award-total {
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

.quote-id-header, .customer-name-header,.version-header,.award-total-header {
    font-weight: bold;
}

.award-total {
    color: #008000; /* This is a green color similar to a dollar bill */
}
.quote, .quote-header {
    display: grid;
    grid-template-columns: 1fr 1fr 2fr 1fr .5fr .5fr; /* Adjust as needed */
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
.award-quote, .refuse-quote {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: 700;
        }

        .award-quote {
            background-color: #4CAF50;
            color: white;
        }

        .award-quote:hover {
            background-color: #45a049;
        }

        .refuse-quote {
            background-color: #f44336;
            color: white;
        }

        .refuse-quote:hover {
            background-color: #da190b;
        }
        .award-total {
    color: #008000; /* This is a green color similar to a dollar bill */
}
.quote-id {
    color: blue;
    cursor: pointer;
}
.customer-name{
    margin-left: 50px;
}
.version{
    margin-left: 60px;
}
.award-total{
    margin-left: -50px;
}
.quote-btn {
        padding: 10px 20px;
        margin: 10px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .quote-btn:hover {
        opacity: 0.8;
    }

    #pending-quotes-btn {
        background-color: #4CAF50; /* Green */
        color: white;
    }

    #awarded-quotes-btn {
        background-color: #008CBA; /* Blue */
        color: white;
    }
    .button-container {
    margin-left: 20px; /* Adjust this value as needed */
    display: flex;
    justify-content: center; /* Center the buttons horizontally */
}

.button-container button {
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin: 0 10px; /* Add some space between the buttons */
    background-color: #4CAF50; /* Green */
    color: white; /* White text */
}

.button-container button:hover {
    background-color: #45a049; /* Darker green */
}

    </style>
</head>
<body style="background-image: url('../../images/steel_coils.jpg'); background-size: cover;">
<div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Dashboard</a>
</div>
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../../images/home_page_company_header.png" alt="company header" width="30%" height="20%" > 
        
    </h1>



    <div class="button-container">
    <button id="pending-quotes-btn">Pending Quotes</button>
    <button id="awarded-quotes-btn">Awarded Quotes</button>
</div>

<div id="pending-quotes-table" class="quote-list">

    <div class="quote-header">
        <span class="quote-id-header">Quote#</span>
        <span class="version-header">Version</span>
        <span class="customer-name-header">Customer Name</span>
        <span class="award-total-header">Award Total</span>
        <span></span> <!-- Empty placeholders for buttons -->
        <span></span>
    </div>
    <?php foreach($quotes as $quote): ?>
        <div class="quote" id="quote-<?= $quote['invoice_id'] ?>-<?= $quote['version'] ?>">
            <span class="quote-id"><?= $quote['invoice_id'] ?></span>
            <span class="version"><?= $quote['version'] ?></span>
            <span class="customer-name"><?= $quote['Customer Name'] ?></span>
            <span class="award-total"><?= '$'.number_format($quote['award_total'])?></span>
            <button class="award-quote" id="award-<?= $quote['invoice_id'] ?>-<?= $quote['version'] ?>">Award</button>
            <button class="refuse-quote" id="refuse-<?= $quote['invoice_id'] ?>-<?= $quote['version'] ?>">Refuse</button>
        </div>
    <?php endforeach; ?>
</div>

<div id="awarded-quotes-table" class="quote-list" style="display: none;">
    <div class="quote-header">
        <span class="quote-id-header">Quote#</span>
        <span class="version-header">Version</span>
        <span class="customer-name-header">Customer Name</span>
        <span class="award-total-header">Award Total</span>
        <span></span> <!-- Empty placeholders for buttons -->
        <span></span>
    </div>
    <?php foreach($awarded_quotes as $awarded ): ?>
        <div class="quote" id="quote-<?= $awarded['invoice_id'] ?>-<?= $awarded['version'] ?>">
            <span class="quote-id"><?= $awarded['invoice_id'] ?></span>
            <span class="version"><?= $awarded['version'] ?></span>
            <span class="customer-name"><?= $awarded['Customer Name'] ?></span>
            <span class="award-total"><?= '$'.number_format($awarded['award_total'])?></span>
        </div>
    <?php endforeach; ?>
</div>


    <script>
$(".award-quote").click(function() {
    var quoteIdAndVersion = $(this).attr('id').split('-');
    var quoteId = quoteIdAndVersion[1];
    var version = quoteIdAndVersion[2];
    $.ajax({
        url: 'award_quote.php',
        method: 'POST',
        data: {quoteId:quoteId, version:version},
        dataType: 'json',
        success: function(data) {
            Swal.fire({
                title: data.success ? 'Success' : 'Error',
                text: data.message,
                icon: data.success ? 'success' : 'error'
            }).then(function() {
                location.reload();
            });
        }
    });
});

$(".refuse-quote").click(function() {
    var quoteIdAndVersion = $(this).attr('id').split('-');
    var quoteId = quoteIdAndVersion[1];
    var version = quoteIdAndVersion[2];
    $.ajax({
        url: 'refuse_quote.php',
        method: 'POST',
        data: {quoteId:quoteId, version:version},
        dataType: 'json',
        success: function(data) {
            Swal.fire({
                title: data.success ? 'Success' : 'Error',
                text: data.message,
                icon: data.success ? 'success' : 'error'
            }).then(function() {
                location.reload();
            });
        }
    });
});
$("#pending-quotes-btn").click(function() {
    $("#pending-quotes-table").show();
    $("#awarded-quotes-table").hide();
});

$("#awarded-quotes-btn").click(function() {
    $("#pending-quotes-table").hide();
    $("#awarded-quotes-table").show();
});

$(".quote").click(function() {
    var quoteId = $(this).find(".quote-id").text();
    var version = $(this).find(".version").text();

    var pdfUrl = "get_pdf.php?invoice_id=" + quoteId + "&version=" + version;

    Swal.fire({
        title: 'Confirm download',
        text: 'Do you want to download the awarded Quote?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, download it!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open(pdfUrl, '_blank');
        }
    });
});
</script>
</body>
</html>

