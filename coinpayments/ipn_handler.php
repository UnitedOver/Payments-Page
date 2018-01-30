<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/gateways/coinpayments/RonMelkhior/CoinpaymentsIPN/Initialize.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/gateways/coinpayments/RonMelkhior/CoinpaymentsIPN/Exceptions/FailedPaymentException.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/gateways/coinpayments/RonMelkhior/CoinpaymentsIPN/Exceptions/InsufficientDataException.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/gateways/coinpayments/RonMelkhior/CoinpaymentsIPN/Exceptions/InvalidRequestException.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/gateways/coinpayments/RonMelkhior/CoinpaymentsIPN/IPN.php';

/*
 *  This is use to process Coinpayments Transactions
 */
use RonMelkhior\CoinpaymentsIPN\IPN;

$ipn = new IPN();
$ipn->setMerchantID($coin_payment_details['merchant_id'])
    ->setIPNSecret($coin_payment_details['ipn_secret']);

try {
    if ($ipn->validate($_POST, $_SERVER)) {
        // Payment was successful, verify vars such as the transaction ID/email and process it.
        $pending_id = filter_string($_POST['item_number']);
        $amount = filter_string($_POST['currency2'] .' '. $_POST['amount2']);
        $txn_id = filter_string($_POST['txn_id']);
        update_pending_payment($pending_id,$amount,$txn_id,true);
    } else {
        // IPN worked, but the payment is pending.
    }
} catch (RonMelkhior\CoinpaymentsIPN\Exceptions\InvalidRequestException $e) {
    // The IPN data was not valid to begin with (missing data, invalid IPN method).
} catch (RonMelkhior\CoinpaymentsIPN\Exceptions\InsufficientDataException $e) {
    // Sufficient data provided, but either the merchant ID or the IPN secret didn't match.
} catch (RonMelkhior\CoinpaymentsIPN\Exceptions\FailedPaymentException $e) {
    // IPN worked, but the payment has failed (PayPal refund/cancelled/timed out).
    if(isset($_POST['item_number'])){
        $pending_id = filter_string($_POST['item_number']);
        update_pending_payment($pending_id,0,0,false);
    }
}
