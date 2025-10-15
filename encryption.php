<?php
$password = "YourPassword"; 
$backupFile = "path/to/backup.sql";
$encryptedFile = "path/to/encrypted.sql.enc";
$plaintext = file_get_contents($backupFile);

$ivLen = openssl_cipher_iv_length('AES-256-CBC');
$iv = random_bytes($ivLen);
$ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $password, OPENSSL_RAW_DATA, $iv);

file_put_contents($encryptedFile, $iv . $ciphertext);
unlink($backupFile);

return $encryptedFile;
