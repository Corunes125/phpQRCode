<?php

require "vendor/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Get the text from POST request (if needed)
$text = $_POST["text"]?? "hello world 1233"; // default text if POST is empty

$qr_code = QrCode::create($text);

$writer = new PngWriter;

$result = $writer->write($qr_code);

// Get the binary string representation of the QR code
$qr_code_binary = $result->getString();

// Create a unique filename for the QR code image
$filename = uniqid(). '.png';

// Save the QR code image to the img folder
$img_folder = 'img/';
if (!file_exists($img_folder)) {
    mkdir($img_folder, 0777, true);
}
file_put_contents($img_folder. $filename, $qr_code_binary);

include 'config.php';
// Insert the QR code image path into the database
$sql = "INSERT INTO qr (qrcode, qrtext) VALUES (?,?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $qr_code_path, $text);
$qr_code_path = $img_folder. $filename;
$stmt->execute();

// Output a success message
echo "QR code saved as $filename in img folder";

