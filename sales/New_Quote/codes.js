var elements=[];
var data = {
    customerId: '',
    customer: '',
    invoiceDate: '',
    parts: [],
    invoice_author: ''
};
user=window.user;


function submitNewPartForm(formData) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'submit_new_part.php', // your form submission URL
            type: 'POST', // or GET
            data: formData, // use the passed form data
            success: function(response) {
                resolve(response);
            },
            error: function(error) {
                reject(error);
            }
        });
    });
}

async function addPart() {
    try {
       

    elements=[];
    var lineProducedName= $("#line_produced option:selected").text();
   
    if (window.partData) {
    var partName = document.getElementById('partName').value;
    // Get values from input fields
    var invoiceId = document.getElementById('invoice_id').value;
    var partNumber = document.getElementById('part').value
    var partName= document.getElementById('partName').value;
    var volume = document.getElementById('volume').value;
    var width = parseFloat(document.getElementById('width').value);
    var pitch = parseFloat(document.getElementById('pitch').value);
    var gauge = parseFloat(document.getElementById('gauge').value);
    var numOutputs = document.getElementById('# Out').value;
    var lineProduced = document.getElementById('line_produced').value;
    var uptime = document.getElementById('uptime').value;
    var partsPerHour = document.getElementById('pph').value;
    var widthIN = width/25.4;
    var pitchIN= pitch/25.4;
    var gaugeIN= gauge/25.4;
    var hourlyRate;
    var hours_to_run=volume/partsPerHour;
    var Density=document.getElementById('Density').value;
    var blankWeight=(gauge*width*pitch*Density)*2.20462;
    var blankWeightKg=blankWeight/2.20462;
    var scrapLbs = partData['Scrap Consumption']*blankWeight;
    var scrapKg = scrapLbs/2.20462;
    var pcsWeight= blankWeight-scrapLbs;
    var pcsWeightKg= pcsWeight/2.20462;
    var palletWeight;
    var palletCost;
    var pallet_type=partData[`pallet_type`];
    var pallet_size=partData[`pallet_size`];
    var pallet_uses=document.getElementById('pallet_uses').value;
    var palletWeight=document.getElementById('palletWeight').value;
    var palletCost=document.getElementById('palletCost').value;
    var wash_and_lube_choice=document.getElementById('wash_and_lube').checked;
    var steel_or_aluminum=document.getElementById('steel_or_aluminum').value;
    var material_cost
    var wash_and_lube;

    if(wash_and_lube_choice==true)
    {
        wash_and_lube=parseFloat(50*hours_to_run).toFixed(3);
    }
    else
    {
        wash_and_lube=parseFloat(0).toFixed(3);
    }
   
    
    if(steel_or_aluminum=='steel')
    {
        material_cost=parseFloat(1.0589*blankWeight).toFixed(3);
    }
    else
    {
        material_cost=parseFloat(2.20462*blankWeight).toFixed(3);
    }
    var material_markup_percent=(document.getElementById('material_markup_percent').value)/100;
    var material_cost_markup=parseFloat(material_cost*material_markup_percent).toFixed(3);
  
 var pcsPerLift= partData['Pieces per Lift'];
 var stacksPerSkid=partData['Stacks per Skid'];
 var pcsPerSkid= pcsPerLift*stacksPerSkid;
 var liftWeight=(pcsPerLift*pcsWeight*stacksPerSkid)+palletWeight;
 var stackHeight=gaugeIN*pcsPerLift;
 var skidsPerTruck=partData['Skids per Truck'];
 var pcsPerTruck=skidsPerTruck*pcsPerSkid;
 var weightPerTruck = (skidsPerTruck * liftWeight).toFixed(3);
var annualTruckLoads=volume/pcsPerTruck;
var UseSkidPcs= pcsPerSkid*pallet_uses;
var skidCostPerPc=palletCost/UseSkidPcs;
if(lineProduced==11)
{
    hourlyRate=950;

}

var blankingPerPieceCost=hourlyRate/partsPerHour;
var packagingPerPieceCost=(12.50/pcsPerSkid)+skidCostPerPc;
packagingPerPieceCost=packagingPerPieceCost.toFixed(3);
var proccessingAndPackagingCost=blankingPerPieceCost+packagingPerPieceCost;
var freightPerPiece=3200/pcsPerTruck;
var totalPerPiece = (parseFloat(blankingPerPieceCost) + parseFloat(material_cost)+ parseFloat(packagingPerPieceCost) + parseFloat(freightPerPiece)+parseFloat(wash_and_lube)+parseFloat(material_cost_markup)).toFixed(3);
var blanksPerMinute=partsPerHour/60;


elements[0]=invoiceId;
elements[1]=partNumber;
elements[2]=partName;
elements[3]=partData[`Material Type`];
elements[4]=numOutputs;
elements[5]=volume;
elements[6]=width;
elements[7]=widthIN;
elements[8]=pitch;
elements[9]=pitchIN;
elements[10]=gauge;
elements[11]=gaugeIN;
elements[12]=blankWeight;
elements[13]=blankWeightKg;
elements[14]=partData[`Scrap Consumption`];
elements[15]=pcsWeight;
elements[16]=pcsWeightKg;
elements[17]=scrapKg;
elements[18]=scrapLbs;
elements[19]=pallet_type;
elements[20]=pallet_size;
elements[21]=palletWeight;
elements[22]=pcsPerLift;
elements[23]=stacksPerSkid;
elements[24]=pcsPerSkid;
elements[25]=liftWeight;
elements[26]=stackHeight;
elements[27]=skidsPerTruck;
elements[28]=pcsPerTruck;
elements[29]=weightPerTruck;
elements[30]=annualTruckLoads;
elements[31]=UseSkidPcs;
elements[32]=skidCostPerPc;
elements[33]=lineProduced;
elements[34]=partsPerHour;
elements[35]=uptime;
elements[36]=blankingPerPieceCost;
elements[37]=packagingPerPieceCost;
elements[38]=freightPerPiece;
elements[39]=totalPerPiece;
elements[40]=Density;
elements[41]=wash_and_lube;
elements[42]=material_cost;
elements[43]=material_markup_percent;
elements[44]=material_cost_markup;

var part = {
    invoiceId: elements[0],
    partNumber: elements[1],
    partName: elements[2],
    materialType: elements[3],
    numOutputs: elements[4],
    volume: elements[5],
    width: elements[6],
    widthIN: elements[7],
    pitch: elements[8],
    pitchIN: elements[9],
    gauge: elements[10],
    gaugeIN: elements[11],
    blankWeight: elements[12],
    blankWeightKg: elements[13],
    scrapConsumption: elements[14],
    pcsWeight: elements[15],
    pcsWeightKg: elements[16],
    scrapLbsInKg: elements[17],
    scrapLbs: elements[18],
    palletType: elements[19],
    palletSize: elements[20],
    palletWeight: elements[21],
    pcsPerLift: elements[22],
    stacksPerSkid: elements[23],
    pcsPerSkid: elements[24],
    liftWeight: elements[25],
    stackHeight: elements[26],
    skidsPerTruck: elements[27],
    pcsPerTruck: elements[28],
    weightPerTruck: elements[29],
    annualTruckLoads: elements[30],
    UseSkidPcs: elements[31],
    skidCostPerPcs: elements[32],
    lineProduced: elements[33],
    partsPerHour: elements[34],
    uptime: elements[35],
    blankingPerPieceCost: elements[36],
    packagingPerPieceCost: elements[37],
    freightPerPiece: elements[38],
    totalPerPiece: elements[39],
    Density: elements[40],
    wash_and_lube: elements[41],
    material_cost: elements[42],
    material_markup_percent: elements[43],
    material_cost_markup: elements[44]
    


}
data.parts.push(part);

// Create new table row and cells
var row = document.createElement('tr');

// Check if the table already has a header row
if (document.getElementById('parts_table').rows.length === 0) {
    var headers = ['Part Number', 'Volume', 'Width', 'Pitch', 'Gauge','Density', '# Out', 'Line Produced', 'Uptime', 'Parts Per Hour', 'Pcs Per Skid', 'Skids Per Truck','Weight Per Truck', 'Blanking Per Piece Cost', 'Packaging Per Piece Cost', 'Total Per Piece'];
    var headerRow = document.createElement('tr');
    for (var i = 0; i < headers.length; i++) {
        var headerCell = document.createElement('th');
        headerCell.textContent = headers[i];
        headerRow.appendChild(headerCell);
    }
    document.getElementById('parts_table').appendChild(headerRow);
}

var cells = [ partNumber, volume, width, pitch, gauge, Density, numOutputs,lineProducedName, uptime, partsPerHour,pcsPerSkid,skidsPerTruck,weightPerTruck ,blankingPerPieceCost,packagingPerPieceCost,totalPerPiece,];
for (var i = 0; i < cells.length; i++) {
    var cell = document.createElement('td');
    cell.textContent = cells[i];
    row.appendChild(cell);
}

// Append new row to table
document.getElementById('parts_table').appendChild(row);

    return Promise.resolve();
}
else
{
    console.error('partData is undefined');
}
elements[0]=invoiceId;

}catch (error) {
    // Handle form submission error
    console.error('Form submission failed:', error);
}
}

