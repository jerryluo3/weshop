<?php

$config['partner']      = '2088411950533151';
$config['key']          = 'pknm5mkrapz3m8q8c1ocze5vmteh1jjo';
$config['seller_email'] = '970793482@qq.com';
$config['payment_type'] = 1;
$config['transport'] = 'http';
$config['input_charset'] = 'utf-8';
$config['sign_type'] = 'MD5';
$config['notify_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/member/callback/notify';
$config['return_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/member/callback/return';
$config['cacert'] = APPPATH.'third_party/alipay/cacert.pem';

?>