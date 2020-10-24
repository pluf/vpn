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
    array( // Create (error if item is existed)
        'regex' => '#^/accounts/(?P<parentId>\d+)/limits$#',
        'model' => 'Pluf_Views',
        'method' => 'createManyToOne',
        'http-method' => 'PUT',
        'params' => array(
            'parent' => 'Vpn_Account',
            'parentKey' => 'account_id',
            'model' => 'Vpn_AccountLimit'
            // 'precond' => function($request, $object, $parent) -> {false, true} | throw exception
        ),
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // Create (update if item is existed)
        'regex' => '#^/accounts/(?P<parentId>\d+)/limits$#',
        'model' => 'Vpn_Views_AccountLimit',
        'method' => 'createOrUpdate',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::loginRequired'
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
        ),
        'precond' => array(
            'User_Precondition::loginRequired'
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
        ),
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // Read (by key)
        'regex' => '#^/accounts/(?P<parentId>\d+)/limits/(?P<modelKey>[^/]+)$#',
        'model' => 'Vpn_Views_AccountLimit',
        'method' => 'getByKey',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
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
        ),
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // Update (by key)
        'regex' => '#^/accounts/(?P<parentId>\d+)/limits/(?P<modelKey>[^/]+)$#',
        'model' => 'Vpn_Views_AccountLimit',
        'method' => 'updateByKey',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
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
        ),
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    // ************************************************************* AccountLimit (by login of the account)
    array( // Create (error if item is existed)
        'regex' => '#^/accounts/(?P<login>[^/]+)/limits$#',
        'model' => 'Vpn_Views_AccountLimit',
        'method' => 'create',
        'http-method' => 'PUT',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // Create (update if item is existed)
        'regex' => '#^/accounts/(?P<login>[^/]+)/limits$#',
        'model' => 'Vpn_Views_AccountLimit',
        'method' => 'createOrUpdate',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // Read (list)
        'regex' => '#^/accounts/(?P<login>[^/]+)/limits$#',
        'model' => 'Vpn_Views_AccountLimit',
        'method' => 'find',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // Read
        'regex' => '#^/accounts/(?P<login>[^/]+)/limits/(?P<modelId>\d+)$#',
        'model' => 'Vpn_Views_AccountLimit',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // Read (by key)
        'regex' => '#^/accounts/(?P<login>[^/]+)/limits/(?P<modelKey>[^/]+)$#',
        'model' => 'Vpn_Views_AccountLimit',
        'method' => 'getByKey',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // Update
        'regex' => '#^/accounts/(?P<login>[^/]+)/limits/(?P<modelId>\d+)$#',
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
        ),
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // Update (by key)
        'regex' => '#^/accounts/(?P<login>[^/]+)/limits/(?P<modelKey>[^/]+)$#',
        'model' => 'Vpn_Views_AccountLimit',
        'method' => 'updateByKey',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    ),
    array( // Delete
        'regex' => '#^/accounts/(?P<login>[^/]+)/limits/(?P<modelId>\d+)$#',
        'model' => 'Vpn_Views_AccountLimit',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'User_Precondition::loginRequired'
        )
    )
);
