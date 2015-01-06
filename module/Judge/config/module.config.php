<?php

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Judge\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /judge/:controller/:action
            'judge' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/judge',
                    'defaults' => array(
                        'controller'    => 'Judge\Controller\Index',
                    ),
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:action',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'profile' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile',
                    'defaults' => array(
                        'controller' => 'Judge\Controller\Profile',
                    )
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'id' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:id',
                            'constraints' => array(
                                'id' => '[a-zA-Z0-9]{24}',
                            ),
                            'defaults' => array(
                                'action' => 'profile',
                            )
                        )
                    )
                )
            ),
            'arena' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/arena',
                    'defaults' => array(
                        'controller' => 'Judge\Controller\Arena',
                    )
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:action',
                            'constraints' => array(
                                'action' => '[a-zA-Z]+'
                            )
                        )
                    )
                )
            ),
            'problems-view' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/problems',
                    'defaults' => array(
                        'controller' => 'Judge\Controller\Problems'
                    )
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:type/:action[/:id]',
                            'constraints' => array(
                                'type' => 'misc|algorithm|arena'
                            )
                        )
                    ),
                )
            )
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
            'Judge\Controller\Index' => 'Judge\Controller\IndexController',
            'Judge\Controller\Problems' => 'Judge\Controller\ProblemsController',
            'Judge\Controller\Profile' => 'Judge\Controller\ProfileController',

        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'judge/index/index' => __DIR__ . '/../view/judge/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'judge_documents' => array(
                'class' =>'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Judge/Document')
            ),
            'odm_default' => array(
                'drivers' => array(
                    'Judge\Document' => 'judge_documents',
                )
            ),
        )
    ),
);
