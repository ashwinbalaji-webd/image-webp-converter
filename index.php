<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {

    if(!file_exists('images')){
        if(mkdir('images', 0777)){
            mkdir('images/uploads', 0777);
            mkdir('images/webp', 0777);
        }
        else{
            echo json_encode(['success' => false, 'error' => 'Failed to create images directory.']);
        }
    }

    $uploadDir = 'images/uploads/';
    $uploadFile = $uploadDir . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        $outputPath = 'images/webp/' . uniqid() . '.webp';

        $image = imagecreatefromstring(file_get_contents($uploadFile));
        if ($image !== false) {
            if (imagewebp($image, $outputPath)) {
                imagedestroy($image);

                echo json_encode(['success' => true, 'path' => $outputPath]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to convert to WebP.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid image format.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to upload image.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}
