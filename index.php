<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
?><!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $site_name; ?></title>
    <link rel="stylesheet" href="css/main.css" />
    <?php if($accept['paypal']==1){?>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <?php } ?>
</head>
<body>

<div class="main_container">

    <div id="vr_back"></div>
<form autocomplete="off" novalidate id="payment-form" method="post">
    <div class="logo logo_pos logo_landing" id="logo"><?php echo $site_name; ?></div>

    <div class="receipt_msg">
        Thank you <span class="name_recip"></span>, the payment of <span class="amount_recip"></span> for <span class="reason_recip"></span> is successful
        <div class="payment_id">Payment ID: <span class="payment_id_recip"></span></div>

        <div class="save_receipt"><div id="save_img" class="icon-group icon-group-dims"></div></div>
    </div>

    <div id="countrpar"><div id="countryselector">WHERE ARE YOU FROM?</div>
    <div id="countryselectorlist">
        <ul id="countrylist">

            <?php

            foreach(getCountryList() as $country){
                echo '<li value="'.strtolower($country).'">'.$country.'</li>';
            }
            ?>
        </ul>
    </div>

    </div>

        <div class="details_view">
                <div class="head_de"><div class="ic_dis_labels icon-android_person icon-android_person-dims"></div>
                    <div class="inf_container">
                        <div class="det_label">Name</div>
                        <div class="det_value" id="user_name_holder"></div>
                    </div>
                </div>

            <div class="head_de"><div class="ic_dis_labels icon-email-dims icon-email"></div>
                <div class="inf_container">
                    <div class="det_label">Email</div>
                    <div class="det_value" id="email_holder"></div>
                </div>
            </div>

            <div class="head_de"><div class="ic_dis_labels icon-wallet icon-wallet-dims"></div>
                <div class="inf_container">
                    <div class="det_label">Amount</div>
                    <div class="det_value" id="amount_sec_holder"></div>
                </div>
            </div>
        </div>

    <div id="detailscreen" class="detaiscreen_bg selcountryp">
        <div id="nextButton" class="icon-next_arrow-dims icon-next_arrow"></div>
    </div>
        <div id="detailscreen_pg" class="detailsside">
        <div class="loading_box">
            <div class="loading_box_cont">
            <div class="payment_method_ic">

            </div>
            <div class="spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
            <div class="complete_details">Please wait</div>
            </div>
        </div>

        <div id="user_details">
            <span id="date"><?php echo date("M d, Y"); ?></span>

            <div class="payment_methods">

                <div class="card_details"><span class="paywithcard">Pay with card</span>
                    <div class="error_cc visible"><div class="message"></div></div>
                    <?php
                    if($gateway==2){
                        ?>
                        <div class="cardCol">
                        <div class="group widerow stripe_right_margin">
                            <input type="text" id="ccName" autocomplete="off" required>
                            <span class="highlight"></span>
                            <label>Card Holder’s Name</label>
                        </div>
                        <div class="stripe_inputs stripe_halfwidth">

                            <div class="field half-width stripe_right">
                                <div id="stripe-card-expiry" class="input empty"></div>
                                <label for="stripe-card-expiry" data-tid="main_container.form.card_expiry_label">Expiry</label>
                                <div class="baseline"></div>
                            </div>
                        </div>
                        </div>
                        <div class="stripe_inputs mrgbtm">
                        <div class="row">
                            <div class="field stripe_right_margin">
                                <div id="stripe-card-number" class="input empty"></div>
                                <label for="stripe-card-number" data-tid="main_container.form.card_number_label">Card number</label>
                                <div class="baseline"></div>
                            </div>
                            <div class="field half-width stripe_right">
                                <div id="stripe-card-cvc" class="input empty"></div>
                                <label for="stripe-card-cvc" data-tid="main_container.form.card_cvc_label">CVC</label>
                                <div class="baseline"></div>
                            </div>
                        </div>
                        </div>
                        <?php
                    }else{
                    ?>
                    <div class="cardCol">
                    <div class="group widerow">
                        <input type="text" id="ccName" autocomplete="off" required>
                        <span class="highlight"></span>
                        <label>Card Holder’s Name</label>
                    </div>
                    <div class="group smrow">
                        <input type="text" id="ccExp" autocomplete="off" required>
                        <span class="highlight"></span>
                        <label>Expiry</label>
                    </div>
                    </div>
                    <div class="cardCol">
                        <div class="group widerow">
                            <input type="text" id="ccNo" autocomplete="off" required maxlength="19">
                            <span class="highlight"></span>
                            <label>Card Number</label>
                        </div>
                        <div class="group smrow">
                            <input type="text" id="cvv" autocomplete="off" size="5" required>
                            <span class="highlight"></span>
                            <label>CVC</label>
                        </div>
                        </div>
                     <?php } ?>


                    <div class="payWithCardButton" id="payWithCard">Pay</div>
                </div>
                <div class="process_info">Card processed with <?php echo $gatewayList[$gateway - 1]; ?></div>
                <div class="orpaywith">or pay with</div>
                <div class="otherPayOptions">
                    <?php if($accept['paypal']==1){?><div class="otbtn paywithPaypal"><div id="paypal-button"></div></div><?php } ?>
                    <?php if($accept['coinpayments']==1){?><div class="otbtn paywithBTC">
                        <?php
                            if($coin_payment_details['show_bitcoin_icon']==1)
                                echo '<div class="icon-Bitcoin_logo icon-Bitcoin_logo-dims"></div>';
                            else echo '<div class="icon-cryptocurrencies" title="'.$coin_payment_details['title'].'""></div>';
                        ?>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <div class="details_field">
            <div class="group">
                <input type="text" name="user_name" id="user_name" required>
                <span class="highlight"></span>
                <label>Name</label>
            </div>

            <div class="group">
                <input type="text" data-type="email" name="email" id="email" required>
                <span class="highlight"></span>
                <label>Email</label>
            </div>

            <div class="group">
                <input type="text" name="reason" id="reason" required data-holder="0">
                <span class="highlight"></span>
                <label>Reason For Payment</label>
            </div>

            <div class="enter_amount_sec">
            <span class="currency"><label for="amount_sec">$</label></span><input type="text" data-type="number" data-pre="$" id="amount_sec" name="amount" class="amount" value="" placeholder="0.00" maxlength="6" data-group="0"/>
            </div>
            </div>
        </div>
    </div>

        <div id="invoice_ic">
            <div class="icon-invoice icon-invoice-dims"></div>
            <div class="payment_invoice_id"></div>
        </div>
</form>
    <?php if($accept['coinpayments']==1){?>
    <form action="https://www.coinpayments.net/index.php" method="post" id="coinpayments_pay">
        <input type="hidden" name="cmd" value="_pay_simple">
        <input type="hidden" name="reset" value="1">
        <input type="hidden" name="merchant" value="<?php echo $coin_payment_details['merchant_id']; ?>">
        <input type="hidden" name="currency" value="USD">
        <input type="hidden" name="amountf" value="">
        <input type="hidden" name="ipn_url" value="<?php echo $coin_payment_details['ipn_url']; ?>">
        <input type="hidden" name="item_name" value="">
        <input type="hidden" name="item_number" value="">
        <input type="hidden" name="email" value=""></form>
    <?php } ?>

<?php
if($footer['show_footer']){
    echo '<div class="footer">'.$footer['text'].'</div>';
}
?>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<script src="js/jquery.inputmask.bundle.js"></script>
<script src="js/jquery.nicescroll.min.js"></script>


<?php
if($gateway===1){
    ?>
    <script type="text/javascript" src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
    <script>

        var successCallback = function(data) {
            process_payment(data.response.token.token);

        };
        // Called when token creation fails.
        var errorCallback = function(data) {
            if (data.errorCode === 200) {
                alertMsg('Please try Again!');
            } else {
                alertMsg(data.errorMsg);
            }
            jQuery('.loading_box').hide();
        };

        var tokenRequest = function() {
            var exp = $("#ccExp").val();
            var ccNo = $("#ccNo");
            var cvv = $("#cvv");

            if(jQuery("#ccName").val().length==0 ){
                alertMsg("Your card's holder name is incomplete.");
                return false;
            }

            if(exp.length==0 || ccNo.val().length==0  ){
                alertMsg("Your card's expiration date is incomplete.");
                return false;
            }
            if(cvv.val().length==0 ) {
                alertMsg("Your card's security code is incomplete.");
                return false;
            }
            if(ccNo.val().length==0 ) {
                alertMsg("Your card number is incomplete.");
                return false;
            }

            if(jQuery("#ccNo").hasClass('cc_type_unknown')){
                alertMsg("Your card number is invalid.");
                return false;
            }
            exp = exp.split('/');
            var args = {
                sellerId: "<?php echo $two_checkout_details['seller_id']; ?>",
                publishableKey: "<?php echo $two_checkout_details['public_key']; ?>",
                ccNo: ccNo.val(),
                cvv: cvv.val(),
                expMonth: exp[0],
                expYear: exp[1]
            };


            TCO.requestToken(successCallback, errorCallback, args);


        };

        $(function() {
            // Pull in the public encryption key for our environment
            TCO.loadPubKey('production');

            $("#payWithCard").click(function(e) {
                // Call our token request function
                tokenRequest();

                // Prevent form from submitting
                return false;
            });
        });



        jQuery(document).on("keyup", "input", function (e) {
            if (!((e.which < 65 || e.which > 122) && (e.which < 48 || e.which > 57))) {
                jQuery(".visible").removeClass('visible');
            }
        });

    </script>

<?php
}else if($gateway==2){
    ?>
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>


    <script>
        (function() {
            'use strict';

            var stripe = Stripe('<?php echo $stripe_details['apiKey']; ?>');


            var elements = stripe.elements({
                fonts: [
                    {
                        cssSrc: 'https://fonts.googleapis.com/css?family=Roboto:100,300,400',
                    },
                ]
                // Stripe's examples are localized to specific languages, but if
                // you wish to have Elements automatically detect your user's locale,
                // use `locale: 'auto'` instead.

            });

            // Floating labels
            var inputs = document.querySelectorAll('.input');
            Array.prototype.forEach.call(inputs, function(input) {
                input.addEventListener('focus', function() {
                    input.classList.add('focused');
                });
                input.addEventListener('blur', function() {
                    input.classList.remove('focused');
                });
                input.addEventListener('keyup', function() {
                    if (input.value.length === 0) {
                        input.classList.add('empty');
                    } else {
                        input.classList.remove('empty');
                    }
                });
            });

            var elementStyles = {
                base: {
                    color: '#888',
                    fontFamily: 'Roboto',
                    fontSize: '18px',
                    fontSmoothing: 'antialiased',
                    fontWeight: 300,
                },
                invalid: {
                    color: '#a70000',

                },
            };

            var elementClasses = {
                focus: 'focused',
                empty: 'empty',
                invalid: 'invalid',
            };

            var cardNumber = elements.create('cardNumber', {
                style: elementStyles,
                classes: elementClasses,
            });
            cardNumber.mount('#stripe-card-number');

            var cardExpiry = elements.create('cardExpiry', {
                style: elementStyles,
                classes: elementClasses,
            });
            cardExpiry.mount('#stripe-card-expiry');

            var cardCvc = elements.create('cardCvc', {
                style: elementStyles,
                classes: elementClasses,
            });
            cardCvc.mount('#stripe-card-cvc');

            registerElements([cardNumber, cardExpiry, cardCvc], 'main_container');

            function registerElements(elements, exampleName) {
                var formClass = '.' + exampleName;
                var example = document.querySelector(formClass);

                var form = example.querySelector('form');
                var resetButton = example.querySelector('a.reset');
                var error = form.querySelector('.error_cc');
                var errorMessage = error.querySelector('.message');



                // Listen for errors from each Element, and show error messages in the UI.
                var savedErrors = {};
                elements.forEach(function(element, idx) {
                    element.on('change', function(event) {
                        if (event.error) {
                            error.classList.add('visible');
                            savedErrors[idx] = event.error.message;
                            errorMessage.innerText = event.error.message;
                        } else {
                            savedErrors[idx] = null;

                            // Loop over the saved errors and find the first one, if any.
                            var nextError = Object.keys(savedErrors)
                                .sort()
                                .reduce(function(maybeFoundError, key) {
                                    return maybeFoundError || savedErrors[key];
                                }, null);

                            if (nextError) {
                                // Now that they've fixed the current error, show another one.
                                errorMessage.innerText = nextError;
                            } else {
                                // The user fixed the last error; no more errors.
                                error.classList.remove('visible');
                            }
                        }
                    });
                });


                $("#payWithCard").click(function(e) {
                    // Call our token request function
                    var ccName = jQuery("#ccName");
                    if(ccName.val().length==0 ){
                        alertMsg("Your card's holder name is incomplete.");
                        return false;
                    }
                    jQuery(".payment_method_ic").empty().closest('.loading_box').show().find('.complete_details').text('Please Wait');

                    var additionalData = {name: ccName.val()};
                    stripe.createToken(elements[0],additionalData).then(function(result) {
                        if (result.error) {
                            // Inform the customer that there was an error
                            error.classList.add('visible');
                            errorMessage.innerText = result.error.message;
                            jQuery('.loading_box').hide();
                        } else {
                            // Send the token to your server
                            stripeTokenHandler(result.token);
                        }
                    });

                    // Prevent form from submitting
                    return false;
                });
                function stripeTokenHandler(token) {

                    process_payment(token.id);




                }
            }
        })();
    </script>

<?php
}
?>
<script type="text/javascript" src="js/html2canvas.min.js"></script>
<script type="text/javascript" src="js/FileSaver.min.js"></script>
<script type="text/javascript" src="js/ccFormat.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.2/jquery.scrollTo.min.js"></script>
<script>



    $("#save_img").click(function() {
        html2canvas($('.main_container').get(0)).then( function (canvas) {
                theCanvas = canvas;
                canvas.toBlob(function(blob) {
                    saveAs(blob, "receipt.png");
                });
            });
    });


    function show_receipt(payment_id){
        var receipt_msg = jQuery(".receipt_msg");
        var currency = jQuery(".enter_amount_sec").find("label").text();
        receipt_msg.find(".name_recip").text(jQuery('#user_name').val());
        receipt_msg.find(".amount_recip").text(currency  +jQuery('#amount_sec').val());
        receipt_msg.find(".reason_recip").text(jQuery('#reason').val());
        receipt_msg.find(".payment_id_recip").text(payment_id);

        jQuery(".detailsside").remove();
        jQuery("#detailscreen").remove();
        jQuery(".details_view").remove();
        jQuery("#invoice_ic").remove();
        jQuery("#vr_back").remove();
        jQuery("#logo").removeClass("logo_details_page").addClass("logo_receipt");

        receipt_msg.fadeIn(300);

        setTimeout(function(){
            jQuery(window).trigger('resize');
        },350);
    }
    function process_payment(token,isPaypal = false){



        var d = '&process_payment=1';
        if(isPaypal){
            jQuery('.loading_box').find('.complete_details').text('Please Wait');
            d = d + '&paypal=1';
        }else{
            jQuery(".payment_method_ic").empty().closest('.loading_box').show().find('.complete_details').text('Please Wait');
        }
        jQuery.ajax({
            type: "POST",
            url: "includes/functions.php",
            data: user_details() + d +'&token='+token,
            success: function(data) {
                if(data.success==1){
                    show_receipt(data.payment_id);
                }else {
                    hideLoad(data.message);
                }
                jQuery('.loading_box').hide();
            },error: function(){
                hideLoad('Please try again!');
                jQuery('.loading_box').hide();
            }
        });
    }

    function user_details(){
        return 'name='+jQuery('#user_name').val()+'&reason='+jQuery('#reason').val()+
            '&amount='+jQuery('#amount_sec').val()+'&email='+jQuery('#email').val()+"&country="+jQuery("#countryselector").text();
    }
    function alertMsg(msg){
        hideLoad(msg);
    }
    function hideLoad(msg = null){
        jQuery('.loading_box').hide();
        if(msg!=null) jQuery('.error_cc').addClass('visible').find('.message').text(msg);

    }

    <?php if($accept['paypal']==1){?>
    paypal.Button.render({
        env: 'production', // Or 'sandbox',

        commit: true, // Show a 'Pay Now' button

        style: {
            label: 'paypal',
            color: 'silver',
            size: 'responsive',
            shape: 'rect',
            tagline: false
        },
        client: {
            sandbox:    '<?php echo $paypal_details['clientId'];?>',
            production: '<?php echo $paypal_details['clientId'];?>'
        },

        payment: function(data, actions) {

            jQuery(".payment_method_ic").empty().html('<div class="icon-PayPal icon-PayPal-dims"></div>').closest('.loading_box').show().find('.complete_details').text('Please complete payment in popup');

            return actions.payment.create({
                transactions: [
                    {
                        amount: {
                            total: jQuery("#amount_sec").val(),
                            currency: '<?php echo $currency; ?>'
                        }
                    }
                ]
            });
        },

        onAuthorize: function(data, actions) {
            return actions.payment.execute().then(function(response) {


                process_payment(response.id,true);



            });
        },

        onCancel: function(data, actions) {
            hideLoad();
        },
        onError: function(data,actions){
            hideLoad();
        }
    }, '#paypal-button');

    <?php } ?>
    jQuery(document).ready(function(){
        var loading_box = jQuery(".loading_box");
        var main_container = jQuery(".main_container");
        var countryselector = jQuery("#countryselector");
        var countrylistSelector = jQuery("#countryselectorlist");
        var detailsScreen = jQuery("#detailscreen");
        var detailsScreenPg = jQuery("#detailscreen_pg");
        var invoice_ic = jQuery("#invoice_ic");
        var footer = jQuery(".footer");
        var country_ul = countrylistSelector.find('ul');
        var nextButton = jQuery("#nextButton");
        var details_view = jQuery(".details_view");
        var payment_methods = jQuery(".payment_methods");
        var details_field = jQuery(".details_field");
        var logo = jQuery("#logo");
        var stage = -1;
        updateboxPos();

        jQuery(window).on('resize',function () {
            updateboxPos();
        });
        function updateboxPos(){
            var wHeight = jQuery(window).height();
            var width = jQuery(window).width();
            var mainContainerHeight = main_container.outerHeight(true);
            var minTop = 8;



            if (width <= 670 && detailsScreen.length && detailsScreen.hasClass("detailsside")) {
                if (detailsScreen.hasClass("details_half_up")) {
                    mainContainerHeight = detailsScreen.outerHeight(true) + 100;
                } else {
                    mainContainerHeight = detailsScreen.outerHeight(true) + 350;
                }
            }

            main_container.stop().animate({'top' : Math.max(minTop, (wHeight - mainContainerHeight)) / 2},'fast');


            if(countrylistSelector.is(':visible')){
                country_ul.getNiceScroll().resize();
            }

        }



        jQuery(".selcountryp").click(function(){
            if(stage!=0) return;
            countryselector.hide();
            logo.removeClass("logo_landing").addClass("logo_details_page");
            detailsScreen.removeClass("selcountryp").addClass("detailsside details_half_up");

            if(footer.length) footer.addClass("footer_ext");

            setTimeout(function(){
                detailsScreenPg.addClass("details_half_up").show();
                invoice_ic.show();
                nextButton.detach().appendTo(detailsScreenPg);
                setTimeout(function(){
                    updateboxPos();
                },100);
                stage = 1;
            },250);



        })

        countryselector.click(function(){
            var cs = countryselector.position();
            countrylistSelector.css({'top':cs.top,'left':cs.left}).show();

            jQuery("#countrylist").niceScroll({
                cursorwidth: "6px", // cursor width in pixel
                smoothscroll: false,
                cursorcolor:"rgba(151,151,151,0.51)"});


        })

        countrylistSelector.find("li").click(function(){
            stage = 0;
            countryselector.text(jQuery(this).text());
            countrylistSelector.fadeOut(200);
        });

        jQuery("#vr_back").click(function(){
            if(loading_box.is(":visible")) return false;
            switch (stage) {
                case 1:
                    nextButton.detach().appendTo(detailsScreen);
                    countryselector.show();
                    logo.removeClass("logo_details_page").addClass("logo_landing");
                    detailsScreenPg.hide();
                    invoice_ic.hide();
                    detailsScreen.removeClass("detailsside details_half_up").addClass("selcountryp");
                    if(footer.length) footer.removeClass("footer_ext");
                    stage = 0;
                    break;
                case 2:
                    details_view.hide();
                    payment_methods.hide();
                    nextButton.fadeIn(200);
                    details_field.fadeIn(200);
                    detailsScreen.addClass("details_half_up");
                    detailsScreenPg.addClass("details_half_up");
                    if(footer.length) footer.removeClass("footer_ex_ext");
                        stage = 1;
                    break;
                case 3:
                    break;
                default:

            }
            setTimeout(function(){
                updateboxPos();
            },250);
        })
        jQuery(".paywithBTC").click(function(){
            jQuery(".payment_method_ic").empty().html(jQuery(this).html()).closest('.loading_box').show().find('.complete_details').text('Please Wait');

            jQuery.ajax({
                type: "POST",
                url: "includes/functions.php",
                data: user_details() + '&create_transaction_coinpayment=1',
                success: function(data) {
                    if(data.success==1){

                        var cp = jQuery("#coinpayments_pay");
                        cp.find("input[name='email']").val(jQuery("#email").val());
                        cp.find("input[name='amountf']").val(jQuery("#amount_sec").val());
                        cp.find("input[name='item_name']").val(jQuery("#reason").val());
                        cp.find("input[name='item_number']").val(data.id);
                        cp.submit();


                    }else {
                        hideLoad(data.message);
                    }
                },error: function(){
                    hideLoad('Please try again!');
                }
            });
        })



        nextButton.click(function(){
            if(stage!=1) return;



                var inputs = ['user_name', 'email', 'reason', 'amount_sec'];
                inputs.forEach(function (name, index, array) {
                    var elem = jQuery("#" + name);
                    var text = jQuery.trim(elem.val());
                    if (text.length == 0) {
                        elem.parent().addClass('error');
                    } else {
                        var attr = elem.attr('data-type');
                        if (typeof attr !== typeof undefined && attr !== false) {
                            if (attr == "email") {
                                if (!isValidEmail(text)) {
                                    elem.parent().addClass('error');
                                    return;
                                }

                            } else if (attr == "number") {
                                if (!isValidNumber(text) || text < 1) {
                                    elem.parent().addClass('error');
                                    return;
                                }

                            }
                        }
                        var holder = jQuery("#" + name + "_holder");
                        if (holder.length) {
                            if (typeof elem.attr('data-pre') !== typeof undefined && elem.attr('data-pre') !== false) {
                                text = elem.attr('data-pre') + " " + text;
                            }
                            holder.text(text);
                        }
                    }


                });

                var user_name = jQuery.trim(jQuery("#user_name").val());
                var email = jQuery.trim(jQuery("#email").val());
                var reason = jQuery.trim(jQuery("#reason").val());

                if (!jQuery(".error").length) {
                    jQuery(this).hide();
                    details_view.fadeIn(200);
                    details_field.hide();
                    detailsScreen.removeClass("details_half_up");
                    detailsScreenPg.removeClass("details_half_up");
                    if(footer.length) footer.addClass("footer_ex_ext");
                    payment_methods.fadeIn(200);
                    stage = 2;
                }

            setTimeout(function(){
                updateboxPos();
            },300);


        });

        jQuery("#ccExp").inputmask("99/99", {"placeholder": "MM/YY"});
        jQuery(document).on("keyup", ".error input", function (e) {

            var attr = jQuery(this).attr('data-type');
            var text = jQuery.trim(jQuery(this).val());
            if(typeof attr !== typeof undefined && attr !== false){
                if(attr == "email"){
                    if(isValidEmail(text)) jQuery(this).parent().removeClass('error');

                }else if(attr == "number"){
                    if(isValidNumber(text) && text>=1) jQuery(this).parent().removeClass('error');

                }
            }else jQuery(this).parent().removeClass('error');
        });


        var reset_input;


        var country_input = "";
        $(document).keydown(function(e){

            if(countrylistSelector.is(":visible")){
                var val = e.key;
                switch (e.which) {
                    case 9:  // Tab
                    case 27: //ESC
                        countrylistSelector.hide();
                        break;
                    case 13:

                        break;
                    default:
                        clearTimeout(reset_input);
                        reset_input = setTimeout(function() {
                            country_input = "";
                        }, 800);


                        country_input  = country_input + val.toLowerCase();

                        countrylistSelector.find("li").each(function(index){
                            var attr = jQuery(this).attr('value');
                            if(attr.startsWith(country_input)){
                                highlight(index);
                                return false;
                            }
                        });

                        break;
                }

            }
        });

        var input_amt = jQuery('input.amount');
        input_amt.keyup(resizeInput).each(resizeInput);


        country_ul.scrollTo(0);
        function highlight(index) {
            setTimeout(function () {

                var visibles         = country_ul.find('li');
                var oldSelected      = country_ul.find('li.selected').removeClass('selected');
                var oldSelectedIndex = visibles.index(oldSelected);

                if (visibles.length > 0) {
                    var selectedIndex = (visibles.length + index) % visibles.length;
                    var selected      = visibles.eq(selectedIndex);

                    var top = 0;
                    if(selected.length>0) {
                        top = selected.position().top;
                        selected.addClass('selected');
                    }
                    if (selectedIndex < oldSelectedIndex && top < 0) {
                        country_ul.scrollTop(country_ul.scrollTop() + top);
                    }
                    if (selectedIndex > oldSelectedIndex) {
                        country_ul.scrollTo(".selected");

                    }

                }
            });
        };


        function resizeInput() {
            a = jQuery(this).val().length;
            if(a==0) a = 3;
            jQuery(this).css('width', Math.max(1,a) * 30 + 20);
        }



        input_amt.keydown(function (e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        function isValidNumber(email) {
            var reg = /^[0-9]\d*(\.\d+)?$/;
            return reg.test(email);
        }
        function isValidEmail(email) {
            var reg = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return reg.test(email);
        }
    });
</script>
</body>
</html>