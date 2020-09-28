<?php
$paths = array(
    'urls/account.php',
    'urls/account_limits.php'
);

$vpnApi = array();

foreach ($paths as $path){
    $myApi = include $path;
    $vpnApi = array_merge($vpnApi, $myApi);
}

return $vpnApi;
