<?php
//Check if cache directories exist and create if not
$dataPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data';
$hydratorCacheDir =  $dataPath
    . DIRECTORY_SEPARATOR
    . 'cache'
    . DIRECTORY_SEPARATOR
    . 'DoctrineMongoODMModule'
    . DIRECTORY_SEPARATOR
    . 'Hydrator';
if (!is_dir($hydratorCacheDir)) {
    mkdir($hydratorCacheDir, 0777, true);
}
$proxyCacheDir = $dataPath
    . DIRECTORY_SEPARATOR
    . 'cache'
    . DIRECTORY_SEPARATOR
    . 'DoctrineMongoODMModule'
    . DIRECTORY_SEPARATOR
    . 'Proxy';
if (!is_dir($proxyCacheDir)) {
    mkdir($proxyCacheDir, 0777, true);
}
return array(
    'doctrine' => array(
        'connection' => array(
            'odm_default' => array(
                'server'    => '127.0.0.1',
                'port'      => '27017',
                'dbname'    => null,
                'options'   => array(
                    'readPreference' => MongoClient::RP_SECONDARY_PREFERRED,
                    'w' => 1
                )
            )
        ),
        'configuration' => array(
            'odm_default' => array(
                'driver'             => 'odm_default',
                'generate_proxies'   => true,
                'proxy_dir'          => $proxyCacheDir,
                'proxy_namespace'    => 'DoctrineMongoODMModule\Proxy',
                'generate_hydrators' => true,
                'hydrator_dir'       => $hydratorCacheDir,
                'hydrator_namespace' => 'DoctrineMongoODMModule\Hydrator',
                'default_db'         => '', //'.databaseNotDefined', // Dot in name will cause error on \MongoClient
                'metadata_cache'     => 'array',
            )
        ),
        'documentmanager' => array(
            'odm_default' => array(
                'connection'    => 'odm_default',
                'configuration' => 'odm_default',
                'eventmanager' => 'odm_default'
            )
        ),
        'eventmanager' => array(
            'odm_default' => array(
                'subscribers' => array()
            )
        ),
    ),
);