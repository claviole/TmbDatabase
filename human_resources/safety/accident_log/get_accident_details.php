<?php
include '../../../connection.php';

$accidentId = $_GET['id'];

$query = "SELECT * FROM accident_report WHERE accident_id = $accidentId";
$result = mysqli_query($database, $query);
$accident = mysqli_fetch_assoc($result);

$query = "SELECT * FROM accident_files WHERE accident_id = $accidentId";
$result = mysqli_query($database, $query);
$files = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<style>
.view-files-button {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
    /* Add more styles as needed */
}
</style>

<div class="modal-header">
    <h5 class="modal-title" id="accidentDetailsModalLabel">Accident Details</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <p><strong>Accident ID:</strong> <?= $accident['accident_id'] ?></p>
    <p><strong>Employee ID:</strong> <?= $accident['employee_id'] ?></p>
    <p><strong>Non-Employee Name:</strong> <?= $accident['non_employee_name'] ?></p>
    <p><strong>Foreman ID:</strong> <?= $accident['foreman_id'] ?></p>
    <p><strong>Accident Type:</strong> <?= $accident['accident_type'] ?></p>
    <p><strong>Date Added:</strong> <?= $accident['date_added'] ?></p>
    <p><strong>Accident Date:</strong> <?= $accident['accident_date'] ?></p>
    <p><strong>Accident Time:</strong> <?= $accident['accident_time'] ?></p>
    <p><strong>Shift:</strong> <?= $accident['shift'] ?></p>
    <p><strong>Time Sent to Clinic:</strong> <?= $accident['time_sent_to_clinic'] ?></p>
    <p><strong>Date Sent to Clinic:</strong> <?= $accident['date_sent_to_clinic'] ?></p>
    <p><strong>Accident Location:</strong> <?= $accident['accident_location'] ?></p>
    <p><strong>Time of Report:</strong> <?= $accident['time_of_report'] ?></p>
    <p><strong>Shift Start Time:</strong> <?= $accident['shift_start_time'] ?></p>
    <p><strong>Accident Description:</strong> <br> <?= $accident['accident_description'] ?></p>
    <p><strong>Consecutive Days Worked:</strong> <?= $accident['consecutive_days_worked'] ?></p>
    <p><strong>Proper PPE Used:</strong> <?= $accident['proper_ppe_used'] ?></p>
    <p><strong>Improper PPE (if applicable):</strong><br> <?= $accident['proper_ppe_used_explain'] ?></p>
    <p><strong>Procedure Followed:</strong> <?= $accident['procedure_followed'] ?></p>
    <p><strong>Improper Procedure (if applicable):</strong><br> <?= $accident['procedure_followed_explain'] ?></p>
    <p><strong>Potential Severity:</strong> <?= $accident['potential_severity'] ?></p>
    <p><strong>Potential Severity Explained (if applicable):</strong><br> <?= $accident['potential_severity_explain'] ?></p>
    <p><strong>Environmental Impact:</strong> <?= $accident['enverionmental_impact'] ?></p>
    <p><strong>Environmental Impact Explained (if applicable):</strong><br> <?= $accident['enverionmental_impact_explain'] ?></p>
    <p><strong>Prevent Reoccurrence:</strong><br> <?= $accident['prevent_reoccurance'] ?></p>
    <p><strong>Immediate Corrective Action:</strong><br> <?= $accident['immediate_corrective_action'] ?></p>
    <p><strong>IRP Required:</strong> <?= $accident['irp_required'] ?></p>
    <p><strong>IRP Names (if applicable):</strong><br> <?= $accident['irp_names'] ?></p>
    <p><strong>Equip Out of Service:</strong> <?= $accident['equip_out_of_service'] ?></p>
    <p><strong>Equip Out of Service Explained (if applicable):</strong><br> <?= $accident['equip_out_of_service_explain'] ?></p>
</div>
<button type="button" class="btn btn-primary view-files-button" data-toggle="modal" data-target="#filesModal">
    View Files
</button>

<!-- Files modal -->
<div class="modal fade" id="filesModal" tabindex="-1" role="dialog" aria-labelledby="filesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filesModalLabel">Files</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php foreach ($files as $file): ?>
                    <a href="download.php?accidentId=<?= $accidentId ?>&fileName=<?= urlencode($file['file_name']) ?>" class="btn btn-primary"><?= htmlspecialchars($file['file_name']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>