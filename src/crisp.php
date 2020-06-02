<?php

function crisp_config()
{
	return array(
		"name" => "Crisp Livechat",
		"description" => "Crisp is free and beautiful livechat to interact with customers. Mobile applications. Customize your chatbox to fit your website design. Get Crisp account here: https://app.crisp.chat",
		"version" => "1.1",
		"author" => "Crisp IM",
		"fields" => array(
			"website_id" => array(
				"FriendlyName" => "Licence Key",
				"Type" => "text",
				"Size" => "32",
				"Description" => "",
				"Default" => ""
			),
			"website_verify" => array(
				"FriendlyName" => "Secret Key",
				"Type" => "text",
				"Size" => "32",
				"Description" => "(Optional) Used for e-mail identity verification",
				"Default" => ""
			)
		)
	);
}
