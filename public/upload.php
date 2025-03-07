<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$api_key = getenv('ROBOFLOW_API_KEY');
$dataset_name = "beach-cleaning-object-detection";

file_put_contents(__DIR__ . '/debug_env.txt', "API_KEY: " . getenv('ROBOFLOW_API_KEY'), FILE_APPEND);

file_put_contents(__DIR__ . '/debug_upload.txt', print_r($_FILES, true), FILE_APPEND);
// Check if a file is uploaded
if (!isset($_FILES['file']) || empty($_FILES['file']['tmp_name'])) {
    echo json_encode(['error' => 'No files uploaded']);
    http_response_code(400);
    exit;
}

// Ensure it's an array
$files = is_array($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : [$_FILES['file']['tmp_name']];
$file_names = is_array($_FILES['file']['name']) ? $_FILES['file']['name'] : [$_FILES['file']['name']];

$responses = [];

foreach ($files as $key => $tmp_name) {
    $file_name = $file_names[$key];

    // Roboflow Upload URL
    $url = "https://api.roboflow.com/dataset/" . $dataset_name . "/upload?api_key=" . $api_key;

    // Prepare the POST fields
    $postFields = [
        'name' => $file_name,
        'file' => new CURLFile($tmp_name, mime_content_type($tmp_name), $file_name)
    ];

    // cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    file_put_contents(__DIR__ . '/upload_log.txt', "HTTP_CODE: $http_code\nERROR: $curl_error\nRESULT: $result\n", FILE_APPEND);

    if ($result === false || $http_code >= 400) {
        $responses[] = [
            'file' => $file_name,
            'error' => $curl_error ?: "HTTP $http_code: " . $result
        ];
    } else {
        $responses[] = [
            'file' => $file_name,
            'response' => json_decode($result, true)
        ];
    }
}

// Return JSON response
file_put_contents(__DIR__ . '/upload_log.txt', print_r($responses, true), FILE_APPEND);
echo json_encode(['responses' => $responses]);
?>
