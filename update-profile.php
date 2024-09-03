<?php
session_start(); // Start the session

// Disable HTML error output
ini_set('display_errors', 0); // Don't display errors on the output
ini_set('log_errors', 1); // Log errors to the server log
error_reporting(E_ALL); // Report all types of errors

// Set CORS headers to allow requests from any origin (adjust as needed)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');



// Ensure the script only processes POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $company = $_POST['company'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $sponsor_code = $_POST['sponsor_code'];

    // Retrieve access token from session or other storage
    $access_token = $_POST['access_token'];
    

    // Check if access token is present
    if (empty($access_token)) {
        echo json_encode(['status' => 'error', 'message' => 'Access token missing']);
        exit; // Stop script execution
    }

    // Prepare the data for the API request in JSON format
    $data = json_encode([
        'name' => $name,
        'email' => $email,
        'company' => $company,
        'state' => $state,
        'city' => $city,
        'sponsor_code' => $sponsor_code
    ]);

    // Define the API URL using the host variable and path from the JSON input
    $apiUrl = 'http://185.193.19.48:9080/api/v1/create-profile';

    // Initialize cURL
    $ch = curl_init($apiUrl);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
        'device-type: Mobile'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Disable SSL verification if needed
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL peer verification if needed

    // Execute the cURL request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Decode the response
    $responseData = json_decode($response, true);

    // Check for errors or a successful response
    if ($httpCode == 200 && isset($responseData['status']) && $responseData['status'] == 'success') {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
    } else {
        $errorMessage = $responseData['message'] ?? 'Failed to update profile';
        echo json_encode(['status' => 'error', 'message' => $errorMessage]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
