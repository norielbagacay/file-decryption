<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

try {
    if (!isset($_FILES['encryptedFile']) || !isset($_POST['password'])) {
        throw new Exception('File and password are required');
    }

    $uploadedFile = $_FILES['encryptedFile'];
    $password = $_POST['password'];

    if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error');
    }

    if (!is_uploaded_file($uploadedFile['tmp_name'])) {
        throw new Exception('Invalid file upload');
    }

    $data = file_get_contents($uploadedFile['tmp_name']);
    if ($data === false) {
        throw new Exception('Could not read uploaded file');
    }

    $ivLen = openssl_cipher_iv_length('AES-256-CBC');
    if (strlen($data) < $ivLen) {
        throw new Exception('Invalid encrypted file format');
    }

    $iv = substr($data, 0, $ivLen);
    $ciphertext = substr($data, $ivLen);

    $decrypted = openssl_decrypt($ciphertext, 'AES-256-CBC', $password, OPENSSL_RAW_DATA, $iv);

    if ($decrypted === false) {
        throw new Exception('Decryption failed. Incorrect password.');
    }

    $backupDir = "C:\\wamp64\\logs\\backup";
    if (!is_dir($backupDir)) {
        if (!mkdir($backupDir, 0755, true)) {
            throw new Exception('Could not create backup directory');
        }
    }

    // Generate unique filename
    $timestamp = date('Y-m-d_H-i-s');
    $outputFile = $backupDir . "\\restore_" . $timestamp . ".sql";
    
    if (file_put_contents($outputFile, $decrypted) === false) {
        throw new Exception('Could not save decrypted file');
    }

    unlink($uploadedFile['tmp_name']);

    echo json_encode([
        'success' => true, 
        'message' => 'File decrypted successfully',
        'output_file' => $outputFile,
        'file_size' => strlen($decrypted),
        'file_name' => basename($outputFile)
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
}
?>
