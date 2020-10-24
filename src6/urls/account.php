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
    array( // Create
        'regex' => '#^/accounts$#',
        'model' => 'Vpn_Views_Account',
        'method' => 'createAccount',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // Read
        'regex' => '#^/accounts/(?P<modelId>\d+)$#',
        'model' => 'Vpn_Views_Account',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // Read (list)
        'regex' => '#^/accounts$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Vpn_Account',
            'sql' => 'is_deleted=false'
        ),
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // Delete
        'regex' => '#^/accounts/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteObject',
        'http-method' => 'DELETE',
        'params' => array(
            'model' => 'Vpn_Account',
            'permanently' => false
        ),
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // Update
        'regex' => '#^/accounts/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateObject',
        'http-method' => 'POST',
        'params' => array(
            'model' => 'Vpn_Account'
        ),
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    // ************************************************************* Account (by login of the account)
    array( // Read
        'regex' => '#^/accounts/(?P<login>[^/]+)$#',
        'model' => 'Vpn_Views_Account',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    )
);