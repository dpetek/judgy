<?php

return array(
    'leptir' => array(
        'brokers' => array(
            array(
                'type' => 'mongodb',
                'connection' => array(
                    'host' => 'localhost',
                    'port' => 27017,
                    'database' => 'leptir',
                    'collection' => 'algorithm_queue'
                ),
                'configuration' => array(
                    'priority' => 1,
                    'name' => 'Algorithm queue'
                )
            ),
            array(
                'type' => 'mongodb',
                'connection' => array(
                    'host' => 'localhost',
                    'port' => 27017,
                    'database' => 'leptir',
                    'collection' => 'arena_queue'
                ),
                'configuration' => array(
                    'priority' => 2,
                    'name' => 'Arena queue'
                )
            ),
        ),
        'logger' => array(
            'writers' => array(
                'stream' => array(
                    'name' => 'stream',
                    'options' => array(
                        'stream' => '/var/log/leptir.log',
                        'formatter' => array(
                            'name' => 'simple',
                            'options' => array(
                                'dateTimeFormat' => 'Y-m-d H:i:s'
                            )
                        )
                    )
                ),
            ),
        ),
        'daemon' => array(
            'configuration' => array(
                'task_execution_time'       => 10,
                'number_of_workers'         => 1,
                'empty_queue_sleep_time'    => 0.5,
                'workers_active_sleep_time' => 0.2
            )
        ),
        'meta_storage' => array(
            'storage' => array(
                array(
                    'type'       => 'mongodb',
                    'connection' => array(
                        'host'       => 'localhost',
                        'port'       => 27017,
                        'database'   => 'leptir',
                        'collection' => 'task_info',
                        'options'    => array()
                    )
                )
            )
        ),
        'tasks_stats' => array(
            'storage' => array(
                array(
                    'type' => 'mongodb',
                    'connection' => array(
                        'host' => 'localhost',
                        'port' => 27017,
                        'database' => 'leptir',
                        'collection' => 'task_stats',
                        'options' => array()
                    )
                )
            )
        )
    ),
);
