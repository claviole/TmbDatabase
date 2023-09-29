<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Customer</title>
</head>
<body>
    <form action="submit_new_customer.php" method="post">
        <label for="customerName">Customer Name:</label>
        <input type="text" id="customerName" name="customerName">
        <label for="customerAddress">Customer Address:</label>
        <input type="text" id="customerAddress" name="customerAddress">
        <label for="customerCity">Customer City:</label>
        <input type="text" id="customerCity" name="customerCity">
        <label for="customerState">Customer State:</label>
        <input type="text" id="customerState" name="customerState">
        <label for="customerZip">Customer Zip:</label>
        <input type="text" id="customerZip" name="customerZip">
        <label for="customerPhone">Customer Phone:</label>
        <input type="text" id="customerPhone" name="customerPhone">
        <label for="customerEmail">Customer Email:</label>
        <input type="text" id="customerEmail" name="customerEmail">
        <label for="customerContact">Customer Contact:</label>
        <input type="text" id="customerContact" name="customerContact">
        <button type="submit">Submit</button>
    </form>
</body>
</html>