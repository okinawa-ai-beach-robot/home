<?php
$api_key = getenv('ROBOFLOW_API_KEY'); // Get API Key from environment variable
$dataset_name = "beach-cleaning-object-detection"; // Dataset Name

// Check if files were uploaded
if (isset($_FILES['file']) && !empty($_FILES['file']['tmp_name'])) {
    $responses = [];

    foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name) {
        $file = $_FILES['file']['tmp_name'][$key];
        $file_name = $_FILES['file']['name'][$key];

        // Setup cURL for file upload
        $ch = curl_init();
        $url = "https://api.roboflow.com/dataset/" 
            . $dataset_name . "/upload"
            . "?api_key=" . $api_key;

        // Prepare the POST fields with the file upload (name only)
        $postFields = [
            'name' => $file_name,      // File name
            'file' => new CURLFile($file, mime_content_type($file), $file_name), // File to upload
        ];

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

        // Execute cURL request
        $result = curl_exec($ch);

        if ($result === false) {
            $responses[] = "Error uploading $file_name: " . curl_error($ch);
        } else {
            $responses[] = "Success: " . $result;
        }

        // Close cURL handle
        curl_close($ch);

    }

    // Send the responses back to the client
    echo json_encode(['responses' => $responses]);
} else {
    echo json_encode(['error' => 'No files uploaded']);
}
?>
