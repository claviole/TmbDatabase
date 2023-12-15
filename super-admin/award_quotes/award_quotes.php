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
            overflow: visible; /* Add this line */
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
    justify-content: space-evenly;
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
.quote-header, .quote {
    display: flex;
    justify-content: space-between;
}

.quote-id-header, .customer-name-header, .award-total-header,
.quote-id, .customer-name, .award-total {
    flex-basis: 33.33%;
    text-align: left;
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

<div class="quote-list" id="pending-quotes-table">
    <!-- Pending Quotes Table -->
    <div class="quote-header">
        <span class="quote-id-header">Invoice ID</span>
        <span class="customer-name-header">Customer Name</span>
        <span class="award-total-header">Award Total</span>
    </div>
    <?php foreach($quotes as $quote): ?>
        <div class="quote">
            <span class="quote-id"><?= $quote['invoice_id'] ?></span>
            <span class="customer-name"><?= $quote['Customer Name'] ?></span>
            <span class="award-total"><?= '$'.number_format($quote['award_total']) ?></span>
        </div>
    <?php endforeach; ?>
</div>
<div class="quote-list" id="awarded-quotes-table" style="display: none;">
    <!-- Awarded Quotes Table -->
    <div class="quote-header">
        <span class="quote-id-header">Invoice ID</span>
        <span class="customer-name-header">Customer Name</span>
        <span class="award-total-header">Award Total</span>
    </div>
    <?php foreach($awarded_quotes as $awarded): ?>
        <div class="quote">
            <span class="quote-id"><?= $awarded['invoice_id'] ?></span>
            <span class="customer-name"><?= $awarded['Customer Name'] ?></span>
            <span class="award-total"><?= '$'.number_format($awarded['award_total']) ?></span>
        </div>
    <?php endforeach; ?>
</div>
<div class="quote-files" style="display: none;"></div>
    <script>


$("#pending-quotes-btn").click(function() {
    $("#pending-quotes-table").show();
     $(".quote-files").hide();
    $("#awarded-quotes-table").hide();
});

$("#awarded-quotes-btn").click(function() {
    $("#pending-quotes-table").hide();
      $(".quote-files").hide();
    $("#awarded-quotes-table").show();
});

var currentQuoteId = null;

var currentQuoteId = null;

$(".quote").click(function() {
    var quoteId = $(this).find(".quote-id").text();
    var $quoteFiles = $(".quote-files"); // Changed this line

    if (currentQuoteId === quoteId) {
        $quoteFiles.slideUp();
        currentQuoteId = null;
    } else {
        $.ajax({
            url: 'get_files.php',
            method: 'POST',
            data: {quoteId: quoteId},
            success: function(data) {
                $quoteFiles.html(data);
                $quoteFiles.append(`
        <button class="award-quote">Award</button>
        <button class="refuse-quote">Reject</button>
    `);
                $quoteFiles.slideDown();
            }
        });
        currentQuoteId = quoteId;
    }
});
$(document).on('click', '.quote-files a', function(e) {
    e.preventDefault();
    var fileName = $(this).text();
    Swal.fire({
        title: 'Confirm download',
        text: 'Do you want to download ' + fileName + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Download',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Download the file
            window.location.href = $(this).attr('href');
        }
    });
});
$(document).on('click', '.award-quote', function() {
    // Handle award click
    $.ajax({
        url: 'award_quote.php',
        method: 'POST',
        data: {quoteId: currentQuoteId, version: 1},
        success: function(response) {
            // Handle the response from the server
            var data = JSON.parse(response);
            if (data.success) {
                Swal.fire('Success', data.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        }
    });
});

$(document).on('click', '.refuse-quote', function() {
    // Handle reject click
    $.ajax({
        url: 'refuse_quote.php',
        method: 'POST',
        data: {quoteId: currentQuoteId, version: 1 },
        success: function(response) {
            // Handle the response from the server
            var data = JSON.parse(response);
            if (data.success) {
                Swal.fire('Success', data.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        }
    });
});
</script>
</body>
</html>

