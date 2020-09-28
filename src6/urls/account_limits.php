<?php
return array(
    // ************************************************************* Schema
    array(
        'regex' => '#^/limits/schema$#',
        'model' => 'Pluf_Views',
        'method' => 'getSchema',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Vpn_AccountLimit'
        )
    ),
    // ************************************************************* AccountLimit
    array( // Create
        'regex' => '#^/accounts/(?P<parentId>\d+)/limits$#',
        'model' => 'Pluf_Views',
        'method' => 'createManyToOne',
        'http-method' => 'POST',
        'params' => array(
            'parent' => 'Vpn_Account',
            'parentKey' => 'account_id',
            'model' => 'Vpn_AccountLimit'
            // 'precond' => function($request, $object, $parent) -> {false, true} | throw exception
        )
    ),
    array( // Read (list)
        'regex' => '#^/accounts/(?P<parentId>\d+)/limits$#',
        'model' => 'Pluf_Views',
        'method' => 'findManyToOne',
        'http-method' => 'GET',
        'params' => array(
            'parent' => 'Vpn_Account',
            'parentKey' => 'account_id',
            'model' => 'Vpn_AccountLimit'
        )
    ),
    array( // Read
        'regex' => '#^/accounts/(?P<parentId>\d+)/limits/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getManyToOne',
        'http-method' => 'GET',
        'params' => array(
            'parent' => 'Vpn_Account',
            'parentKey' => 'account_id',
            'model' => 'Vpn_AccountLimit'
        )
    ),
    array( // Read (by key)
        'regex' => '#^/orders/(?P<parentId>\d+)/limits/(?P<modelKey>[^/]+)$#',
        'model' => 'Vpn_Views_AccountLimit',
        'method' => 'getByKey',
        'http-method' => 'GET'
    ),
    array( // Update
        'regex' => '#^/accounts/(?P<parentId>\d+)/limits/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateManyToOne',
        'http-method' => array(
            'POST',
            'PUT'
        ),
        'params' => array(
            'parent' => 'Vpn_Account',
            'parentKey' => 'account_id',
            'model' => 'Vpn_AccountLimit'
            // 'precond' => function($request, $object, $parent) -> {false, true} | throw exception
        )
    ),
    array( // Update (by key)
        'regex' => '#^/orders/(?P<parentId>\d+)/limits/(?P<modelKey>[^/]+)$#',
        'model' => 'Vpn_Views_AccountLimit',
        'method' => 'updateByKey',
        'http-method' => 'POST'
    ),
    array( // Delete
        'regex' => '#^/accounts/(?P<parentId>\d+)/limits/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteManyToOne',
        'http-method' => 'DELETE',
        'params' => array(
            'parent' => 'Vpn_Account',
            'parentKey' => 'account_id',
            'model' => 'Vpn_AccountLimit'
            // 'precond' => function($request, $object, $parent) -> {false, true} | throw exception
        )
    )
);
