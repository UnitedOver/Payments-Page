# Payments Page
A modern website/webpage to easily accept payment for your work/service/product even if you are a freelancer or a small organization. 

## Requirements 
 - SSL certificate installed on your server
 - PHP 5.4+
 - [cURL](http://php.net/manual/en/curl.installation.php) extension in PHP
 - [mbstring](http://php.net/manual/en/book.mbstring.php) extension in PHP

## Installation
Just upload the files on server and configure the config.php

## Setup
Setting up the Payments Page is very easy as you just need to make changes to the *config.php* file which is well commented to make things easy and understandable

## Supported Payment Gateways
- [Paypal](https://www.paypal.com/)
- [Stripe](https://stripe.com/) *(Credit / Debit Card Processor)*
- [2 Checkout](https://www.2checkout.com/) *(Credit / Debit Card Processor)*
- [CoinPayments](https://www.coinpayments.net/) *(Cryptocurrency like Bitcoin, Litecoin, Ethereum etc)*

## FAQ

#### 1. How to change the accepted coins in CryptoCurrency Payment
This can only be changed via CryptoCurrency settings on their dashboard. You will need to mention the coins you want to accept there only. 

#### 2. Where does the invoice / transaction details are saved
All the transaction details get saved as a csv file under the data folder.

## Solution to Frequent Errors
#### 1. cURL error 60: SSL certificate problem: unable to get local issuer certificate (see http: curl.haxx.se libcurl c libcurl errors.html)
To resolve the error, you need to define your CURL certificate authority information path

To do that,

 1. Download the latest curl recognized certificates here: https://curl.haxx.se/ca/cacert.pem
 2. Save the cacert.pem file in a reachable destination.
 3. Then, in your php.ini file, scroll down to where you find [curl].
 4. You should see the CURLOPT_CAINFO option commented out. Uncomment and point it to the cacert.pem file. You should have a line like this: `curl.cainfo = “certificate path\cacert.pem”`

Save and close your php.ini. Restart your webserver and try your request again.

If you do not set the right location, you will get a CURL 77 error.

### License
Payments Page is [MIT Licensed](https://github.com/UnitedOver/Payments-Page/blob/master/LICENSE)

