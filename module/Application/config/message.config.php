<?php

namespace Application;

use Application\Service\Message\MessageConstants as MsgConst;
use Application\Service\Message\Message;
use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;


return [
    'message' => [
        'messages' => [
            [
                'id' => MsgConst::ID_DONNEES_PERSO_SAISIES,
                'contents' => [
                    ComposanteRole::ROLE_ID  => "Les données personnelles de {intervenant} ont été saisies.", // 1er contenu = par défaut
                    IntervenantRole::ROLE_ID => "Vos données personnelles ont été saisies.",
                ],
            ],
            [
                'id' => MsgConst::ID_DONNEES_PERSO_PAS_SAISIES,
                'contents' => [
                    ComposanteRole::ROLE_ID  => "Les données personnelles de {intervenant} n'ont pas été saisies.",
                    IntervenantRole::ROLE_ID => "Vos données personnelles n'ont pas été saisies.",
                ],
            ],
            [
                'id' => MsgConst::ID_DONNEES_PERSO_VALIDEES,
                'contents' => [
                    Message::ROLE_ID_DEFAULT => "Les données personnelles de {intervenant} ont été validées. {validationDetails}",
                    IntervenantRole::ROLE_ID => "Vos données personnelles ont été validées. {validationDetails}",
                ],
            ],
            [
                'id' => MsgConst::ID_DONNEES_PERSO_PAS_VALIDEES,
                'contents' => [
                    Message::ROLE_ID_DEFAULT => "Les données personnelles de {intervenant} n'ont pas encore été validées.",
                    IntervenantRole::ROLE_ID => "Vos données personnelles n'ont pas encore été validées.",
                ],
            ],
            [
                'id' => MsgConst::ID_DONNEES_PERSO_IMPORTANTES,
                'contents' => [
                    ComposanteRole::ROLE_ID => "Ces données sont indispensables pour éditer le contrat et mettre en paiement les heures d'enseignement.",
                    IntervenantRole::ROLE_ID => "Ces données sont indispensables pour éditer votre contrat et mettre en paiement vos heures d'enseignement.",
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            'MessageService' => Service\Message\MessageServiceFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'message' => Service\Message\View\Helper\MessageHelperFactory::class,
        ],
    ],
];
