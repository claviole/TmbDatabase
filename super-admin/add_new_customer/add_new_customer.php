<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="codes.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <title>Add New Customer</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
}

.form-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-bottom: 20px;
    border-radius: 20px;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.form-container div {
    flex: 1 0 45%; /* This will make each div take up approximately 45% of the container's width, allowing for some space in between */
    border: 1px solid #ccc;
    padding: 10px;
    margin: 10px;
    box-sizing: border-box; /* This ensures that the padding and border are included in the element's total width and height */
}
form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    font-size: 14px;
}

form input[type="text"], form input[type="number"] {
    width: 90%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    color: #333;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
    margin-bottom: 15px;
}

form button {
    background-color: #1B145D;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.3s ease;
}

form button:hover {
    background-color: #111;
}
.return-button {
    display: inline-block;
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 10px 20px;
    background-color: #1B145D;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.return-button:hover {
    background-color: #111;
}
</style>
</head>
<body style="background-image: url('../../images/steel_coils.jpg'); background-size: cover;">
<form id="customerForm" action="submit_new_customer.php" method="post">
    <div class="form-container">
        <div>
        <label for="customerName">Customer Name:</label>
        <input type="text" id="customerName" name="customerName">
        <label for="customerAddress">Customer Address:</label>
        <input type="text" id="customerAddress" name="customerAddress">
        </div>
        <div>
        <label for="customerCity">Customer City:</label>
        <input type="text" id="customerCity" name="customerCity">
        <label for="customerState">Customer State:</label>
        <input type="text" id="customerState" name="customerState">
        </div>
        <div>
        <label for="customerZip">Customer Zip:</label>
        <input type="text" id="customerZip" name="customerZip">
        <label for="customerPhone">Customer Phone:</label>
        <input type="text" id="customerPhone" name="customerPhone">
        </div>
        <div>
        <label for="customerEmail">Customer Email:</label>
        <input type="text" id="customerEmail" name="customerEmail">
        <label for="customerContact">Customer Contact:</label>
        <input type="text" id="customerContact" name="customerContact">
        </div>
        <div>
            <button type="submit">Submit</button>
        </div>
    </div>
    </form>
    <a href="../index.php" class="return-button">Return to Dashboard</a>
</body>
<script>
    $(document).ready(function() {
    $('#customerForm').on('submit', function(e) {
        // Prevent the form from being submitted
        e.preventDefault();

        // Check if all fields are filled
        var allFilled = true;
        $('#customerForm input[type="text"]').each(function() {
            if ($(this).val() === '') {
                allFilled = false;
            }
        });

        // If all fields are filled, submit the form
        if (allFilled) {
            this.submit();
        } else {
            // If not, show a SweetAlert2 message
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill out all fields before submitting!',
            });
        }
    });
});
</script>
</html>