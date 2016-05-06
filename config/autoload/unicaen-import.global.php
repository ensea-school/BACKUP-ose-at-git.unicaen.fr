<?php

namespace Application;

return [
    'unicaen-import' => [
        'differentiel_view_helpers' => [
            'CHEMIN_PEDAGOGIQUE'  => View\Helper\Import\CheminPedagogiqueViewHelper::class,
            'ELEMENT_PEDAGOGIQUE' => View\Helper\Import\ElementPedagogiqueViewHelper::class,
            'INTERVENANT'         => View\Helper\Import\IntervenantViewHelper::class,
            'PERSONNEL'           => View\Helper\Import\PersonnelViewHelper::class,
            'ETAPE'               => View\Helper\Import\EtapeViewHelper::class,
        ],
    ],
];