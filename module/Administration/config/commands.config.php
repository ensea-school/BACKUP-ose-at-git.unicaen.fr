<?php

namespace Administration;

return [
    'services' => [
        Command\InstallCommand::class                => Command\InstallCommandFactory::class,
        Command\InstallBddCommand::class             => Command\InstallBddCommandFactory::class,
        Command\ChangementMotDePasseCommand::class   => Command\ChangementMotDePasseCommandFactory::class,
        Command\UpdateBddCommand::class              => Command\UpdateBddCommandFactory::class,
        Command\UpdateBddDataCommand::class          => Command\UpdateBddDataCommandFactory::class,
        Command\UpdateBddPrivilegesCommand::class    => Command\UpdateBddPrivilegesCommandFactory::class,
        Command\ClearCacheCommand::class             => Command\ClearCacheCommandFactory::class,
        Command\CalculTableauxBordCommand::class     => Command\CalculTableauxBordCommandFactory::class,
        Command\UpdateCommand::class                 => Command\UpdateCommandFactory::class,
        Command\UpdateCodeCommand::class             => Command\UpdateCodeCommandFactory::class,
        Command\UpdateEmployeur::class               => Command\UpdateEmployeurFactory::class,
        Command\SynchronisationCommand::class        => Command\SynchronisationCommandFactory::class,
        Command\MajExportsCommand::class             => Command\MajExportsCommandFactory::class,
        Command\CreerUtilisateurCommand::class       => Command\CreerUtilisateurCommandFactory::class,
        Command\FichiersVersFilesystemCommand::class => Command\FichiersVersFilesystemCommandFactory::class,
    ],

    'laminas-cli' => [
        'commands' => [
            'install'                  => Command\InstallCommand::class,
            'install-bdd'              => Command\InstallBddCommand::class,
            'changement-mot-de-passe'  => Command\ChangementMotDePasseCommand::class,
            'update'                   => Command\UpdateCommand::class,
            'update-bdd'               => Command\UpdateBddCommand::class,
            'update-bdd-data'          => Command\UpdateBddDataCommand::class,
            'update-bdd-privileges'    => Command\UpdateBddPrivilegesCommand::class,
            'update-ddl'               => \Unicaen\BddAdmin\Command\UpdateDdlCommand::class,
            'build-synchronisation'    => \UnicaenImport\Command\MajVuesFonctionsCommand::class,
            'clear-cache'              => Command\ClearCacheCommand::class,
            'calcul-tableaux-bord'     => Command\CalculTableauxBordCommand::class,
            'update-code'              => Command\UpdateCodeCommand::class,
            'update-employeur'         => Command\UpdateEmployeur::class,
            'synchronisation'          => Command\SynchronisationCommand::class,
            'maj-exports'              => Command\MajExportsCommand::class,
            'creer-utilisateur'        => Command\CreerUtilisateurCommand::class,
            'fichiers-vers-filesystem' => Command\FichiersVersFilesystemCommand::class,
        ],
    ],
];