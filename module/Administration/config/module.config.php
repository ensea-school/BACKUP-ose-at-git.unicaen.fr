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
        Service\AdministrationService::class       => Service\AdministrationServiceFactory::class,
        Service\GitRepoService::class              => Service\GitRepoServiceFactory::class,
        DataSource\DataSource::class               => DataSource\DataSourceFactory::class,
        Command\InstallCommand::class              => Command\InstallCommandFactory::class,
        Command\InstallBddCommand::class           => Command\InstallBddCommandFactory::class,
        Command\ChangementMotDePasseCommand::class => Command\ChangementMotDePasseCommandFactory::class,
        Command\UpdateBddCommand::class            => Command\UpdateBddCommandFactory::class,
        Command\UpdateBddDataCommand::class        => Command\UpdateBddDataCommandFactory::class,
        Command\UpdateBddPrivilegesCommand::class  => Command\UpdateBddPrivilegesCommandFactory::class,
        Command\ClearCacheCommand::class           => Command\ClearCacheCommandFactory::class,
        Command\CalculTableauxBordCommand::class   => Command\CalculTableauxBordCommandFactory::class,
        Command\UpdateCommand::class               => Command\UpdateCommandFactory::class,
        Command\UpdateCodeCommand::class           => Command\UpdateCodeCommandFactory::class,
        Command\UpdateEmployeur::class             => Command\UpdateEmployeurFactory::class,
    ],

    'laminas-cli' => [
        'commands' => [
            'install'                 => Command\InstallCommand::class,
            'install-bdd'             => Command\InstallBddCommand::class,
            'changement-mot-de-passe' => Command\ChangementMotDePasseCommand::class,
            'update'                  => Command\UpdateCommand::class,
            'update-ddl'              => \Unicaen\BddAdmin\Command\UpdateDdlCommand::class,
            'update-bdd'              => Command\UpdateBddCommand::class,
            'update-bdd-data'         => Command\UpdateBddDataCommand::class,
            'update-bdd-privileges'   => Command\UpdateBddPrivilegesCommand::class,
            'clear-cache'             => Command\ClearCacheCommand::class,
            'calcul-tableaux-bord'    => Command\CalculTableauxBordCommand::class,
            'update-code'             => Command\UpdateCodeCommand::class,
            'update-employeur'        => Command\UpdateEmployeur::class,
        ],
    ],
];