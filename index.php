<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $postData = file_get_contents('php://input');


    if ($postData && !is_null($postData)) {
        $postData = json_decode($postData, true);
    } else {
    }

    if (!file_exists('images')) {
        if (!(mkdir('images'))) {
            echo json_encode(['success' => false, 'error' => 'Failed to create images directory.']);
        }
    }

    $uploadDir = 'images/' . $postData['uploadDir'];

    if (file_exists($uploadDir)) {
        deleteFolder($uploadDir);
    }

    if (mkdir($uploadDir)) {

        $_AimagePaths =  array_map(function ($_SimagePath) {
            global $uploadDir;
            return compressAndStoreImage($_SimagePath, $uploadDir);
        }, $postData['images']);

        echo json_encode(['success' => true, 'paths' => $_AimagePaths]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to create package directory.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}

function compressAndStoreImage($imageURL, $outputDirectory)
{

    $imageData = file_get_contents($imageURL);

    if (!$imageData) return false;

    $image = imagecreatefromstring($imageData);

    if (!$image) return false;

    $baseNameWithoutExtension = pathinfo($imageURL, PATHINFO_FILENAME);
    $outputPath = $outputDirectory . '/' . $baseNameWithoutExtension . '.webp';

    imagewebp($image, $outputPath, 80);

    imagedestroy($image);

    return $outputPath;
}



function deleteFolder($folderPath)
{
    if (is_dir($folderPath)) {
        $files = scandir($folderPath);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
                if (is_dir($filePath)) {
                    deleteFolder($filePath);
                } else {
                    unlink($filePath);
                }
            }
        }
        rmdir($folderPath);
    }
}
