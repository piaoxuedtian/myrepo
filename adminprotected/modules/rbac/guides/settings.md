# RBAC module settings in main setting file

~~~
[php]
'modules' => array(
    'rbac' => array(
        'layout' => 'main',
        'debug' => true,
        'disabledScanFrontend' => true,
        'disabledScanModules' => array('gii', 'abc'),
        'userTable' => 'user',
        'userTableId' => 'id',
        'userTableName' => 'username',
        'pageSize' => 20,
        'language' => 'zh_cn',
        'notAuthorizedView' => null,
    ),
    'components' => array(
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
            'itemTable' => 'AuthItem',
            'assignmentTable' => 'AuthAssignment',
            'itemChildTable' => 'AuthItemChild',
        ),
    ),
)
~~~