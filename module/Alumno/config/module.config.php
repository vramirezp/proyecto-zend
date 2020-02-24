<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Alumno;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'alumno' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/alumno[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ]
                ]
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            Controller\AlumnoController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'alumno' => __DIR__ . '/../view',
        ],
    ],
];