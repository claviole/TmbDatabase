<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Part</title>
</head>
<body>
    <form action="submit_new_part.php" method="post">
        <label for="partNumber">Part Number:</label>
        <input type="text" id="partNumber" name="partNumber">
        <label for="partName">Part Name:</label>
        <input type="text" id="partName" name="partName">
        <label for="mill">Mill:</label>
        <input type="text" id="mill" name="mill">
        <label for="platform">Platform:</label>
        <input type="text" id="platform" name="platform">
        <label for="type">Type:</label>
        <input type="text" id="type" name="type">
        <label for="surface">Surface:</label>
        <input type="text" id="surface" name="surface">
        <label for="materialType">Material Type:</label>
        <input type="text" id="materialType" name="materialType">
        <label for="palletType">Pallet Type:</label>
        <input type="text" id="palletType" name="palletType">
        <label for="palletSize">Pallet Size:</label>
        <input type="text" id="palletSize" name="palletSize">
        <label for="piecesPerLift">Pieces per Lift:</label>
        <input type="number" id="piecesPerLift" name="piecesPerLift">
        <label for="stacksPerSkid">Stacks per Skid:</label>
        <input type="number" id="stacksPerSkid" name="stacksPerSkid">
        <label for="skidsPerTruck">Skids per Truck:</label>
        <input type="number" id="skidsPerTruck" name="skidsPerTruck">
        <label for="scrapConsumption">Scrap Consumption:</label>
        <input type="float" id="scrapConsumption" name="scrapConsumption" step="0.01">
        <button type="submit">Submit</button>
    </form>
</body>
</html>