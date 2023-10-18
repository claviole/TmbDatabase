var elements=[];
var data = {
    customerId: '',
    customer: '',
    invoiceDate: '',
    parts: [],
    invoice_author: '',
    contingencies: ''
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
    var volume = document.getElementById('volume').value;
    var width = parseFloat(document.getElementById('width').value);
    var pitch = parseFloat(document.getElementById('pitch').value);
    var gauge = parseFloat(document.getElementById('gauge').value);
    var numOutputs = document.getElementById('# Out').value;
    var lineProduced = document.getElementById('line_produced').value;
    var uptime = document.getElementById('uptime').value/100;
    var partsPerHour = document.getElementById('pph').value*uptime;
    var widthIN = width/25.4;
    var pitchIN= pitch/25.4;
    var gaugeIN= gauge/25.4;
    var hourlyRate;
    var hours_to_run=volume/partsPerHour;
    var Density=parseFloat(document.getElementById('Density').value);
    var blankWeight=parseFloat(gaugeIN*widthIN*pitchIN*Density).toFixed(3);
    var blankWeightKg= parseFloat(blankWeight/2.20462).toFixed(3);
    var scrapConsumption = document.getElementById('scrapConsumption').value;
    var scrap_multiply= scrapConsumption/100;
    var scrapLbs = scrap_multiply*blankWeight;
    var scrapKg = scrapLbs/2.20462;
    var pcsWeight= blankWeight-scrapLbs;
    var pcsWeightKg= pcsWeight/2.20462;
    var palletWeight;
    var palletCost;
    var pallet_type=document.getElementById('palletType').value;
    var pallet_size=document.getElementById('palletSize').value;
    var pallet_uses=document.getElementById('pallet_uses').value;
    palletWeight=document.getElementById('palletWeight').value;
    var palletWeightkg=palletWeight/2.20462;
    palletCost=document.getElementById('palletCost').value;
    var wash_and_lube_choice=document.getElementById('wash_and_lube').checked;
    var steel_or_aluminum=document.getElementById('steel_or_aluminum').value;
    var material_cost
    var wash_and_lube;
    var nom = document.getElementById('nom?').value;
    var blank_die=document.getElementById('blank_die?').value;
    var model_year=document.getElementById('model_year').value;
    var trap=document.getElementById('trap').value;
    var total_freight=document.getElementById('freight').value;
    var material_type=document.getElementById('materialType').value;
    var cost_per_lb=document.getElementById('cost_per_lb').value;

    if(wash_and_lube_choice==true)
    {
        wash_and_lube=parseFloat(50*hours_to_run).toFixed(3);
    }
    else
    {
        wash_and_lube=parseFloat(0).toFixed(3);
    }
   
    
 
    
    material_cost=parseFloat(cost_per_lb*blankWeight).toFixed(3);

  
    var material_markup_percent=(document.getElementById('material_markup_percent').value)/100;
    var material_cost_markup=parseFloat((material_cost*material_markup_percent)+material_cost).toFixed(3);
  
 var pcsPerLift= document.getElementById('piecesPerLift').value;
 var stacksPerSkid=document.getElementById('stacksPerSkid').value;
 var pcsPerSkid= pcsPerLift*stacksPerSkid;
 var liftWeight=(pcsPerLift*pcsWeight*stacksPerSkid)+palletWeight;
 var stackHeight=gaugeIN*pcsPerLift;
 var skidsPerTruck=document.getElementById('skidsPerTruck').value;
 var pcsPerTruck=skidsPerTruck*pcsPerSkid;
 var weightPerTruck = (skidsPerTruck * liftWeight).toFixed(3);
var annualTruckLoads=volume/pcsPerTruck;
var UseSkidPcs= pcsPerSkid*pallet_uses;
var skidCostPerPc=palletCost/UseSkidPcs;
if(lineProduced==1|| lineProduced==2 || lineProduced==3 || lineProduced==4 || lineProduced==5 || lineProduced==6 || lineProduced==7 || lineProduced==8 || lineProduced==9 || lineProduced==10)
{
    hourlyRate=850;
}


else if (lineProduced==11 || lineProduced==12 || lineProduced==13 || lineProduced==14 )
{
    hourlyRate=900;

}  


else if (lineProduced==15|| lineProduced==16 || lineProduced==17 || lineProduced==18 )
{
    hourlyRate=850;

}
else if(lineProduced==19 || lineProduced==20)
{
    hourlyRate=950;
}
else if(lineProduced==21)
{
    hourlyRate=350;
}


var blankingPerPieceCost=hourlyRate/partsPerHour;
var packagingPerPieceCost= parseFloat((palletCost/pcsPerSkid)+(25/pcsPerSkid)).toFixed(3);
var proccessingAndPackagingCost=blankingPerPieceCost+packagingPerPieceCost;
var freightPerPiece=total_freight/pcsPerTruck;
var totalPerPiece = (parseFloat(blankingPerPieceCost) + parseFloat(packagingPerPieceCost) + parseFloat(freightPerPiece)+(parseFloat(wash_and_lube)/pcsPerTruck)+parseFloat(material_cost_markup)).toFixed(3);
var blanksPerMinute=partsPerHour/60;


elements[0]=invoiceId;
elements[1]=partNumber;
elements[2]=partName;
elements[3]=material_type;
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
elements[14]=scrapConsumption;
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
elements[45]=nom;
elements[46]=blank_die;
elements[47]=model_year;
elements[48]=trap;

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
    material_cost_markup: elements[44],
    nom: elements[45],
    blank_die: elements[46],
    model_year: elements[47],
    trap: elements[48]

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



async function submitInvoice() {
    var selectedCustomer = $('#customer').val();
   
    data.customer = selectedCustomer;
    data.invoiceDate = new Date().toISOString();
    data.parts = data.parts;
    data.customerId = $('#customer_id').val();
    data.invoice_author = user;
    data.pdf_format = $('#pdf_format').val();
    data.contingencies = $('#contingencies').val();
    console.log('Data: ', data);
        
    // First, send the JSON data
    let response = await fetch('submit_invoice.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });

  // After the fetch call to submit_invoice.php
  let result = await response.json();

  // Get the invoice_id from the JSON response
 

    // Check if the first request was successful
    if (result.success) {
        console.log('Result was a success');
        // Then, send the files
        let formData = new FormData();
        let files = document.querySelector('#invoice_files').files;
        for (let i = 0; i < files.length; i++) {
            formData.append('invoice_files[]', files[i]);
        }
        var currentInvoiceNumber = parseInt($('#invoice_number').val());
        // Add the invoice_id to the form data
        formData.append('invoice_id', currentInvoiceNumber);

        response = await fetch('submit_files.php', {
            method: 'POST',
            body: formData
        });

        result = await response.json();

        // Check if the file upload was successful
        if (result.success) {
            // Handle success
            console.log("Files uploaded successfully");
            // Clear all input fields
            $('input[type="text"], input[type="number"], select').val('');
            // Clear the parts table
            $("#parts_table").find("tr:gt(0)").remove();
            // Increment the invoice number
            var currentInvoiceNumber = parseInt($('#invoice_number').val());
            $('#invoice_number').val(currentInvoiceNumber + 1);
            // If the invoice was successfully submitted, generate the PDF
            if (data.pdf_format == 'ford') {
                window.location.href = 'generate_ford_quote_pdf.php?invoice_id=' + currentInvoiceNumber;
            } else if (data.pdf_format == 'thai_summit') {
                window.location.href = 'generate_thai_summit_quote.php?invoice_id=' + currentInvoiceNumber;
            }
        } else {
            // Handle error
            console.error("Error uploading files: " + result.error);
        }
    } else {
        // Handle error
        console.error("Error submitting invoice: " + result.error);
    }
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
    document.getElementById('nom?').value = '';
    document.getElementById('blank_die?').value = '';
    document.getElementById('model_year').value = '';
    document.getElementById('trap').value = '';
}

window.onload = function(){
    
    document.getElementById('submit-button').addEventListener('click', function(event) {
        event.preventDefault();
        submitInvoice();
    });
};