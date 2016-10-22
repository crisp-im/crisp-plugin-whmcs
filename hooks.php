<?php

if (!defined("WHMCS"))
{
	die("This file cannot be accessed directly");
}

function hook_crisp_footer_output($vars)
{

	$website_id = null;
	$output = "";

	$sql = "SELECT setting, value FROM `tbladdonmodules` WHERE setting IN ('website_id') AND module = 'crisp';";
	$result = mysql_query($sql);
	if (mysql_num_rows($result) > 0)
	{
		while ($data = mysql_fetch_assoc($result))
		{
			if ($data['setting'] === 'website_id')
			{
				$website_id = (string)$data['value'];
			}
		}
	}

	if (!$website_id) return false;

	$output .= "<script type='text/javascript'>";


	$output .= "window.CRISP_READY_TRIGGER = function() {";

	if (isset($vars['clientsdetails']))
	{
		if ($vars['clientsdetails']['email']) {
			$email = $vars['clientsdetails']['email'];
			$output .= "\$crisp.set('user:email', '$email');";
		}

		if ($vars['clientsdetails']['firstname']) {
			$name = $vars['clientsdetails']['firstname'] . " " . $vars['clientsdetails']['lastname'];
			$output .= "\$crisp.set('user:nickname', '$name');";
		}

		if ($vars['clientsdetails']['id']) {
			$id = $vars['clientsdetails']['id'];
			$output .= "\$crisp.set('session:data', ['id', '$id']);";
		}

		if ($vars['clientsdetails']['companyname']) {
			$companyname = $vars['clientsdetails']['companyname'];
			$output .= "\$crisp.set('session:data', ['companyname', '$companyname']);";
		}

		if ($vars['clientsdetails']['address1']) {
			$address1 = $vars['clientsdetails']['address1'];
			$output .= "\$crisp.set('session:data', ['address1', '$address1']);";
		}

		if ($vars['clientsdetails']['address2']) {
			$address2 = $vars['clientsdetails']['address2'];
			$output .= "\$crisp.set('session:data', ['address2', '$address2']);";
		}

		if ($vars['clientsdetails']['city']) {
			$city = $vars['clientsdetails']['city'];
			$output .= "\$crisp.set('session:data', ['city', '$city']);";
		}

		if ($vars['clientsdetails']['state']) {
			$state = $vars['clientsdetails']['state'];
			$output .= "\$crisp.set('session:data', ['state', '$state']);";
		}

		if ($vars['clientsdetails']['postcode']) {
			$postcode = $vars['clientsdetails']['postcode'];
			$output .= "\$crisp.set('session:data', ['postcode', '$postcode']);";
		}

		if ($vars['clientsdetails']['country']) {
			$country = $vars['clientsdetails']['country'];
			$output .= "\$crisp.set('session:data', ['country', '$country']);";
		}

		if ($vars['clientsdetails']['phonenumber']) {
			$phonenumber = $vars['clientsdetails']['phonenumber'];
			$output .= "\$crisp.set('session:data', ['phonenumber', '$phonenumber']);";
		}
	}

	$output .= "};</script>";

	$output .= "
  	<script type='text/javascript'>CRISP_WEBSITE_ID = '$website_id';(function(){d=document;s=d.createElement('script');s.src='https://client.crisp.im/l.js';s.async=1;d.getElementsByTagName('head')[0].appendChild(s);})();</script>
  ";

	return $output;
}

add_hook('ClientAreaFooterOutput', 1, 'hook_crisp_footer_output');
