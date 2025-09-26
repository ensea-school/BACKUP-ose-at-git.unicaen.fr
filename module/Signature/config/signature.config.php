<?php

namespace Signature;

use Application\Provider\Privileges;
use Framework\Authorize\Authorize;
use Signature\Command\UpdateSignaturesContratsProcessesCommand;
use Signature\Command\UpdateSignaturesContratsProcessesCommandFactory;
use Signature\Controller\SignatureController;
use Signature\Controller\SignatureControllerFactory;
use Signature\Controller\SignatureFlowController;
use Signature\Controller\SignatureFlowControllerFactory;
use Signature\Service\SignatureFlowService;
use Signature\Service\SignatureFlowServiceFactory;
use Signature\Service\SignatureFlowStepService;
use Signature\Service\SignatureFlowStepServiceFactory;

return [


    'routes' => [
        'signature-flow' => [
            'route'         => '/signature-flow',
            'controller'    => SignatureFlowController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'saisir-circuit'    => [
                    'route'      => '/saisir-circuit/[:signatureFlow]',
                    'controller' => SignatureFlowController::class,
                    'action'     => 'saisir-circuit',
                ],
                'supprimer-circuit' => [
                    'route'      => '/supprimer-circuit/:signatureFlow',
                    'controller' => SignatureFlowController::class,
                    'action'     => 'supprimer-circuit',
                ],
                'saisir-etape'      => [
                    'route'      => '/saisir-etape/flow/:signatureFlow/etape/[:signatureFlowStep]',
                    'controller' => SignatureFlowController::class,
                    'action'     => 'saisir-etape',
                ],
                'supprimer-etape'   => [
                    'route'      => '/supprimer-etape/:signatureFlow/:signatureFlowStep',
                    'controller' => SignatureFlowController::class,
                    'action'     => 'supprimer-etape',
                ],

            ],
        ],
        'signature'      => [
            'route'         => '/signature',
            'controller'    => SignatureController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'get-document' => [
                    'route'         => '/:signature/get-document',
                    'controller'    => SignatureController::class,
                    'action'        => 'get-document',
                    'may_terminate' => true,
                ],
            ],
        ],
    ],

    'navigation'  => [
        'administration' => [
            'pages' => [
                'signature' => [
                    'pages' => [
                        'signature-flow' => [
                            'label'      => "Gestion des circuits de signatures",
                            'title'      => "Gestion des circuits de signatures",
                            'route'      => 'signature-flow',
                            'resource'   => Authorize::controllerResource(SignatureFlowController::class, 'index'),
                            'withtarget' => true,
                            'order'      => 10,
                        ],
                    ],
                ],
            ],
        ],
    ],
    /* Droits d'accès */
    'guards'      => [
        [
            'controller' => SignatureController::class,
            'action'     => ['index', 'get-document'],
            'privileges' => [
                Privileges::CONTRAT_ENVOYER_SIGNATURE_ELECTRONIQUE,
            ],

        ],
        [
            'controller' => SignatureFlowController::class,
            'action'     => ['index', 'saisir-circuit', 'supprimer-circuit', 'saisir-etape', 'supprimer-etape'],
            'privileges' => [
                Privileges::SIGNATURE_FLOW_EDITION,
            ],

        ],


    ],

    /* Déclaration du contrôleur */
    'controllers' => [
        SignatureController::class     => SignatureControllerFactory::class,
        SignatureFlowController::class => SignatureFlowControllerFactory::class,

    ],

    'forms' => [
        Form\SignatureFlowForm::class     => Form\SignatureFlowFormFactory::class,
        Form\SignatureFlowStepForm::class => Form\SignatureFlowStepFormFactory::class,
    ],

    'services' => [
        SignatureFlowService::class                     => SignatureFlowServiceFactory::class,
        SignatureFlowStepService::class                 => SignatureFlowStepServiceFactory::class,
        UpdateSignaturesContratsProcessesCommand::class => UpdateSignaturesContratsProcessesCommandFactory::class,
    ],

    'laminas-cli' => [
        'commands' => [
            'update-signatures-contrats-processes' => Command\UpdateSignaturesContratsProcessesCommand::class
        ],
    ],


];