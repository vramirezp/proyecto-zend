<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Alumno;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Segment;

return [
    /*'controllers' => [
        'factories' => [
            Controller\AlumnoController::class => InvokableFactory::class,
        ],
    ],*/
    'router' => [
        'routes' => [
            'alumno' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/alumno[/:action][/:run][/:nombre][/:apellido]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'run'    => '[0-9a-zA-Z.-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\AlumnoController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'alumno' => __DIR__ . '/../view',
        ],
        'strategies' => array('ViewJsonStrategy',),
    ],
];