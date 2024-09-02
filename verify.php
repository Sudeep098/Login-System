<?php
// Set CORS headers to allow requests from any origin (adjust as needed)
header("Access-Control-Allow-Origin: *"); // Change '*' to your specific origin if needed
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Check if the form data is set
if (isset($_POST['CC']) && isset($_POST['mobile']) && isset($_POST['otp'])) {
    // Retrieve form data
    $country_code = $_POST['CC'];
    $mobile_number = $_POST['mobile'];
    $otp = $_POST['otp'];

    // Data to send in the POST request
    $postData = array(
        "country_code" => $country_code,
        "mobile_number" => $mobile_number,
        "otp" => $otp
    );

    // Target URL
    $url = "http://185.193.19.48:9080/api/v1/verify-otp";

    // Convert the data to JSON format
    $jsonData = json_encode($postData);

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData),
        'device-type: Mobile',
        'description: MOBILE WEB'
    ));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Disable hostname verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL certificate verification
    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2); // Enforce TLS 1.2

    // Execute the POST request
    $response = curl_exec($ch);

    // Check for errors
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        echo json_encode(array('status' => 'error', 'message' => $error));
    } else {
        curl_close($ch);
        // Decode the JSON response
        $responseData = json_decode($response, true);
        // Send response data as JSON
        echo json_encode($responseData);
    }
} else {
    // If form data is not set, return an error
    echo json_encode(array('status' => 'error', 'message' => 'Form data not received.'));
}
?>
