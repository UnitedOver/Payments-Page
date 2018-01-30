<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/includes/parsecsv.lib.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
include_once($_SERVER['DOCUMENT_ROOT'].'/gateways/coinpayments/coinpayments.inc.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/gateways/2checkout/Twocheckout.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/gateways/stripe/init.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/gateways/PayPal/autoload.php');
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\CreditCard;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Transaction;


/*
 * @return payment details csv
 */
function get_payment_csv(){
    $csv = new parseCSV();
    $csv->fields = ['Payment ID','Transaction ID', 'Name','Country','Email','Reason','Amount','Payment Method','Time','IP Address'];
    $csv->sort_by = 'Payment ID';
    $csv->parse($_SERVER['DOCUMENT_ROOT'].'/data/payment_info.csv');
    return $csv;
}
/*
 * @params user and payment details
 * @return payment details csv
 */
function add_payment($name,$country,$email,$amount,$reason,$payment_method,$transaction_id,$updatePaymentID = 0,$details_array = null){
    global $payment_id,$currency;
    $pid = -1;

    $csv = get_payment_csv();

    $csv_data = $csv->data;

    if($updatePaymentID==0) {
        if (is_array($csv_data)) {
            $csv_data = array_values($csv_data);
            $data_len = count($csv_data);

            if ($data_len > 0) $pid = $csv_data[$data_len - 1]['Payment ID'] + 1;
            if ($payment_id < $pid) $payment_id = $pid;
        }
    }else{
        $payment_id = $updatePaymentID;
    }
    if($details_array!=null && is_array($details_array)){

        $p_row = array('Payment ID '=>$payment_id) + $details_array;

        $csv->data[] = $p_row;


    }else {
        $row = array($payment_id, $transaction_id, $name, $country, $email, $reason, $currency . " " . $amount, $payment_method, date("h:i:sa, d M Y"),getIP());
        if ($updatePaymentID == 0) {
            $csv->data[] = $row;
        } else {
            $csv->data[$updatePaymentID] = $row;
        }
    }
    $csv->save();

    return $payment_id;
}

/*
 * @params Payment ID
 * This will remove payment entry with PAYMENT ID @payment_id from payment csv
 */

function remove_payment_entry($payment_id){
    $csv = get_payment_csv();
    if(isset($csv->data[$payment_id])) {
        unset($csv->data[$payment_id]);
        $csv->save();
    }
}

/*
 * @params $ID => ID of payment
 * @params $SUCCESS => Whether payment was successfull or not
 * This will update details of pending payment
 */
function update_pending_payment($id,$amount,$txn_id,$success){
    $csv = get_pendingcsv('ID');

    if(isset($csv->data[$id])) {
        if ($success) {
            $row = $csv->data[$id];
            $row['Amount'] = $amount;
            unset($row['ID']);
            $data = array('Transaction ID ' => $txn_id) + $row;


            add_payment(null, null, null, null, null, null, null, null, $data);
        }
        unset($csv->data[$id]);
        $csv->save();

    }
}

/*
 * This will check if there is any payment info to process or not.
 */
if(isset($_POST['process_payment']) && isset($_POST['amount']) && isset($_POST['reason'])  && isset($_POST['name']) && isset($_POST['email']) ){
    process_payment();
    die();
}

/*
 * This will process payment
 */
function process_payment(){
    global $site_name,$gateway;
    $amount = filter_number($_POST['amount']);
    $name = filter_string($_POST['name']);
    $reason = filter_string($_POST['reason']);
    $email = filter_email($_POST['email']);
    $country = filter_string($_POST['country']);
    $token = $_POST['token'];
    if(empty($token)) error_message('Error');// Show error if token is missing
    validateInput($amount,$email,$name,$reason,$country);// Validate input which we have received
    $payment_id = -1;

    if(isset($_POST['paypal'])){

        /*
         * This will process paypal payment
         */
        $payment_id = paypal_process($name, $country, $email, $reason,$token);
    }else {

        /*
         * Payment Gateways
         * More info about the gateway numbers can be found in /includes/config.php
         */

        switch ($gateway) {
            case 1:
                $payment_id = add_payment($name, $country, $email, $amount, $reason, '2Checkout', 0);
                $transactionId = two_checkout_process($payment_id, $amount, $token);
                if ($transactionId) {
                    $payment_id = add_payment($name, $country, $email, $amount, $reason, '2Checkout', $transactionId, $payment_id);
                } else {
                    remove_payment_entry($payment_id);
                }
                break;
            case 2:
                $transactionId = stripe_process($amount, $reason, $token);
                if ($transactionId) {
                    $payment_id = add_payment($name, $country, $email, $amount, $reason, 'Stripe', $transactionId);
                };
                break;
            default:

                /*
                 * If invalid payment gateway is selected then it will invalid gateway error
                */
                error_message('Invalid Gateway');
                break;
        }
    }
    if($payment_id>0){
        /*
         * Show successfull message if payment status is successful with payment ID so that client browser can show receipt to the user
         */
        header('Content-type: application/json');
        $data = [ 'success' => 1, 'payment_id' => $payment_id ];
        echo json_encode( $data );

    }
    die();
}

/*
 * This will only get details from PayPal Payment ID AND WILL NOT PROCESS THE PAYMENT as it was process on client side.
 * Amount will be used from the data that it has received from PAYPAL
 */
