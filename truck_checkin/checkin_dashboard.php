<?php
session_start();

include '../configurations/connection.php'; // Assuming you have a db_connection.php file for database connection
date_default_timezone_set('America/Chicago');

$currentUserLocationCode = $_SESSION['location_code']; // Example

// At the beginning of your PHP script
$status = isset($_GET['status']) ? $_GET['status'] : 'pending';

// Adjust your SQL query based on the status
$sql = "SELECT trucking_id, load_number, phone_number, part_number, truck_number, arrival_date, appointment_date, appointment_time, `status` FROM trucking WHERE location_code = ? AND `status` = ?";
$stmt = $database->prepare($sql);
$stmt->bind_param("ss", $currentUserLocationCode, $status);
$stmt->execute();
$result = $stmt->get_result();

$checkIns = [];
while ($row = $result->fetch_assoc()) {
    $checkIns[] = $row;
}
$checkedInStatus = 'checked in';
$sql = "SELECT trucking_id, bay_location, current_location, load_number, phone_number, part_number, truck_number, arrival_date, appointment_date, appointment_time, `status` FROM trucking WHERE location_code = ? AND `status` = ?";
$stmt = $database->prepare($sql);
$stmt->bind_param("ss", $currentUserLocationCode, $checkedInStatus);
$stmt->execute();
$result = $stmt->get_result();

$checkedInTrucks = [];
while ($row = $result->fetch_assoc()) {
    $checkedInTrucks[] = $row;
}

// Define bay priorities based on the layout
$baysPriority = [
    'Drive 1 Bay 3' => 3,
    'Drive 1 Bay 2' => 2,
    'Drive 1 Bay 1' => 1, // Highest priority for Drive 1
    'Drive 2 Bay 3' => 3,
    'Drive 2 Bay 2' => 2,
    'Drive 2 Bay 1' => 1, // Highest priority for Drive 2
];

// Determine bay availability (assuming each bay can hold 2 trucks)
$baysAvailability = array_fill_keys(array_keys($baysPriority), 2);

foreach ($checkedInTrucks as $truck) {
    if ($truck['current_location']) {
        $baysAvailability[$truck['current_location']]--;
    }
}

