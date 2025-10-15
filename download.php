<?php
if (!isset($_GET['file'])) {
    http_response_code(400);
    die('No file specified');
}

$file = $_GET['file'];

// Security: Only allow files from backup directory
$backupDir = realpath("C:\\wamp64\\logs\\backup");
$filePath = realpath($file);

if (!$filePath || strpos($filePath, $backupDir) !== 0) {
    http_response_code(403);
    die('Access denied');
}

if (!file_exists($filePath)) {
    http_response_code(404);
    die('File not found');
}

$fileName = basename($filePath);

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: public');

readfile($filePath);
exit;
?>
