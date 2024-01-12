<?php
session_start();
include '../../connection.php';

// Fetch quotes for dropdown
$result = $database->query("SELECT `invoice_id`, `Customer Name`,`award_status`,`award_total` FROM `invoice` WHERE `Customer Name` IS NOT NULL AND `Customer Name` <> '' GROUP BY `invoice_id`, `Customer Name`");
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

.quote-id-header, .customer-name-header,.award-status-header,.award-total-header {
    font-weight: bold;
}
.award-total {
    color: #008000; /* This is a green color similar to a dollar bill */
}
.quote-id-header {
    max-width: 50%; /* Adjust as needed */
    margin-left: 0px;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.award-total-header {
    max-width: 50%; /* Adjust as needed */
    margin-left: 275px;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.customer-name-header{
    max-width: 50%;
    margin-left: 350px;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.award-status-header{
    max-width: 50%;
    margin-left: 200px;
    text-overflow: ellipsis;
    white-space: nowrap;

}
.quote{
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr; /* Adjust as needed */
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
#quote-search {
    display: block;
    width: 20%;
    margin: 20px auto;

    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s ease;

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
     <div>
    <input type="text" id="quote-search" placeholder="Search quotes...">.
    </div>
    <div class="quote-list">
       
        <div class="quote-header">
            <span class="quote-id-header">Quote ID</span>
            <span class="customer-name-header">Customer Name</span>
            <span class="award-status-header">Award Status</span>
            <span class="award-total-header">Quote Total</span>
            <!-- ... -->
        </div>
    <?php foreach($quotes as $quote): ?>
        <div class="quote">
            <span class="quote-id"><?= $quote['invoice_id'] ?></span>
            <span class="customer-name"><?= $quote['Customer Name'] ?></span>
            <span class="award-status"><?= $quote['award_status'] ?></span>
            <span class="award-total"><?= '$'.number_format($quote['award_total']) ?></span>

          
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
                var generateExcelButton = '<a href="#" onclick="openExcelItemsPopup(\'' + quoteId + '\')" class="btn">Generate Excel</a>';
                $(".quote-files").append(deleteButton);
                $(".quote-files").append(generateExcelButton);
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
function openExcelItemsPopup(quoteId) {
    Swal.fire({
        title: 'Select Excel Items:',
        html: generateExcelItemsFormHTML(quoteId), // Generate the HTML for the form
        focusConfirm: false,
        preConfirm: () => {
            // Get selected item names
            var selectedItemNames = Array.from(document.querySelectorAll('input[name="excel-item"]:checked')).map(function(checkbox) {
                return checkbox.value;
            });
            return selectedItemNames;
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            var invoiceId = quoteId;
            // Send selected item names to the server
            $.ajax({
        url: 'generate_excel.php',
        method: 'POST',
        data: { itemNames: result.value,
            invoice_id: quoteId
        },
                 success: function(response) {
                    // Parse the JSON response
                     var data = JSON.parse(response);

                     // Check if there was an error
                    if (data.error) {
                    // If there was an error, show an error message
                        Swal.fire('Error', data.message, 'error');
                    }   
                    else 
                    {
                    // If there was no error, show the download prompt
                    Swal.fire({
                    title: 'Download File',
                    text: "Would you like to download the file?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, download it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "../quote_approvals/download.php?quoteId=" + encodeURIComponent(data.invoice_id) + "&file_name=" + encodeURIComponent(data.filename);
                setTimeout(function(){ // Delay for file download initiation
            location.reload(); // Refresh the page
        }, 2000); // Delay for 5 seconds
               
            }
        });
    }
}
            });
        }
    });
}
var presets = {
    'Ford': ['supplier_name','Part#','Part Name','Material Type','Mill','Platform','Surface','Volume','Gauge(mm)','Width(mm)','Pitch(mm)','Blank Weight(kg)','Pallet Type','cost_per_kg','total_steel_cost(kg)','Blanking per piece cost','freight per piece cost','Packaging Per Piece Cost','material_cost_markup','Total Cost per Piece'],
    'Rivian': ['Material Type', 'Volume', 'Width(mm)'],
    'Thai Summit':['Part#', 'Part Name','blank_die?','Type','Gauge(mm)','nom?','Width(mm)','Pitch(mm)','trap','Gauge(in)','Pitch(in)','Blank Weight(lb)','parts_per_blank','blanks_per_mt','Surface','Scrap Consumption','Blanking per piece cost','freight per piece cost','Total Cost per Piece']
    // ... add more presets here ...
};
function applyPreset() {
    var preset = document.getElementById("preset").value;
    if (preset) {
        // Uncheck all checkboxes inside the form
        document.querySelectorAll('#excel-items-form input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.checked = false;
        });

        // Check the checkboxes for the selected preset
        presets[preset].forEach(function(item) {
            document.getElementById(item).checked = true;
        });
    }
}

function generateExcelItemsFormHTML(quoteId) {
    var items;
    if (quoteId.includes('QuickQuote')) {
        items = ['Part#', 'Blank Weight(lb)', 'Pcs Weight(lb)', 'ship_to_location', 'Blanking per piece cost', 'Packaging Per Piece Cost', 'freight per piece cost','material_cost'];
    } else {
        items =[
    'supplier_name',    
    'Part#',
    'Part Name',
    'model_year',
    'Material Type',
    'Mill',
    'Platform',
    'Volume',
    'Width(mm)',
    'width(in)',
    'Pitch(mm)',
    'Pitch(in)',
    'Gauge(mm)',
    'Gauge(in)',
    'Density',
    'nom?',
    'trap',
    'Type',
    'blank_die?',
    'Blank Weight(kg)',
    'Blank Weight(lb)',
    'Scrap Consumption',
    'Pcs Weight(kg)',
    'Pcs Weight(lb)',
    'Scrap Weight(kg)',
    'Scrap Weight(lb)',
    'parts_per_blank',
    'blanks_per_mt',
    'blanks_per_ton',
    'Surface',
    'Pallet Type',
    'Pallet Size',
    'Pcs per Lift',
    'Stacks per Skid',
    'Pcs per Skid',
    'Lift Weight+Skid Weight(lb)',
    'Skids per Truck',
    'Pieces per Truck',
    'Truck Weight(lb)',
    'Annual Truckloads',
    'UseSkidPcs',
    'Skid cost per piece',
    'Line Produced on',
    'PPH',
    'Uptime',
    'cost_per_lb',
    'cost_per_kg',
    'total_steel_cost(kg)',
    'total_steel_cost(lb)',
    'Blanking per piece cost',
    'Packaging Per Piece Cost',
    'freight per piece cost',
    'material_cost',
    'material_markup_percent',
    'material_cost_markup',
    'palletCost',
    'Total Cost per Piece'
        ];
    }


    var html = '<form id="excel-items-form" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; max-height: 400px; overflow-y: auto; padding: 10px;">';

    // Add dropdown for presets
    html += '<div style="grid-column: span 2; text-align: center;">';
    html += '<select id="preset" onchange="applyPreset()" style="width: 70%;">';
    html += '<option value="">Select a preset</option>';
    for (var preset in presets) {
        html += '<option value="' + preset + '">' + preset + '</option>';
    }
    html += '</select>';
    html += '</div>';

    items.forEach(function(item) {
        html += '<div style="padding: 5px; border: 1px solid #ccc; border-radius: 5px; margin: 5px;">';
        html += '<input type="checkbox" id="' + item + '" name="excel-item" value="' + item + '">';
        html += '<label for="' + item + '" style="margin-left: 5px;">' + item + '</label>';
        html += '</div>';
    });

    html += '</form>';

    return html;
}

</script>
</body>
</html>