// Sort trucks by bay priority, availability, and appointment time
usort($checkedInTrucks, function($a, $b) use ($baysPriority, $baysAvailability) {
    // Check if either truck is awaiting entry, indicating they are not yet assigned to a bay
    $aAwaitingEntry = $a['current_location'] === 'Awaiting Entry';
    $bAwaitingEntry = $b['current_location'] === 'Awaiting Entry';

    // Trucks not awaiting entry (already assigned to a bay) should be sorted to the end
    if (!$aAwaitingEntry && $bAwaitingEntry) {
        return 1; // $a goes after $b
    } elseif ($aAwaitingEntry && !$bAwaitingEntry) {
        return -1; // $a goes before $b
    }

    // If both are awaiting entry or both are not, proceed with existing sorting logic
    $priorityA = $baysPriority[$a['bay_location']] ?? 0;
    $priorityB = $baysPriority[$b['bay_location']] ?? 0;

    if ($priorityA !== $priorityB) {
        return $priorityB - $priorityA;
    }

    $availabilityA = $baysAvailability[$a['bay_location']] ?? 0;
    $availabilityB = $baysAvailability[$b['bay_location']] ?? 0;

    if ($availabilityA !== $availabilityB) {
        return $availabilityB - $availabilityA;
    }

    // Finally, sort by appointment time if all else is equal
    return strtotime($a['appointment_time']) - strtotime($b['appointment_time']);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
    <title>Check In</title>
    <style>
        :root {
    --tmdb-red: #D50000; /* Adjust the red color based on your logo */
    --tmdb-black: #212121; /* A shade of black */
    --tmdb-grey: #F5F5F5; /* A light grey for backgrounds */
}
          .flashing {
        animation: flash .5s linear infinite;
    }
        @keyframes flash {
    0% {background-color: white;}
    50% {background-color: yellow;}
    100% {background-color: white;}
}
    .notification {
        position: absolute;
        top: 0;
        right: 300px;
        padding: 10px;
        background-color: #f2f2f2;
        border: 1px solid #ccc;
        
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

        body {
        background-image: url('<?php echo $backgroundImage;?>');
        background-size: cover;
        background-repeat: no-repeat;
        color: var(--tmdb-black);
    font-family: 'Roboto', sans-serif;
    }

    .header-container {
    text-align: center;
    margin: 20px 0;
}


#tabs button {
    background-color: var(--tmdb-red);
    color: white;
    border: none;
    padding: 10px 20px;
    margin: 0 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

#tabs button:hover {
    background-color: darken(var(--tmdb-red), 10%);
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th, .table td {
    text-align: left;
    padding: 12px;
    border-bottom: 1px solid #ddd; /* Light grey border for separation */
}

.table th {
    background-color: var(--tmdb-red);
    color: white;
}

/* Directly target the table tag within the DataTables wrapper */
.dataTables_wrapper table tbody tr:hover {
    background-color: var(--tmdb-grey) !important; /* Use !important cautiously */
    cursor: pointer; /* Change cursor to indicate clickable */
}
.button {
    background-color: var(--tmdb-red);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: darken(var(--tmdb-red), 10%);
}

@media screen and (max-width: 768px) {
    .table, .button {
        width: 100%;
    }
}
    .header-container {
    display: flex;
    justify-content: center;
    margin: 20px 0; /* Keep your existing margin */
}
/* Example for hover state on table rows */

.dashboard-title {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5); /* Add shadow to make text stand out */
    color: var(--tmdb-red);
    font-size: 3rem; /* Increase size */
    font-weight: bold; /* Make it bold */
    padding: 1rem; /* Add some padding */
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent background for legibility */
    display: inline-block; /* Wrap the background tightly around the text */
}
  /* Add these styles for hover effect on table rows */
  #checkInsTable tbody tr:hover {
        background-color: var(--tmdb-grey); /* Change color on hover */
        cursor: pointer; /* Change cursor to indicate clickable */
    }

    .flashing {
    animation: flash .5s linear infinite;
}

@keyframes flash {
    0% {background-color: white;}
    50% {background-color: yellow;}
    100% {background-color: white;}
}
    </style>
</head>
<body>

<div class="header-container flex justify-center">
    <h1>
        <img src="<?php echo $companyHeaderImage; ?>" alt="company header" class="max-w-30 h-auto">
    </h1>
</div>
<body class="bg-gray-100 font-roboto">

<div class="container mx-auto mt-8">
    

    <div class="mt-8">
        <div class="mb-4">
        <button id="tab1" class="py-2 px-4 text-white bg-red-600 hover:bg-red-700 font-semibold rounded-lg shadow">
    Arrived
</button>
<button id="tab2" class="py-2 px-4 text-white bg-gray-600 hover:bg-gray-700 font-semibold rounded-lg shadow">
    Checked In
</button>
        </div>

        <div id="tab1Content" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <table id="checkInsTable" class="min-w-full divide-y divide-gray-300 bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <thead class="bg-red-600">
        <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
            Load Number
        </th>
        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                Truck Number
            </th>
        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                Part Number
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                Arrival Date
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($checkIns as $checkIn): ?>
                    <tr data-check-in='<?php echo json_encode($checkIn); ?>'>
    <td><?php echo htmlspecialchars($checkIn['load_number']); ?></td>
    <td><?php echo htmlspecialchars($checkIn['truck_number']); ?></td>
    <td><?php echo htmlspecialchars($checkIn['part_number']); ?></td>
    <td><?php echo htmlspecialchars($checkIn['arrival_date']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="tab2Content" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" style="display: none;">
    <table id="checkedInTable" class="min-w-full divide-y divide-gray-300 bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <thead class="bg-red-600">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                    Load Number
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                    Truck Number
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                    Bay
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                    Arrival Date
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                    Appointment Date
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                    Appointment Time
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                    Current Location
                </th>

            
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
    <?php foreach ($checkedInTrucks as $truck): ?>
        <tr data-check-in='<?php echo htmlspecialchars(json_encode($truck), ENT_QUOTES, 'UTF-8'); ?>'>
            <td><?php echo htmlspecialchars($truck['load_number']); ?></td>
            <td><?php echo htmlspecialchars($truck['truck_number']); ?></td>
            <td><?php echo htmlspecialchars($truck['bay_location']); ?></td>
            <td><?php echo htmlspecialchars($truck['arrival_date']); ?></td>
            <td><?php echo htmlspecialchars($truck['appointment_date']); ?></td>
            <td><?php echo htmlspecialchars($truck['appointment_time']); ?></td>
            <td><?php echo htmlspecialchars($truck['current_location']); ?></td>
        </tr>
    <?php endforeach; ?>
</tbody>
        </table>
</div>
</div>
</div>

<div id="infoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" aria-labelledby="modalTitle" aria-modal="true" role="dialog">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                            Trucking Information
                        </h3>
                        <div class="mt-2">
                            <!-- Form fields -->
                            <form id="infoForm">
                                <input type="hidden" id="modal_trucking_id" name="trucking_id">
                                <!-- Repeat this input structure for each field -->
                                <div class="mb-4">
                                    <label for="modal_load_number" class="block text-sm font-medium text-gray-700">Load Number:</label>
                                    <input type="text" id="modal_load_number" name="load_number" class="mt-1 p-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 block w-full">
                                </div>
                                <!-- ... other fields ... -->
                                <div class="mb-4">
                                    <label for="modal_part_number" class="block text-sm font-medium text-gray-700">Part Number:</label>
                                    <input type="text" id="modal_part_number" name="part_number" class="mt-1 p-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 block w-full">
                                </div>

                                <div class="mb-4">
                                    <label for="modal_truck_number" class="block text-sm font-medium text-gray-700">Truck Number:</label>
                                    <input type="text" id="modal_truck_number" name="truck_number" class="mt-1 p-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 block w-full">
                                </div>
                                <div class="mb-4">
                                    <label for="modal_bay_location" class="block text-sm font-medium text-gray-700">Bay Location</label>
                                    <select id="modal_bay_location" name="bay_location" class="mt-1 p-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 block w-full">
                                        <option value="Drive 1 Bay 1">Drive 1 Bay 1</option>
                                        <option value="Drive 1 Bay 2">Drive 1 Bay 2</option>
                                        <option value="Drive 1 Bay 3">Drive 1 Bay 3</option>
                                        <option value="Drive 2 Bay 1">Drive 2 Bay 1</option>
                                        <option value="Drive 2 Bay 2">Drive 2 Bay 2</option>
                                        <option value="Drive 2 Bay 3">Drive 2 Bay 3</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="modal_phone_number" class="block text-sm font-medium text-gray-700">Phone Number:</label>
                                    <input type="text" id="modal_phone_number" name="phone_number" class="mt-1 p-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 block w-full">
                                </div>
                                <div class="mb-4">
                                    <label for="modal_appointment_date" class="block text-sm font-medium text-gray-700">Appointment Date:</label>
                                    <input type="date" id="modal_appointment_date" name="appointment_date" class="mt-1 p-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 block w-full">
                                </div>
                                <div class="mb-4">
                                    <label for="modal_appointment_time" class="block text-sm font-medium text-gray-700">Appointment Time:</label>
                                    <input type="time" id="modal_appointment_time" name="appointment_time" class="mt-1 p-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 block w-full">
                                </div>
                                <div class="mb-4">
    <label for="modal_arrival_date" class="block text-sm font-medium text-gray-700">Arrival Date and Time:</label>
    <input type="datetime-local" id="modal_arrival_date" name="arrival_date" class="mt-1 p-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 block w-full" readonly>
</div>
                               
                                <!-- ... -->
                                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm" onclick="submitModalForm()">
                                        Save Changes
                                    </button>
                                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal()">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <script>
 $(document).ready(function() {
    var table = $('#checkInsTable').DataTable({
    "paging": true,
    "ordering": true,
    "info": true,
    "responsive": true,
    "dom": 'Bfrtip', // Define elements present in the table
    "buttons": [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    "initComplete": function(settings, json) {
        $('.dataTables_wrapper').removeClass('form-inline'); /* Remove default styling */
        $('table.dataTable thead').addClass('bg-red-600 text-white');
        $('table.dataTable').addClass('shadow-lg bg-white rounded-lg');
    }
})
    // Define a custom sorting function for date and time
    jQuery.fn.dataTableExt.oSort['date-time-pre'] = function(a) {
        if (!a) {
            // If the value is undefined, null, or an empty string, return a minimal timestamp
            return 0;
        }
        var ukDatea = a.split(' ');
        // Check if date and time parts exist before splitting to avoid errors
        var date = ukDatea[0] ? ukDatea[0].split('-') : ['1970', '01', '01'];
        var time = ukDatea[1] ? ukDatea[1].split(':') : ['00', '00'];
        return (new Date(date[0], date[1]-1, date[2], time[0], time[1])).getTime();
    };

    var checkedInTable = $('#checkedInTable').DataTable({
        "paging": true,
        "ordering": true,
        "info": true,
        "responsive": true,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "columnDefs": [
            {
                "targets": 3, // Index of the appointment date column (zero-based)
                "type": 'date'
            },
            {
                "targets": 4, // Index of the appointment time column (zero-based)
                "type": 'date-time'
            }
        ],
        "order": [], // Sort by appointment date, then by time
        "initComplete": function(settings, json) {
            $('.dataTables_wrapper').removeClass('form-inline');
            $('table.dataTable thead').addClass('bg-red-600 text-white');
            $('table.dataTable').addClass('shadow-lg bg-white rounded-lg');

            // Find the row with the closest appointment time to the current date and time
            var now = new Date().getTime();
            var closestAppointmentTime = null;
            var closestRow;

            $('#checkedInTable tbody tr').each(function() {
                var currentLocation = $(this).find('td').eq(6).text(); // Assuming the current location is in the 7th column (zero-based index 6)
                if (currentLocation === "Awaiting Entry") {
                    var dateStr = $(this).find('td').eq(3).text(); // Appointment date
                    var timeStr = $(this).find('td').eq(4).text(); // Appointment time
                    var dateTimeStr = dateStr + ' ' + timeStr;
                    var dateTime = new Date(dateTimeStr).getTime();

                    // Calculate the absolute difference from now
                    var timeDiff = Math.abs(dateTime - now);

                    // If this is the first iteration or the time difference is smaller than the current closest, update the closest values
                    if (closestAppointmentTime === null || timeDiff < closestAppointmentTime) {
                        closestAppointmentTime = timeDiff;
                        closestRow = this;
                    }
                }
            });

            // Apply the flashing class to the closest appointment row with "Awaiting Entry" status
            if (closestRow) {
                $(closestRow).addClass('flashing');
            }
        }
    });


   // Function to fetch and update table data
   function updateTable(status) {
    $.ajax({
        type: "GET",
        url: "fetch_checkins.php", // Adjust if necessary to include status in the request
        data: { status: status }, // Pass the status as a parameter to your PHP script
        success: function(response) {
            var checkIns = JSON.parse(response);
            table.clear(); // Clear the table before adding new rows
            $.each(checkIns, function(index, checkIn) {
                var rowNode = table.row.add([
                    checkIn.load_number,
                    checkIn.truck_number,
                    checkIn.part_number,
                    checkIn.arrival_date,
                    // Add or remove fields as necessary based on the status
                ]).draw().node();

                $(rowNode).data('checkIn', checkIn); // Attach the checkIn data to the row
            });
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data: ', xhr, status, error);
        }
    });
}

    // Set interval to update table every 30 seconds
    setInterval(updateTable, 10000); // 10000 milliseconds = 10 seconds

    // Initial fetch of data
    updateTable();
});
        function showCheckInForm(checkInData) {
            // Function to display the form and autofill data
            // You can use SweetAlert2 or a modal to show the form
            console.log(checkInData); // For demonstration
        }

        $('#tab1').on('click', function() {
    $('#tab1Content').show();
    $('#tab2Content').hide();
    $(this).removeClass('bg-gray-600 hover:bg-gray-700').addClass('bg-red-600');
    $('#tab2').removeClass('bg-red-600').addClass('bg-gray-600 hover:bg-gray-700');
});

$('#tab2').on('click', function() {
    $('#tab2Content').show();
    $('#tab1Content').hide();
    $(this).removeClass('bg-gray-600 hover:bg-gray-700').addClass('bg-red-600');
    $('#tab1').removeClass('bg-red-600').addClass('bg-gray-600 hover:bg-gray-700');
});
function showCheckInForm(checkInData) {
    // Convert arrival_date to YYYY-MM-DDTHH:mm format
    var arrivalDateTime = new Date(checkInData.arrival_date.replace(' ', 'T'));
    var year = arrivalDateTime.getFullYear();
    var month = ('0' + (arrivalDateTime.getMonth() + 1)).slice(-2); // getMonth() is zero-based
    var day = ('0' + arrivalDateTime.getDate()).slice(-2);
    var hours = ('0' + arrivalDateTime.getHours()).slice(-2);
    var minutes = ('0' + arrivalDateTime.getMinutes()).slice(-2);
    var formattedDateTime = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;

    // Autofill the form based on `checkInData`
    $('#modal_trucking_id').val(checkInData.trucking_id);
    $('#modal_load_number').val(checkInData.load_number);
    // Repeat for other fields
    $('#modal_part_number').val(checkInData.part_number);
    $('#modal_truck_number').val(checkInData.truck_number);
    $('#modal_phone_number').val(checkInData.phone_number);
    $('#modal_appointment_date').val(checkInData.appointment_date);
    $('#modal_appointment_time').val(checkInData.appointment_time);
    $('#modal_arrival_date').val(formattedDateTime); // Use the formatted date and time
    $('#modal_status').val(checkInData.status);

    // Show the modal
    $('#infoModal').removeClass('hidden');
}
function closeModal() {
    // Hide the modal
    $('#infoModal').addClass('hidden');
}
function submitModalForm() {
    var formData = {
        trucking_id: $('#modal_trucking_id').val(),
        load_number: $('#modal_load_number').val(),
        part_number: $('#modal_part_number').val(),
        truck_number: $('#modal_truck_number').val(),
        phone_number: $('#modal_phone_number').val(),
        appointment_date: $('#modal_appointment_date').val(),
        appointment_time: $('#modal_appointment_time').val(),
        arrival_date: $('#modal_arrival_date').val(),
        bay_location: $('#modal_bay_location').val()
        // Add all the form fields here
        // ...
    };

    $.ajax({
        type: "POST",
        url: "update_trucking.php", // Replace with the path to your update script
        data: formData,
        success: function(response) {
            // Handle success
            console.log('Update successful', response);
            closeModal();
            location.reload();
            // Optionally, refresh the table or update the row in the table
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error('Update failed', xhr, status, error);
        }
    });
}

// Attach the click event to the table rows
$('#checkInsTable tbody').on('click', 'tr', function() {
    var checkInData = $(this).data('checkIn');
    showCheckInForm(checkInData);
});

function closeModal() {
    $('#infoModal').addClass('hidden');
}

// Function to approve truck entry and send SMS
function approveTruckEntry(truckingId, phoneNumber, bayLocation) {
    var formData = new FormData();
formData.append('trucking_id', truckingId);
formData.append('phone_number', phoneNumber);
formData.append('bay_location', bayLocation);

$.ajax({
    type: "POST",
    url: "/configurations/approve_entry.php",
    data: formData,
    processData: false,  // tell jQuery not to process the data
    contentType: false,  // tell jQuery not to set contentType
    success: function(response) {
        location.reload(); // Reload the page to reflect the changes

    },
    error: function(xhr, status, error) {
        console.error('Error', xhr, status, error);
    }
});
}

// Click event for the "Checked In" table rows
$('#checkedInTable tbody').on('click', 'tr', function() {
    var checkInDataAttr = $(this).attr('data-check-in');

    try {
        var checkInData = JSON.parse(checkInDataAttr);

        // Check the current location of the truck first
        if (checkInData.current_location !== "Awaiting Entry") {
            // If the current location is not "Awaiting Entry", ask if they would like to depart the load
            Swal.fire({
                title: 'Depart Load',
                text: "Would you like to depart this load?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, depart it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Call a function to update the current location and status to "Departed"
                    departTruck(checkInData.trucking_id);
                }
            });
        } else {
            // If the current location is "Awaiting Entry", check if there are any trucks waiting to check in
            var waitingTrucksCount = $('#checkInsTable tbody tr:visible').length;

            if (waitingTrucksCount > 0) {
                // If there are trucks waiting to check in, prompt the user
                Swal.fire({
                    title: 'Pending Check-Ins',
                    text: "There are trucks waiting to check in. Please ensure all trucks are checked in before approving more entries.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'View Waiting Trucks',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If the user wants to view waiting trucks, switch to the "Arrived" tab
                        $('#tab1').click(); // Simulate a click on the "Arrived" tab to show the waiting trucks
                    }
                });
            } else {
                // If there are no trucks waiting to check in, proceed with the approval process
                Swal.fire({
                    title: 'Approve Truck Entry',
                    text: `Approve entry for Load Number: ${checkInData.load_number}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Approve Entry'
                }).then((result) => {
                    if (result.isConfirmed) {
                        approveTruckEntry(checkInData.trucking_id, checkInData.phone_number, checkInData.bay_location);
                        location.reload(); // Reload the page to reflect the changes
                    }
                });
            }
        }
    } catch (e) {
        console.error("Error parsing JSON from data attribute:", e);
    }
});
// Function to update the truck's current location and status to "Departed"
function departTruck(truckingId) {
    var formData = new FormData();
    formData.append('trucking_id', truckingId);
    // Since you're setting the status and current_location to 'Departed' in the PHP script,
    // you don't need to append them here unless they might vary.
    // formData.append('current_location', 'Departed');
    // formData.append('status', 'Departed');

    $.ajax({
        type: "POST",
        url: "depart_truck.php", // Make sure this is the correct relative or absolute path to your PHP script
        data: formData,
        processData: false, // Don't process the files
        contentType: false, // Let the browser set the content type for FormData
        success: function(response) {
            console.log('Departure successful', response);
            location.reload(); // Reload the page to reflect the changes
        },
        error: function(xhr, status, error) {
            console.error('Departure failed', xhr, status, error);
        }
    });
}
 // 20000 milliseconds = 20 seconds
    </script>
</body>
</html>