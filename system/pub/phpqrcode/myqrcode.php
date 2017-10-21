<?php
include "phpqrcode.php";//引入PHP QR库文件
$value="http://pay.no-same.cn/index.php?route=product/product&product_id=346";
$errorCorrectionLevel = "L";
$matrixPointSize = "3";
$myqrcode=QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize);

exit;
?>