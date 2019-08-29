<?php

return [
    'fi'          => [
        'libelle-court'            => 'Fi',
        'libelle-long'             => 'Formation initiale',
        'type-heures-element'      => 'fi',
        'eligible-centre-cout-ep'  => true,
        'eligible-extraction-paie' => true,
        'enseignement'             => true,
    ],
    'fa'          => [
        'libelle-court'            => 'Fa',
        'libelle-long'             => 'Formation en apprentissage',
        'type-heures-element'      => 'fa',
        'eligible-centre-cout-ep'  => true,
        'eligible-extraction-paie' => true,
        'enseignement'             => true,
    ],
    'fc'          => [
        'libelle-court'            => 'Fc',
        'libelle-long'             => 'Formation continue',
        'type-heures-element'      => 'fc',
        'eligible-centre-cout-ep'  => true,
        'eligible-extraction-paie' => true,
        'enseignement'             => true,
    ],
    'fc_majorees' => [
        'libelle-court'            => 'Rém. FC D714-60',
        'libelle-long'             => 'Rémunération de la formation continue au titre de l\'article D714-60',
        'type-heures-element'      => 'fc',
        'eligible-centre-cout-ep'  => false,
        'eligible-extraction-paie' => false,
        'enseignement'             => false,
    ],
    'referentiel' => [
        'libelle-court'            => 'Référentiel',
        'libelle-long'             => 'Référentiel',
        'type-heures-element'      => 'referentiel',
        'eligible-centre-cout-ep'  => false,
        'eligible-extraction-paie' => true,
        'enseignement'             => false,
    ],
];