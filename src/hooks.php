<?php

if(!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Database\Capsule;

function hook_crisp_footer_output($vars)
{
    $website_id = Capsule::table('tbladdonmodules')->where('module', 'crisp')->where('setting', 'website_id')->value('value');
    $website_verify = Capsule::table('tbladdonmodules')->where('module', 'crisp')->where('setting', 'website_verify')->value('value');
    
    if(!$website_id) {
        return;
    }

    $output = "<script type='text/javascript'>
        window.CRISP_READY_TRIGGER = function() {
    ";

    if ($vars['clientsdetails']['email'] && empty($website_verify)) {
        $email = $vars['clientsdetails']['email'];
        $output .= "\$crisp.push(['set', 'user:email', ['$email']]);";
    } else if ($vars['clientsdetails']['email']) {
        $email = $vars['clientsdetails']['email'];
        $hmac = hash_hmac("sha256", $email, $website_verify);
        $output .= "\$crisp.push(['set', 'user:email', ['$email', '$hmac']]);";
    }

    // First and last name
    if ($vars['clientsdetails']['firstname']) {
        $name = $vars['clientsdetails']['firstname'] . " " . $vars['clientsdetails']['lastname'];
        $output .= "\$crisp.set('user:nickname', '$name');";
    }

    // Information apart from First & Lastname that should be imported. Must exist in the clientsdetails-array.
    if(isset($vars['clientsdetails'])) {
        $merge_fields = [
            'id',
            'companyname',
            'address1',
            'address2',
            'city',
            'state',
            'postcode',
            'country',
            'phonenumber'
        ];

        foreach($merge_fields as $merge_field) {
            if(isset($vars['clientsdetails'][$merge_field])) {
                $output .= "\$crisp.set('session:data', ['" . $merge_field . "', '" . $vars['clientsdetails'][$merge_field] . "']);";
            }
        }
    }

    $output .= "};</script>
    <script type='text/javascript'>CRISP_WEBSITE_ID = '$website_id';(function(){d=document;s=d.createElement('script');s.src='https://client.crisp.chat/l.js';s.async=1;d.getElementsByTagName('head')[0].appendChild(s);})();</script>
    ";

    return $output;
}
add_hook('ClientAreaFooterOutput', 1, 'hook_crisp_footer_output');
