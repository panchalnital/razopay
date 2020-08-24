<?php 
include('header.php');

$req_data=array(
"item_name"=>"My Test Product it software",
"item_description"=>"My Test Product Description",
"item_number"=>"3456",
"amount"=>"510.59",
"address"=>"ABCD Address",
"currency"=>"INR",
"cust_name"=>"phpzag",
"email"=>"nital@gmail.com",
"contact"=>"7405507091"
);
//echo "<pre>";
//print_r($req_data);exit;
?>
<title>Razorpay Payment </title>
<?php //include('container.php');?>
<div class="container">
	<div class="row">
	<h2></h2>
	<br><br><br>
<?php
require('config.php');
require('razorpay-php/Razorpay.php');
session_start();
use Razorpay\Api\Api;
$api = new Api($keyId, $keySecret);
$orderData = [
    'receipt'         => 3456,
    'amount'          => $req_data['amount'] * 100,
    'currency'        => $req_data['currency'],
    'payment_capture' => 1
];
$razorpayOrder = $api->order->create($orderData);
$razorpayOrderId = $razorpayOrder['id'];
$_SESSION['razorpay_order_id'] = $razorpayOrderId;
$displayAmount = $amount = $orderData['amount'];
if ($displayCurrency !== 'INR') {
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}
$data = [
    "key"               => $keyId,
    "amount"            => $amount,
    "name"              => $req_data['item_name'],
    "description"       => $req_data['item_description'],
    "image"             => "",
    "prefill"           => [
    "name"              => $req_data['cust_name'],
    "email"             => $req_data['email'],
    "contact"           => $req_data['contact'],
    ],
    "notes"             => [
    "address"           => $req_data['address'],
    "merchant_order_id" => rand(),
    ],
    "theme"             => [
    "color"             => "#F37254"
    ],
    "order_id"          => $razorpayOrderId,
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}

$json = json_encode($data);


require("manual.php");
?>
</div>
<?php include('footer.php');?>