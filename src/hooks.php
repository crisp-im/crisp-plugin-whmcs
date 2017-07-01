<?php

if(!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use Illuminate\Database\Capsule\Manager as Capsule;

function hook_crisp_footer_output($vars)
{
    $website_id = Capsule::table('tbladdonmodules')->select('value')->WHERE('module', '=', 'crisp')->WHERE('setting', '=', 'website_id')->value();
    if(!$website_id) {
        return;
    }

    $output = "<script type='text/javascript'>
        window.CRISP_READY_TRIGGER = function() {
    ";

    // First and last name
    if ($vars['clientsdetails']['firstname']) {
        $name = $vars['clientsdetails']['firstname'] . " " . $vars['clientsdetails']['lastname'];
        $output .= "\$crisp.set('user:nickname', '$name');";
    }

    // Information apart from First & Lastname that should be imported. Must exist in the clientsdetails-array.
    if(isset($vars['clientsdetails'])) {
        $merge_fields = [
            'email',
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
                $output .= "\$crisp.set('user:" . $merge_field . "', '" . $vars['clientsdetails'][$merge_field] . "');";
            }
        }
    }

    $output .= "};</script>
    <script type='text/javascript'>CRISP_WEBSITE_ID = '$website_id';(function(){d=document;s=d.createElement('script');s.src='https://client.crisp.chat/l.js';s.async=1;d.getElementsByTagName('head')[0].appendChild(s);})();</script>
    ";

    return $output;
}
add_hook('ClientAreaFooterOutput', 1, 'hook_crisp_footer_output');
