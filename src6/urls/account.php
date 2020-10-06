<?php
return array(
    // ************************************************************* Schema
    array(
        'regex' => '#^/accounts/schema$#',
        'model' => 'Pluf_Views',
        'method' => 'getSchema',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Vpn_Account'
        )
    ),
    // ************************************************************* Account
    array( // Read (list)
        'regex' => '#^/accounts$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Vpn_Account',
            'sql' => 'is_deleted=false'
        )
    ),
    array( // Create
        'regex' => '#^/accounts$#',
        'model' => 'Vpn_Views_Account',
        'method' => 'createAccount',
        'http-method' => 'POST',
        'precond' => array()
    ),
    array( // Read
        'regex' => '#^/accounts/(?P<modelId>\d+)$#',
        'model' => 'Vpn_Views_Account',
        'method' => 'get',
        'http-method' => 'GET'
    ),
    array( // Delete
        'regex' => '#^/accounts/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteObject',
        'http-method' => 'DELETE',
        'precond' => array(),
        'params' => array(
            'model' => 'Vpn_Account',
            'permanently' => false
        )
    ),
    array( // Update 
        'regex' => '#^/accounts/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateObject',
        'http-method' => 'POST',
        'precond' => array(),
        'params' => array(
            'model' => 'Vpn_Account'
        )
    )
);