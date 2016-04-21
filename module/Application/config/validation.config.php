<?php

namespace Application;

return [

    'controllers'     => [
        'invokables' => [
            'Application\Controller\Validation' => Controller\ValidationController::class,
        ],
    ],
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Validation'         => [],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
            'ApplicationTypeValidation'      => Service\TypeValidation::class,
            'ApplicationValidation'          => Service\Validation::class,
            'ValidationEnseignementRule'     => Rule\Validation\Enseignement\ValidationRule::class,
            'ValidationReferentielRule'      => Rule\Validation\Referentiel\ValidationRule::class,
            'ValidationAssertion'            => Assertion\ValidationAssertionProxy::class,
            'ValidationServiceAssertion'     => Assertion\ValidationServiceAssertion::class,
            'ValidationReferentielAssertion' => Assertion\ValidationReferentielAssertion::class,
            'processusValidation'            => Processus\ValidationProcessus::class,
        ],
        'factories'  => [
            'ValidationEnseignementPrevuRule'   => Rule\Validation\Enseignement\Prevu\RuleFactory::class,
            'ValidationEnseignementRealiseRule' => Rule\Validation\Enseignement\Realise\RuleFactory::class,
            'ValidationReferentielPrevuRule'    => Rule\Validation\Referentiel\Prevu\RuleFactory::class,
            'ValidationReferentielRealiseRule'  => Rule\Validation\Referentiel\Realise\RuleFactory::class,
        ],
    ],
];
