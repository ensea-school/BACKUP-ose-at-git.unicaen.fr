<?php

namespace Application;

use Laminas\Log\Logger;

return [
    'service_manager' => [
        'factories' => [
            'logger' => 'Laminas\Log\LoggerServiceFactory',
        ],
    ],
    'log'             => [
        'writers' => [
            'stream' => [
                'name'    => 'stream',
                'options' => [
                    'stream'    => '/tmp/ose.log',
                    'filters'   => [
                        'priority' => [
                            'name'    => 'priority',
                            'options' => [
                                'priority' => Logger::DEBUG,
                            ],
                        ],
                        //                        'suppress' => array(
                        //                            'name' => 'suppress',
                        //                            'options' => array(
                        //                                'suppress' => false
                        //                            )
                        //                        )
                    ],
                    'formatter' => [
                        'name'    => 'simple',
                        'options' => [
                            'dateTimeFormat' => 'd-m-Y H:i:s',
                        ],
                    ],
                ],
            ],
        ],
        //        'processors' => array(
        //            array(
        //                'name' => 'backtrace',
        //            ),
        //        ),
    ],
];