<?php

return [
	'class' => 'yii\db\Connection',
    	'dsn' => 'mysql:host=127.0.0.1;dbname=' . getenv("MYSQL_DATABASE"),
    	'username' => 'root',
    	'password' => getenv("MYSQL_ROOT_PASSWORD"),
    	'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