window.onload = function(){
    
document.getElementById('submit-button').addEventListener('click', function(event) {
    event.preventDefault();
    submitInvoice();
    fetch('Admin/fetch_invoice.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ invoiceId: invoiceId }) // Send the invoice ID to the server
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('invoice').innerText = JSON.stringify(data.invoice, null, 2);
        document.getElementById('parts').innerText = JSON.stringify(data.parts, null, 2);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
    
})};



function submitInvoice() {
    var selectedCustomer = $('#customer').val();
   
    data.customer= selectedCustomer,
    data.invoiceDate= new Date().toISOString(),
    data.parts= data.parts
    data.customerId = $('#customer_id').val();
    data.invoice_author = user;
    console.log('Data: ',data);
        
    fetch('submit_invoice.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    }) 
    .then(response => response.json())
    .then(data => {
        // Handle the response data
        console.log(data);
        // Clear all input fields
        $('input[type="text"], input[type="number"], select').val('');
        // Clear the parts table
        $("#parts_table").find("tr:gt(0)").remove();
        // Increment the invoice number
        var currentInvoiceNumber = parseInt($('#invoice_number').val());
        $('#invoice_number').val(currentInvoiceNumber + 1);
        // If the invoice was successfully submitted, generate the PDF
        if (data.success) {
            window.location.href = 'generate_invoice_pdf.php?invoice_id=' + currentInvoiceNumber;
        }
    })
    .catch(error => {
        // Handle the error
        console.error('There has been a problem with your fetch operation:', error);
    });
}

window.onload = function(){
    document.getElementById('submit-button').addEventListener('click', function(event) {
        event.preventDefault();
        submitInvoice();
    });
};

function clearPartInputs() {
    document.getElementById('volume').value = '';
    document.getElementById('width').value = '';
    document.getElementById('pitch').value = '';
    document.getElementById('Density').value = '';
    document.getElementById('gauge').value = '';
    document.getElementById('# Out').value = '';
    document.getElementById('line_produced').value = '';
    document.getElementById('uptime').value = '';
    document.getElementById('pph').value = '';
    document.getElementById('partName').value = '';
    document.getElementById('partNumber').value = '';
    document.getElementById('mill').value = '';
    document.getElementById('platform').value = '';
    document.getElementById('type').value = '';
    document.getElementById('surface').value = '';
    document.getElementById('materialType').value = '';
    document.getElementById('palletType').value = '';
    document.getElementById('palletSize').value = '';
    document.getElementById('piecesPerLift').value = '';
    document.getElementById('stacksPerSkid').value = '';
    document.getElementById('skidsPerTruck').value = '';
    document.getElementById('scrapConsumption').value = '';
    document.getElementById('palletWeight').value = '';
    document.getElementById('palletCost').value = '';
    document.getElementById('wash_and_lube').value = '';
    document.getElementById('pallet_uses').value = '';
    document.getElementById('steel_or_aluminum').value = '';
    document.getElementById('material_markup_percent').value = '';
}

window.onload = function(){
    
    document.getElementById('submit-button').addEventListener('click', function(event) {
        event.preventDefault();
        submitInvoice();
    });
};