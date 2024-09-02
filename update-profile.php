<?php
session_start(); // Start the session
// Set CORS headers to allow requests from any origin (adjust as needed)
header("Access-Control-Allow-Origin: *"); // Change '*' to your specific origin if needed
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Check if the form data is set

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
    session_start();
    $access_token = $_SESSION['access_token'] ?? '';

    // Prepare the data for the API request
    $data = [
        'name' => $name,
        'email' => $email,
        'company' => $company,
        'state' => $state,
        'city' => $city,
        'sponsor_code' => $sponsor_code
    ];

    // Convert the data array to JSON
    $jsonData = json_encode($data);

    // Define the API URL
    $apiUrl = 'http://185.193.19.48:9080/api/v1/create-profile'; // Replace with your actual API URL

    // Initialize cURL
    $ch = curl_init($apiUrl);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
        'device-type: Mobile'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Execute the cURL request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Decode the response
    $responseData = json_decode($response, true);

    // Check for errors or a successful response
    if ($httpCode == 200 && isset($responseData['status']) && $responseData['status'] == 'success') {
        // On success, send a success response to the frontend
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
    } else {
        // On failure, send an error response to the frontend
        $errorMessage = $responseData['message'] ?? 'Failed to update profile';
        echo json_encode(['status' => 'error', 'message' => $errorMessage]);
    }
} else {
    // If not a POST request, return an error
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