function paypal_process($name, $country, $email, $reason,$payment_id){
    global $paypal_details;
    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            $paypal_details['clientId'],     // ClientID
            $paypal_details['clientSecret']      // ClientSecret
        )
    );
    try {
        $payment = Payment::get($payment_id, $apiContext);
    } catch (PayPal\Exception\PayPalConnectionException $ex) {
        error_message('Please try again');
    } catch (Exception $ex) {
        error_message('Please try again');
    }

    $amount = $payment->transactions[0]->amount->total;

    if($amount>0) {
        return add_payment($name, $country, $email, $amount, $reason, 'PayPal', $payment_id);
    }else{
        error_message('Invalid Amount');
    }

}

/*
 * This will process CoinPayment gateway
 */
if(isset($_POST['create_transaction_coinpayment'])){
if($accept['coinpayments']==1) {
    create_transaction_coinpayment();
}else{
 error_message('Payment not supported');// Throw error if CoinPayments is disabled
    }
    die();
}

/*
 * This will process stripe payment
 */
function stripe_process($amount,$reason,$token){
    global $site_name,$currency,$stripe_details;


    try {
        \Stripe\Stripe::setApiKey($stripe_details['secret_key']);


        $charge = \Stripe\Charge::create(array(
            "amount" => $amount * 100,
            "currency" => $currency,
            "description" => $site_name,
            "statement_descriptor" => $reason,// You can change description to whatever you want. Here we are using reason for payment as a descriptor
            "capture" => false, // We don't want stripe to capture the payment details, as it is of no use for us.
            "source" => $token,
        ));
    }catch(Exception $e){
        error_message("Unexpected error occued!");
    }

        if ($charge->id) {
            return $charge->id;
        }

    return false;

}

/*
 * This will process 2checkout payment
 */
function two_checkout_process($payment_id,$amount,$token){
    global $currency,$two_checkout_details;
    Twocheckout::privateKey($two_checkout_details['private_key']);
    Twocheckout::sellerId($two_checkout_details['seller_id']);
    Twocheckout::sandbox(false);
    try{
        $charge = Twocheckout_Charge::auth(array(
            "merchantOrderId" => $payment_id,
            "token" => $token,
            "currency" => $currency,
            "total" => $amount
        ), 'array');
        if ($charge['response']['responseCode'] == 'APPROVED') {
            return $charge['response']['transactionId'];
        }
    }catch (Twocheckout_Error $e) {
        error_message($e->getMessage());
    }
}

/*
 * This will create a pending payment id for coinpayment and will be returned to client browser
 * We will require this ID to futher process the payment once it is successful
 */
function create_transaction_coinpayment(){

    global $site_name,$currency,$coin_payment_details;
    $amount = filter_number($_POST['amount']);
    $name = filter_string($_POST['name']);
    $reason = filter_string($_POST['reason']);
    $email = filter_email($_POST['email']);
    $country = filter_string($_POST['country']);
    validateInput($amount,$email,$name,$reason,$country);
    $id = add_pending_payment($name,$country,$email,$amount,$reason,'CoinPayments');
    header('Content-type: application/json');
    $data = [ 'success' => 1, 'id' => $id];
    echo json_encode( $data );
    die();




}

/*
 * This will validate the user input and will throw error if input is not valid.
 */
function validateInput($amount,$email,$name,$reason,$country){


    if(!is_numeric($amount) || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($amount)<=0 || strlen($name)==0 || strlen($reason)==0 || strlen($country)==0){
        error_message("Invalid Details");
    }
    if($amount<1){
        error_message("Amount should be atleast USD 1");
    }
}

function success_message(){

}

/*
 * @params Message: Error message you want to show to user
 * This will show error message to user
 */
function error_message($message){
    header('Content-type: application/json');
    $data = [ 'success' => 0, 'message' => $message ];
    echo json_encode( $data );
    die();
}

/*
 * @params Payment and UserDetails
 * This will add a entry to the pending payment csv
 */
function add_pending_payment($name,$country,$email,$amount,$reason,$payment_method){
    global $currency;
    $csv = get_pendingcsv('ID');
    $id = 1;
    $csv_data = $csv->data;



    if(is_array($csv_data)) {
        $csv_data = array_values($csv_data);
        $data_len = count($csv_data);

        if ($data_len > 0) $id = $csv_data[$data_len - 1]['ID'] + 1;

    }

    $csv->data[] = array($id,$name,$country,$email,$reason,$currency." ".$amount,$payment_method,date("h:i:sa, d M Y"),getIP());
    $csv->save();
    return $id;
}

/*
 * @return pending payment details csv
 */
function get_pendingcsv($sortby){
    $csv = new parseCSV();
    $csv->fields = ['ID', 'Name','Country', 'Email','Reason','Amount','Payment Method','Time','IP Address'];
    $csv->sort_by = $sortby;
    $csv->parse($_SERVER['DOCUMENT_ROOT'].'/data/pending_payment.csv');
    return $csv;
}


function getIP(){
    $ip = filter_ip($_SERVER['REMOTE_ADDR']);
    return $ip;
}
/*
 * @param String: You want to clear
 * @return filtered Strings
 * This function is used to help filter the user inputs which will help us in escaping unwanted characters.
 */
function filter_string($string){
    return filter_var(trim($string), FILTER_SANITIZE_STRING);
}
function filter_email($email){
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}
function filter_number($number){
    return filter_var(trim($number), FILTER_SANITIZE_NUMBER_FLOAT);
}
function filter_ip($ip) {
    $ip = filter_var($ip, FILTER_VALIDATE_IP);
    return ($ip)? $ip : -1;
}



