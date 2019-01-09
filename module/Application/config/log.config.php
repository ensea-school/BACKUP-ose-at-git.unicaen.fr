<?php

namespace Application;

use Zend\Log\Logger;

return [
    'service_manager' => [
        'factories'    => [
            'logger' => 'Zend\Log\LoggerServiceFactory'
        ],
    ],
    'log' => array(
        'writers' => array(
            'stream' => array(
                'name' => 'stream',
                'options' => array(
                    'stream' => '/tmp/ose.log',
                    'filters' => array(
                        'priority' => array(
                            'name' => 'priority',
                            'options' => array(
                                'priority' => Logger::DEBUG
                            )
                        ),
//                        'suppress' => array(
//                            'name' => 'suppress',
//                            'options' => array(
//                                'suppress' => false
//                            )
//                        )
                    ),
                    'formatter' => array(
                        'name' => 'simple',
                        'options' => array(
                            'dateTimeFormat' => 'd-m-Y H:i:s'
                        )
                    ),
                )
            )
        ),
//        'processors' => array(
//            array(
//                'name' => 'backtrace',
//            ),
//        ),
    ),
];