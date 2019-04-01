<?php
return [
	// HTTP 请求的超时时间（秒）
	'timeout' => 5.0,

	// 默认发送配置
	'default' => [
	    // 网关调用策略，默认：顺序调用
	    'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

	    // 默认可用的发送网关
	    'gateways' => [
	        'aliyun',
	    ],
	],
	// 可用的网关配置
	'gateways' => [
	    'errorlog' => [
	        'file' => '/tmp/easy-sms.log',
	    ],    
	    'qcloud' => [
	        'sdk_app_id' => '1400194359', // SDK APP ID
	        'app_key' => 'befc08e9aaf4d4c4bfe149a2725f9fe6', // APP KEY
	        'sign_name' => '', // 短信签名，如果使用默认签名，该字段可缺省（对应官方文档中的sign）
	    ],
	    'yunpian' => [
	        'api_key' => '824f0ff2f71cab52936axxxxxxxxxx',
	    ],
	    'aliyun' => [
	        'access_key_id' => '',
	        'access_key_secret' => '',
	        'sign_name' => '',
	    ],
	    //...
	],
];