<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

$request = Request::createFromGlobals();
$file = $request->files->get('fileUpload');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$file) {
        die("No file uploaded.");
    }

    // Define allowed file types and size limit
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if (!in_array($file->getMimeType(), $allowedTypes)) {
        die("Invalid file type.");
    }

    if ($file->getSize() > $maxSize) {
        die("File too large.");
    }

    // Move file to uploads directory
    $uploadDir = __DIR__ . '/uploads/';
    $newFilename = uniqid() . '.' . $file->guessExtension();

    try {
        $file->move($uploadDir, $newFilename);
        echo "Upload successful!";
    } catch (FileException $e) {
        echo "Upload failed: " . $e->getMessage();
    }
}
?>
