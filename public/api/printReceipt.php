<?php
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/config/init.php';

//Printer
require $_SERVER['DOCUMENT_ROOT'] . '/grocery/vendor/autoload.php';
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\CapabilityProfile;

//Activate Header CORS
$CORS = cors("POST");

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input", true));
    
    $print = new Sales();
    $print->order_id = $data->order_id;
    //$print->order_id = 4;
    $order_products = $print->printOrderReceipt();
    $sales = $print->printSalesReceipt();
    
    $leftspc=0;
    $widthspc=38;
    
    $caption = "Generic POS";
	$x = strlen($caption);
	$spcrem = $widthspc - $x;
	$spcside = $spcrem / 2;
    $content = Space($spcside).$caption.PHP_EOL;
    
    $caption = "Gapan City, Nueva Ecija";
	$x = strlen($caption);
	$spcrem = $widthspc - $x;
	$spcside = $spcrem / 2;
    $content .= Space($leftspc).Space($spcside).$caption.PHP_EOL;
    
    $x = strlen(str_pad($data->order_id, 9, '0', STR_PAD_LEFT));
	$z = strlen('Inv#');
	$s = Space(($widthspc - $z) - $x).str_pad($data->order_id, 9, '0', STR_PAD_LEFT);
	$content .= Space($leftspc). 'Inv#'.$s.PHP_EOL;

    $x = strlen(DATE("M. d, Y",strtotime($sales['created_at'])));
	$z = strlen('Date:');
	$s = Space(($widthspc - $z) - $x).DATE("M. d, Y",strtotime($sales['created_at']));
    $content .= Space($leftspc). 'Date:'.$s.PHP_EOL;
    
    $x = strlen(DATE("h:i A",strtotime($sales['created_at'])));
	$z = strlen('Time:');
	$s = Space(($widthspc - $z) - $x).DATE("h:i A",strtotime($sales['created_at']));
    $content .= Space($leftspc). 'Time:'.$s.PHP_EOL;
    
    $caption = "______________________________________";
	$x = strlen($caption);
	$spcrem = $widthspc - $x;
	$spcside = $spcrem / 2;
    $content .= Space($leftspc).Space($spcside).$caption.PHP_EOL;
    
    foreach($order_products as $orders) {
        $prodname = $orders['product_name'];
        $x = strlen($orders['total_amount']);
        $z = strlen($prodname." x".$orders['product_qty']);
        $prodlen = strlen($orders['product_name']);
        $test = $z + $x + 1;
        if ($test > $widthspc) {
                $exceed = abs(($x + $z + 1) - $widthspc);
                $prodname = substr($prodname,0, ($prodlen - $exceed));
                $z = strlen($prodname." x".$orders['product_qty']);
        }
        $s = Space(($widthspc - $z) - $x -4)."Php ".$orders['total_amount'];
        $content .= Space($leftspc).$prodname." x".$orders['product_qty'].$s.PHP_EOL;
    }

    $caption = "______________________________________";
	$x = strlen($caption);
	$spcrem = $widthspc - $x;
	$spcside = $spcrem / 2;
    $content .= Space($leftspc).Space($spcside).$caption.PHP_EOL.PHP_EOL;
    
    $x = strlen($sales['total_amount']);
	$z = strlen('Total Amount:');
	$s = Space(($widthspc - $z) - $x -4)."Php ".$sales['total_amount'];
    $content .= Space($leftspc). 'Total Amount:'.$s.PHP_EOL;

    if ($sales['discount_amount'] != 0) {
        $x = strlen($sales['discount_amount']);
        $z = strlen('Total Discount:');
        $s = Space(($widthspc - $z) - $x -4)."Php ".$sales['discount_amount'];
        $content .= Space($leftspc). 'Total Discount:'.$s.PHP_EOL;
    }

    $caption = "______________________________________";
	$x = strlen($caption);
	$spcrem = $widthspc - $x;
	$spcside = $spcrem / 2;
    $content .= Space($leftspc).Space($spcside).$caption.PHP_EOL.PHP_EOL;

	$x = strlen($sales['vat_sales']);
	$z = strlen('Vat Sale:');
	$s = Space(($widthspc - $z) - $x -4)."Php ".$sales['vat_sales'];
	$content .= Space($leftspc). 'Vat Sale:'.$s.PHP_EOL;

	$x = strlen($sales['vat_amount']);
	$z = strlen('12 % Vat:');
	$s = Space(($widthspc - $z) - $x -4)."Php ".$sales['vat_amount'];
    $content .= Space($leftspc). '12 % Vat:'.$s.PHP_EOL.PHP_EOL;
    
    $x = strlen($sales['to_pay']);
	$z = strlen('Total Amount to be Paid:');
	$s = Space(($widthspc - $z) - $x -4)."Php ".$sales['to_pay'];
    $content .= Space($leftspc). 'Total Amount to be Paid:'.$s.PHP_EOL;
    
    $x = strlen($sales['tendered']);
	$z = strlen('Tendered Amount:');
	$s = Space(($widthspc - $z) - $x -4)."Php ".$sales['tendered'];
    $content .= Space($leftspc). 'Tendered Amount:'.$s.PHP_EOL;
    
    $x = strlen($sales['change_amount']);
	$z = strlen('Change:');
	$s = Space(($widthspc - $z) - $x -4)."Php ".$sales['change_amount'];
	$content .= Space($leftspc). 'Change:'.$s.PHP_EOL;

	$caption = "______________________________________";
	$x = strlen($caption);
	$spcrem = $widthspc - $x;
	$spcside = $spcrem / 2;
    $content .= Space($leftspc).Space($spcside).$caption.PHP_EOL.PHP_EOL;
    
    $x = strlen($sales['employee_fn']. " ".$sales['employee_sn']);
	$z = strlen('Cashier:');
	$s = Space(($widthspc - $z) - $x).$sales['employee_fn']. " ".$sales['employee_sn'];
	$content .= Space($leftspc). 'Cashier:'.$s.PHP_EOL;

	$x = strlen($sales['payment_name']);
	$z = strlen('Payment Type:');
	$s = Space(($widthspc - $z) - $x).$sales['payment_name'];
	$content .= Space($leftspc). 'Payment Type:'.$s.PHP_EOL;

    try {
        $profile = CapabilityProfile::load("TM-U220");
        $connector = new WindowsPrintConnector("EPSON TM-U220 Receipt");
        $printer = new Printer($connector, $profile);
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/grocery/public/images/receipts barcode/$data->order_id.png", $generator->getBarcode($data->order_id, $generator::TYPE_CODE_128));
        $tux = EscposImage::load($_SERVER['DOCUMENT_ROOT'] . "/grocery/public/images/receipts barcode/$data->order_id.png", false);
        
        $printer -> text($content);
        $printer-> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> bitImageColumnFormat($tux, Printer::IMG_DOUBLE_HEIGHT);
        $printer -> feed();
        $printer -> cut();
        $printer -> close();
    } catch (Exception $e) {
        echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
    }
}
?>