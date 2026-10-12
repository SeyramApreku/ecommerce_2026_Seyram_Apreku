<?php

function upload_product_image($file, $required = true)
{
    if ($file === null) {
        if ($required) {
            throw new RuntimeException('Please choose a product image.');
        }

        return null;
    }

    if (
        !is_array($file) ||
        !isset($file['error']) ||
        !is_int($file['error'])
    ) {
        throw new RuntimeException('Invalid image upload.');
    }

    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        if ($required) {
            throw new RuntimeException('Please choose a product image.');
        }

        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException(
            'Image upload failed. Choose an image no larger than 2 MB.'
        );
    }

    $temporaryPath = $file['tmp_name'] ?? null;

    if (
        !is_string($temporaryPath) ||
        !is_uploaded_file($temporaryPath)
    ) {
        throw new RuntimeException('Invalid image upload.');
    }

    $size = filesize($temporaryPath);

    if ($size === false || $size < 1 || $size > 2 * 1024 * 1024) {
        throw new RuntimeException('Image must be no larger than 2 MB.');
    }

    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp'
    ];

    $fileInfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $fileInfo->file($temporaryPath);
    $imageInfo = @getimagesize($temporaryPath);

    if (
        !isset($allowedTypes[$mimeType]) ||
        $imageInfo === false ||
        ($imageInfo['mime'] ?? '') !== $mimeType
    ) {
        throw new RuntimeException(
            'Choose a valid JPG, PNG, GIF, or WebP image.'
        );
    }

    $directory = __DIR__ . '/../images/products/';

    if (!is_dir($directory) || !is_writable($directory)) {
        throw new RuntimeException(
            'The product image folder is missing or is not writable.'
        );
    }

    $filename = bin2hex(random_bytes(16))
        . '.'
        . $allowedTypes[$mimeType];

    if (!move_uploaded_file($temporaryPath, $directory . $filename)) {
        throw new RuntimeException('The image could not be saved.');
    }

    return $filename;
}