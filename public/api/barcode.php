<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/vendor/autoload.php';

$text = $_GET['text'] ?? '';
if (empty($text)) {
    http_response_code(400);
    exit("Text is required");
}

$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
header('Content-Type: image/png');
echo $generator->getBarcode($text, $generator::TYPE_CODE_128);
?>
