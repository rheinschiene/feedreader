<?php

return [
	'class' => 'yii\db\Connection',
    	'dsn' => 'mysql:host=' . getenv("MYSQL_HOST") . ';dbname=' . getenv("MYSQL_DATABASE"),
    	'username' => getenv("MYSQL_USER"),
    	'password' => trim(file_get_contents(getenv("MYSQL_PASSWORD_FILE"))),
    	'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
