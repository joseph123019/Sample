<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
// Paypal library configuration
// ------------------------------------------------------------------------

// PayPal environment, Sandbox or Live
$config['sandbox'] = FALSE; // FALSE for live environment

// PayPal business email
//$config['business'] = 'dev3.storemote+merchant@gmail.com';
$config['business'] = 'payment@storemote.com';

// What is the default currency?
$config['paypal_lib_currency_code'] = 'USD';

// If (and where) to log ipn response in a file
$config['paypal_lib_ipn_log'] = TRUE;
$config['paypal_lib_ipn_log_file'] = BASEPATH . 'logs/paypal_ipn.log';