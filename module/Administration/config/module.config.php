<?php

namespace Administration;

return [
    'unicaen-bddadmin' => [

        'data' => [
            'sources' => [
                10 => DataSource\DataSource::class,
            ],
        ],

        'migration' => [
            Migration\v23minimum::class,
            Migration\v24Formules::class,
            Migration\v24MisesEnPaiement::class,
            Migration\v24Primes::class,
        ],
    ],

    'services' => [
        DataSource\DataSource::class               => DataSource\DataSourceFactory::class,
        Command\InstallCommand::class              => Command\InstallCommandFactory::class,
        Command\InstallBddCommand::class           => Command\InstallBddCommandFactory::class,
        Command\ChangementMotDePasseCommand::class => Command\ChangementMotDePasseCommandFactory::class,
    ],

    'laminas-cli' => [
        'commands' => [
            'install'                 => Command\InstallCommand::class,
            'install-bdd'             => Command\InstallBddCommand::class,
            'changement-mot-de-passe' => Command\ChangementMotDePasseCommand::class,
            'update-ddl'              => \Unicaen\BddAdmin\Command\UpdateDdlCommand::class,
        ],
    ],
];