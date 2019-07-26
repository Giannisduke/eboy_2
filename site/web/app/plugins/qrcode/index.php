<?php 
header('Content-Type: text/html; charset=utf-8');
//namespace KS;
require_once __DIR__."/vendor/autoload.php";

//this is a base on prompay QR Code 
// can change style of QR Code by function set_targetType
// if set_targetType == url this QR Code to redirect to Open Web url
// but if not set function it will return prompay code for payment
$pp = new \KS\PromptPay();
$pp->set_targetType('url');

// get URL and name from Woocommerce API
require_once __DIR__."/class/config.php";
require_once __DIR__."/class/class_product.php";

$product = new product($woocommerce);

/* $perpage = isset($_REQUEST['perpage'])? $_REQUEST['perpage'] : '10';
$status = isset($_REQUEST['status'])? $_REQUEST['status'] : 'publish';
$page = isset($_REQUEST['page'])? $_REQUEST['page'] : '1'; */
//$perpage = 100;
//$status = 'publish';
$page = (isset($_GET['page'])? $_GET['page']:'1');
$list = $product->getAllProductList(100,$page);
/* echo "<h3>สามารถใส่การตั้งค่าการดังต่อไปนี้ ตามหลัง URL ได้</h3>";
echo "<p> perpage  คือ จำนวนข้อมูลที่แสดงต่อหน้า default is 10</p>";
echo "<p> status  คือ สถานะของสินค้า (draft, pending, private, publish) * default is publish</p>";
echo "<p style='color:red'> ตัวอย่าง http://playground-inseoul.com/qrcode/?perpage=100&status=draft </p>";
 */
echo "<div style='position:fixed;background-color:#fff;padding:10px;width:100%;top:0;'>
    <ul> 
    <li style='float:left;padding:10px;list-style:none;'>Page :</li>
    <li style='float:left;padding:10px;list-style:none;'><a href='?page=1'>1</a></li>
    <li style='float:left;padding:10px;list-style:none;'><a href='?page=2'>2</a></li>
    <li style='float:left;padding:10px;list-style:none;'><a href='?page=3'>3</a></li>
    <li style='float:left;padding:10px;list-style:none;'><a href='?page=4'>4</a></li>
    </ul></div>";
echo "<br><br><br><hr>";
for($i = 0 ;$i < count($list); $i++){
    echo "<ul><li>";
    echo "<div>=====================================<br/>";
    echo "<p> ID : ".$list[$i]->id."</p>";
    echo "<p> Name : ".$list[$i]->name."</p>";
    echo "<p> URL : ".$list[$i]->permalink."</p>";  
    $target = "http://playground-inseoul.com/show/product/".$list[$i]->id; 
    $name = $list[$i]->slug."-".$list[$i]->id;
    $savePath = 'assets/'.$name.'_qrcode.png';
    $pp->generateQrCode($savePath, $target); 
    echo "=========================================<br/>";
    echo '<img src="'.$savePath.'">';
    echo "</div>";
    echo "</li></ul>";
}


//Generate QR Code PNG file



function getname($t){
    $spli = explode("/",$t);
    $name = $spli[count($spli)-1];
    return 'assets/'.$name.'_qrcode.png';
}

function showitem(){
 

}