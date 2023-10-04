<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Part</title>
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
<body style="background-image: url('../../images/laser-blanking.jpg'); background-size: cover;">
   
    
    <form action="submit_new_part.php" method="post">
    
    <div class="form-container">
    
        <div>
        <label for="partNumber">Part Number:</label>
        <input type="text" id="partNumber" name="partNumber">
        <label for="partName">Part Name:</label>
        <input type="text" id="partName" name="partName">
        </div>
        <div>
        <label for="mill">Mill:</label>
        <input type="text" id="mill" name="mill">
        <label for="platform">Platform:</label>
        <input type="text" id="platform" name="platform">
        </div>
        <div>
        <label for="type">Type:</label>
        <input type="text" id="type" name="type">
        <label for="surface">Surface:</label>
        <input type="text" id="surface" name="surface">
        </div>
        <div>
        <label for="materialType">Material Type:</label>
        <input type="text" id="materialType" name="materialType">
        <label for="palletType">Pallet Type:</label>
        <input type="text" id="palletType" name="palletType">
        </div>
        <div>
        <label for="palletSize">Pallet Size:</label>
        <input type="text" id="palletSize" name="palletSize">
        <label for="piecesPerLift">Pieces per Lift:</label>
        <input type="number" id="piecesPerLift" name="piecesPerLift">
        </div>
        <div>
        <label for="stacksPerSkid">Stacks per Skid:</label>
        <input type="number" id="stacksPerSkid" name="stacksPerSkid">
        <label for="skidsPerTruck">Skids per Truck:</label>
        <input type="number" id="skidsPerTruck" name="skidsPerTruck">
        </div>
        <div>
        <label for="scrapConsumption">Scrap Consumption:</label>
        <input type="number" id="scrapConsumption" name="scrapConsumption" step="0.01">
        </div>
        <button type="submit">Submit</button>
    </div>
    </form>
    <a href="../index.php" class="return-button">Return to Dashboard</a>
    
    
</body>
</html>