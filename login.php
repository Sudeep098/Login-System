<?php
header('Content-Type: application/json'); // Set content type to JSON
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // Allow specific methods
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Allow specific headers

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle preflight OPTIONS request
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form data is set
    if (isset($_POST['CC']) && isset($_POST['mobile'])) {
        // Retrieve form data
        $country_code = $_POST['CC'];
        $mobile_number = $_POST['mobile'];

        // Data to send in the POST request
        $postData = array(
            "country_code" => $country_code,
            "mobile_number" => $mobile_number
        );

        // Target URL
        $url = "http://185.193.19.48:9080/api/v1/send-otp";

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
            'Content-Length: ' . strlen($jsonData)
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
            echo json_encode(array('status' => 'error', 'message' => "An error occurred: $error"));
        } else {
            curl_close($ch);
            $responseData = json_decode($response, true);

            // Check if OTP was sent successfully
            if (isset($responseData['success']) && $responseData['success'] === true) {
                echo json_encode(array('status' => 'success', 'message' => 'OTP sent successfully'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Failed to send OTP'));
            }
        }
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Form data not received.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method.'));
}
?>
