<?php

/*
 * Payment gateway you want to use
 * 1 -> 2Checkout
 * 2 -> Stripe
 */
$gateway = 2;

$site_name = "Test"; // Name of the Site


/*
 * Set values to 0 if you don't want to use any of the following gateways
 * DEFAULT : 1.
 * 0 -> DISABLE,
 * 1 -> ENABLE
 */
$accept = array('paypal' => 1,
                'bitcoin' =>1);


/*
 * CURRENCY THAT YOU WANT TO ACCEPT
 */
$currency = "USD";

/*
 * 2CHECKOUT API DETAILS
 */
$two_checkout_details = array('public_key'=> 'Your_Public_Key','seller_id'=> 'Your_Seller_ID','private_key' => 'Your_Private_Key');


/*
 * STRIPE API DETAILS
 */
$stripe_details = array('apiKey' => 'YOUR_API_KEY','secret_key'=>'YOUR_SECRET_KEY');


/*
 * PAYPAL API DETAILS
 */
$paypal_details = array('clientId'=> 'YOUR_CLIENT_ID',
                        'clientSecret' => 'YOUR_CLIENT_SECRET');


/*
 * COINPAYMENT API DETAILS
 * show_bitcoin_icon set it to 1 if you are only going to accept bitcoin
 * Title: CoinPayment Button Title, it will only be used if show_bitcoin_icon is set to 0
 */
$coin_payment_details = array('merchant_id'=> 'YOUR_MERCHANT_ID','ipn_secret'=>'IPN_SECRET','ipn_url' => 'ipn_url'
,'show_bitcoin_icon' => 0,'title'=> 'like Bitcoin, Litecoin, Ethereum, etc');


/*
 * SUPPORTED GATEWAY LIST
 */
$gatewayList = array('2Checkout','Stripe');

/*
 * LIST OF COUNTRIES THAT ARE SUPPORTED
 */
function getCountryList(){
    return array("Afghanistan", "Albania", "Algeria","American Samoa","Andorra","Angola","Anguilla","Antigua","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bonaire, Sint Eustatius and Saba","Bosnia and Herzegovina","Botswana","Brazil","British Indian Ocean Territory","British Virgin Islands","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Colombia","Comoros","Cook Islands","Costa Rica","Côte d'Ivoire","Croatia","Cuba","Curaçao","Cyprus","Czech Republic","Democratic Republic of the Congo","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Federated States of Micronesia","Fiji","Finland","France","French Guiana","French Polynesia","Gabon","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guernsey","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Isle Of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania","Mauritius","Mayotte","Mexico","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","North Korea","Northern Mariana Islands","Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Puerto Rico","Qatar","Republic of the Congo","Romania","Runion","Russia","Rwanda","Saint Helena","Saint Kitts and Nevis","Saint Pierre and Miquelon","Saint Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Sint Maarten","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","St. Lucia","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","The Bahamas","The Gambia","Timor-Leste","Togo","Tokelau","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Turks and Caicos Islands","Tuvalu","U.S. Virgin Islands","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","Uruguay","Uzbekistan","Vanuatu","Venezuela","Vietnam","Wallis and Futuna","Western Sahara","Yemen","Zambia","Zimbabwe");
}


/*
 * NUMBER FROM WHICH YOUR PAYMENT ID WILL START
 */
$payment_id = 2000;

/*
 * Footer Settings
 * show_footer: Whether you want footer to be shown or not
 * text: Text you want to show in footer
 */
$footer = array('show_footer'=>true,'text' => 'Made By <a href="https://github.com/UnitedOver/Payments-Page" target="_blank">UnitedOver</a>');

