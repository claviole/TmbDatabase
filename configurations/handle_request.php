<?php
include 'connection.php'; // Adjust the path as necessary
include 'send_expense.php'; // Adjust the path as necessary

// Function to fetch user email by username
function getUserEmailByUsername($username) {
    global $database; // Make sure this is your database connection variable
    $stmt = $database->prepare("SELECT email FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['email'];
    }
    return null;
}

// Function to update the approval status
function updateApprovalStatus($expenseId, $status) {
    global $database;
    $stmt = $database->prepare("UPDATE purchase_requests SET approval_status = ? WHERE expense_id = ?");
    $stmt->bind_param("si", $status, $expenseId);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $expenseId = $_POST['expenseId'];
    $username = $_POST['username'];
    $email = getUserEmailByUsername($username);
    $reason = $_POST['reason'] ?? '';
    $formHtml = $_POST['formHtml'] ?? ''; // Extract form HTML from POST data

    if (!$email) {
        echo json_encode(['status' => 'error', 'message' => 'User email not found.']);
        exit;
    }

    $isApproved = $action == 'approve';
    $status = $isApproved ? 'approved' : 'denied';
    $isSent = sendApprovalDenialEmail($email, $isApproved, $reason, $formHtml); // Pass form HTML to the function

    if ($isSent && updateApprovalStatus($expenseId, $status)) {
        echo json_encode(['status' => 'success', 'message' => 'Email sent and status updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send email or update status.']);
    }
    exit;
}