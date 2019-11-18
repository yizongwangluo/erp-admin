<?php

$pay_host = base_url();
$config['agent_id']    = 2105318;
$config['agent_id_pc'] = 2107147;
$config['pay_url']     = 'https://pay.heepay.com/Payment/Index.aspx';
$config['query_url']   = 'https://query.heepay.com/Payment/Query.aspx';
$config['sign_key']    = '56480F871D954C2EA23F842B';
$config['sign_key_pc']    = '2CD5BE97BE5243EA8466632D';
$config['notify_url']  = $pay_host . "payment_gateway/notify/heepay";
$config['return_url']  = $pay_host . "payment_gateway/back/heepay";
$config['agent']       = [
    '2105318' => '56480F871D954C2EA23F842B',
    '2107147' => '2CD5BE97BE5243EA8466632D'
];

return $config;

?>
