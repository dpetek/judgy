<?php

return array(
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/user',
                    'defaults' => array(
                        'controller' => 'Api\Controller\User',
                    )
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'post' => array(
                        'type' => 'Method',
                        'options' => array(
                            'verb' => 'post'
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'create' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '.:format',
                                    'constraints' => array(
                                        'format' => 'json'
                                    )
                                )
                            ),
                            'action' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:action:.:format',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9]*',
                                        'format' => 'json'
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            'tags' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/tag',
                    'defaults' => array(
                        'controller' => 'Api\Controller\Tag',
                    )
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'get' => array(
                        'type' => 'Method',
                        'options' => array(
                            'verb' => 'get'
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'action' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:action:.:format',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z0-9]+',
                                        'format' => 'json'
                                    )
                                )
                            )
                        )
                    ),
                    'post' => array(
                        'type' => 'Method',
                        'options' => array(
                            'verb' => 'post'
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'create' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '.:format',
                                    'constraints' => array(
                                        'format' => 'json'
                                    )
                                )
                            ),
                            'action' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:action:.:format',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9]*',
                                        'format' => 'json'
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            'api-rating' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/rating',
                    'defaults' => array(
                        'controller' => 'Api\Controller\Rating',
                    )
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'post' => array(
                        'type' => 'Method',
                        'options' => array(
                            'verb' => 'post'
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'create' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '.:format',
                                    'constraints' => array(
                                        'format' => 'json'
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            'problems-api' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/problems/:type',
                    'defaults' => array(
                        'controller' => 'Api\Controller\Problems',
                    )
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'post' => array(
                        'type' => 'Method',
                        'options' => array(
                            'verb' => 'post'
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'create' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '.:format',
                                    'constraints' => array(
                                        'format' => 'json'
                                    )
                                )
                            ),
                            'action' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:action:.:format',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9]*',
                                        'format' => 'json'
                                    )
                                )
                            ),
                            'id-action' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:id/:action:.:format',
                                    'constraints' => array(
                                        'id' => '[a-zA-Z0-9]{24}',
                                        'action' => '[a-zA-Z][a-zA-Z0-9]*',
                                        'format' => 'json'
                                    )
                                )
                            )
                        )
                    )
                )
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Api\Controller\User' => 'Api\Controller\UserController',
            'Api\Controller\Problems' => 'Api\Controller\ProblemsController',
            'Api\Controller\Tag' => 'Api\Controller\TagController',
            'Api\Controller\Rating' => 'Api\Controller\RatingController',
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
