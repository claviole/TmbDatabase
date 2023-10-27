<?php
session_start();
include '../../connection.php';

// Fetch quotes for dropdown
$result = $database->query("SELECT `invoice_id`, `Customer Name`,`award_status` FROM `invoice` WHERE `Customer Name` IS NOT NULL AND `Customer Name` <> '' GROUP BY `invoice_id`, `Customer Name`");
$quotes = $result->fetch_all(MYSQLI_ASSOC);

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
  
    padding: 10px;
    background-color: #ddd;
}

.quote-id-header, .customer-name-header,.award-status-header {
    font-weight: bold;
}
.quote-id-header {
    max-width: 50%; /* Adjust as needed */
    margin-left: 0px;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.customer-name-header{
    max-width: 50%;
    margin-left: 500px;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.award-status-header{
    max-width: 50%;
    margin-left: 700px;
    text-overflow: ellipsis;
    white-space: nowrap;

}
.quote{
    display: grid;
    grid-template-columns: 1fr 1fr 1fr; /* Adjust as needed */
    justify-content: start; /* Add this line */
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
    padding: 10px;
    background-color: #ddd;
}


        .quote-files {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }

       

   

.quote-id {
    color: blue;
    cursor: pointer;
}

.quote-files {
    display: none;
    width: 80%;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
}
.btn {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 10px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    font-weight: 700;
}

.btn:hover {
    background-color: #45a049;
}
.delete-btn {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 10px;
    margin-left: 10px;
    background-color: #f44336;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    font-weight: 700;
}

.delete-btn:hover {
    background-color: #da190b;
}

    </style>
</head>
<body>
<body style="background-image: url('../../images/steel_coils.jpg'); background-size: cover;">
<div class="return-button-container">
    <a href="../index.php" class="return-button">Return to Dashboard</a>
</div>
    <h1 style="display: flex; justify-content: center; align-items: flex-start;"> 
        <img src="../../images/home_page_company_header.png" alt="company header" width="30%" height="20%" > 
       

     
    </h1>
    <!-- ... -->
    <div class="quote-list">
        <input type="text" id="quote-search" placeholder="Search quotes...">
        <div class="quote-header">
            <span class="quote-id-header">Quote ID</span>
            <span class="customer-name-header">Customer Name</span>
            <span class="award-status-header">Award Status</span>
            <!-- ... -->
        </div>
    <?php foreach($quotes as $quote): ?>
        <div class="quote">
            <span class="quote-id"><?= $quote['invoice_id'] ?></span>
            <span class="customer-name"><?= $quote['Customer Name'] ?></span>
            <span class="award-status"><?= $quote['award_status'] ?></span>
          
        </div>
    <?php endforeach; ?>
</div>
<div class="quote-files">
        <!-- Files will be loaded here -->

       
    </div>
    <!-- Your scripts here -->
    <script>
        function confirmDelete(quoteId) {
    if (confirm('Are you sure you want to delete this quote?')) {
        window.location.href = 'delete_quote.php?invoice_id=' + quoteId;
    }
}
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
        var currentQuoteId = null;

$(".quote").click(function() {
    var quoteId = $(this).find(".quote-id").text();
    if (currentQuoteId === quoteId) {
        $(".quote-files").slideUp();
        currentQuoteId = null;
    } else {
        $.ajax({
            url: 'fetch_quote_files.php',
            method: 'POST',
            data: {quoteId:quoteId},
            success: function(data) {
                $(".quote-files").hide().html(data);
                var editButton = '<a href="edit_quote.php?invoice_id=' + quoteId + '" class="btn">Edit Quote</a>';
                var deleteButton = '<a href="#" onclick="confirmDelete(\'' + quoteId + '\')" class="delete-btn">Delete Quote</a>';
                $(".quote-files").append(editButton);
                $(".quote-files").append(deleteButton);
                $(".quote-files").slideDown();
            }   
        });
        currentQuoteId = quoteId;
    }
});
$(document).on('click', '.quote-files .download-link', function(e) {
    e.preventDefault();
    var fileName = $(this).text().replace('Download ', '');
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
</script>
</body>
</html>
