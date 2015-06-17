<?php
return array (
  'doctrine' => 
  array (
    'cache' => 
    array (
      'apc' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\ApcCache',
        'namespace' => 'OSE__Application',
      ),
      'array' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\ArrayCache',
        'namespace' => 'DoctrineModule',
      ),
      'filesystem' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\FilesystemCache',
        'directory' => 'data/DoctrineModule/cache',
        'namespace' => 'DoctrineModule',
      ),
      'memcache' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\MemcacheCache',
        'instance' => 'my_memcache_alias',
        'namespace' => 'DoctrineModule',
      ),
      'memcached' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\MemcachedCache',
        'instance' => 'my_memcached_alias',
        'namespace' => 'DoctrineModule',
      ),
      'redis' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\RedisCache',
        'instance' => 'my_redis_alias',
        'namespace' => 'DoctrineModule',
      ),
      'wincache' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\WinCacheCache',
        'namespace' => 'DoctrineModule',
      ),
      'xcache' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\XcacheCache',
        'namespace' => 'DoctrineModule',
      ),
      'zenddata' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\ZendDataCache',
        'namespace' => 'DoctrineModule',
      ),
    ),
    'authentication' => 
    array (
      'odm_default' => 
      array (
      ),
      'orm_default' => 
      array (
        'objectManager' => 'doctrine.entitymanager.orm_default',
      ),
    ),
    'authenticationadapter' => 
    array (
      'odm_default' => true,
      'orm_default' => true,
    ),
    'authenticationstorage' => 
    array (
      'odm_default' => true,
      'orm_default' => true,
    ),
    'authenticationservice' => 
    array (
      'odm_default' => true,
      'orm_default' => true,
    ),
    'connection' => 
    array (
      'orm_default' => 
      array (
        'configuration' => 'orm_default',
        'eventmanager' => 'orm_default',
        'params' => 
        array (
          'host' => 'osedb.unicaen.fr',
          'port' => '1523',
          'user' => 'ose',
          'password' => 'oustBN4',
          'dbname' => 'OSEPROD',
          'charset' => 'AL32UTF8',
        ),
        'driverClass' => 'Doctrine\\DBAL\\Driver\\OCI8\\Driver',
      ),
    ),
    'configuration' => 
    array (
      'orm_default' => 
      array (
        'metadata_cache' => 'array',
        'query_cache' => 'array',
        'result_cache' => 'array',
        'hydration_cache' => 'array',
        'driver' => 'orm_default',
        'generate_proxies' => true,
        'proxy_dir' => 'data/DoctrineORMModule/Proxy',
        'proxy_namespace' => 'DoctrineORMModule\\Proxy',
        'filters' => 
        array (
          'historique' => 'Common\\ORM\\Filter\\HistoriqueFilter',
          'etape' => 'Common\\ORM\\Filter\\EtapeFilter',
          'annee' => 'Common\\ORM\\Filter\\AnneeFilter',
        ),
        'datetime_functions' => 
        array (
        ),
        'string_functions' => 
        array (
          'CONVERT' => 'Common\\ORM\\Query\\Functions\\Convert',
          'CONTAINS' => 'Common\\ORM\\Query\\Functions\\Contains',
          'REPLACE' => 'Common\\ORM\\Query\\Functions\\Replace',
          'OSE_DIVERS_STRUCTURE_DANS_STRUCTURE' => 'Common\\ORM\\Query\\Functions\\OseDivers\\StructureDansStructure',
          'compriseEntre' => 'Common\\ORM\\Query\\Functions\\OseDivers\\CompriseEntre',
        ),
        'numeric_functions' => 
        array (
        ),
        'second_level_cache' => 
        array (
        ),
      ),
    ),
    'driver' => 
    array (
      'orm_default' => 
      array (
        'class' => 'Doctrine\\ORM\\Mapping\\Driver\\DriverChain',
        'drivers' => 
        array (
          'ZfcUser\\Entity' => 'zfcuser_entity',
          'UnicaenAuth\\Entity\\Db' => 'orm_auth_driver',
          'Application\\Entity\\Db' => 'orm_default_driver',
        ),
      ),
      'zfcuser_entity' => 
      array (
        'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
        'paths' => 
        array (
          0 => '/var/www/OSE/vendor/unicaen/unicaen-auth/config/../src/UnicaenAuth/Entity/Db',
        ),
      ),
      'orm_auth_driver' => 
      array (
        'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
        'cache' => 'array',
        'paths' => 
        array (
          0 => '/var/www/OSE/vendor/unicaen/unicaen-auth/config/../src/UnicaenAuth/Entity/Db',
        ),
      ),
      'orm_default_driver' => 
      array (
        'class' => 'Doctrine\\ORM\\Mapping\\Driver\\XmlDriver',
        'paths' => 
        array (
          0 => '/var/www/OSE/module/Application/config/../src/Application/Entity/Db/Mapping',
        ),
      ),
    ),
    'entitymanager' => 
    array (
      'orm_default' => 
      array (
        'connection' => 'orm_default',
        'configuration' => 'orm_default',
      ),
    ),
    'eventmanager' => 
    array (
      'orm_default' => 
      array (
        'subscribers' => 
        array (
          0 => 'Doctrine\\DBAL\\Event\\Listeners\\OracleSessionInit',
          1 => 'Common\\ORM\\Event\\Listeners\\HistoriqueListener',
        ),
      ),
    ),
    'sql_logger_collector' => 
    array (
      'orm_default' => 
      array (
      ),
    ),
    'mapping_collector' => 
    array (
      'orm_default' => 
      array (
      ),
    ),
    'formannotationbuilder' => 
    array (
      'orm_default' => 
      array (
      ),
    ),
    'entity_resolver' => 
    array (
      'orm_default' => 
      array (
      ),
    ),
    'migrations_configuration' => 
    array (
      'orm_default' => 
      array (
        'directory' => 'data/DoctrineORMModule/Migrations',
        'name' => 'Doctrine Database Migrations',
        'namespace' => 'DoctrineORMModule\\Migrations',
        'table' => 'migrations',
      ),
    ),
    'migrations_cmd' => 
    array (
      'generate' => 
      array (
      ),
      'execute' => 
      array (
      ),
      'migrate' => 
      array (
      ),
      'status' => 
      array (
      ),
      'version' => 
      array (
      ),
      'diff' => 
      array (
      ),
      'latest' => 
      array (
      ),
    ),
  ),
  'doctrine_factories' => 
  array (
    'cache' => 'DoctrineModule\\Service\\CacheFactory',
    'eventmanager' => 'DoctrineModule\\Service\\EventManagerFactory',
    'driver' => 'DoctrineModule\\Service\\DriverFactory',
    'authenticationadapter' => 'DoctrineModule\\Service\\Authentication\\AdapterFactory',
    'authenticationstorage' => 'DoctrineModule\\Service\\Authentication\\StorageFactory',
    'authenticationservice' => 'DoctrineModule\\Service\\Authentication\\AuthenticationServiceFactory',
    'connection' => 'DoctrineORMModule\\Service\\DBALConnectionFactory',
    'configuration' => 'DoctrineORMModule\\Service\\ConfigurationFactory',
    'entitymanager' => 'DoctrineORMModule\\Service\\EntityManagerFactory',
    'entity_resolver' => 'DoctrineORMModule\\Service\\EntityResolverFactory',
    'sql_logger_collector' => 'DoctrineORMModule\\Service\\SQLLoggerCollectorFactory',
    'mapping_collector' => 'DoctrineORMModule\\Service\\MappingCollectorFactory',
    'formannotationbuilder' => 'DoctrineORMModule\\Service\\FormAnnotationBuilderFactory',
    'migrations_configuration' => 'DoctrineORMModule\\Service\\MigrationsConfigurationFactory',
    'migrations_cmd' => 'DoctrineORMModule\\Service\\MigrationsCommandFactory',
  ),
  'service_manager' => 
  array (
    'invokables' => 
    array (
      'DoctrineModule\\Authentication\\Storage\\Session' => 'Zend\\Authentication\\Storage\\Session',
      'doctrine.dbal_cmd.runsql' => '\\Doctrine\\DBAL\\Tools\\Console\\Command\\RunSqlCommand',
      'doctrine.dbal_cmd.import' => '\\Doctrine\\DBAL\\Tools\\Console\\Command\\ImportCommand',
      'doctrine.orm_cmd.clear_cache_metadata' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\ClearCache\\MetadataCommand',
      'doctrine.orm_cmd.clear_cache_result' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\ClearCache\\ResultCommand',
      'doctrine.orm_cmd.clear_cache_query' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\ClearCache\\QueryCommand',
      'doctrine.orm_cmd.schema_tool_create' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\SchemaTool\\CreateCommand',
      'doctrine.orm_cmd.schema_tool_update' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\SchemaTool\\UpdateCommand',
      'doctrine.orm_cmd.schema_tool_drop' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\SchemaTool\\DropCommand',
      'doctrine.orm_cmd.convert_d1_schema' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\ConvertDoctrine1SchemaCommand',
      'doctrine.orm_cmd.generate_entities' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\GenerateEntitiesCommand',
      'doctrine.orm_cmd.generate_proxies' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\GenerateProxiesCommand',
      'doctrine.orm_cmd.convert_mapping' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\ConvertMappingCommand',
      'doctrine.orm_cmd.run_dql' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\RunDqlCommand',
      'doctrine.orm_cmd.validate_schema' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\ValidateSchemaCommand',
      'doctrine.orm_cmd.info' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\InfoCommand',
      'doctrine.orm_cmd.ensure_production_settings' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\EnsureProductionSettingsCommand',
      'doctrine.orm_cmd.generate_repositories' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\GenerateRepositoriesCommand',
      'BjyAuthorize\\View\\RedirectionStrategy' => 'BjyAuthorize\\View\\RedirectionStrategy',
      'unicaen-auth_user_service' => 'UnicaenAuth\\Service\\User',
      'UnicaenAuth\\Authentication\\Storage\\Db' => 'UnicaenAuth\\Authentication\\Storage\\Db',
      'UnicaenAuth\\Authentication\\Storage\\Ldap' => 'UnicaenAuth\\Authentication\\Storage\\Ldap',
      'UnicaenAuth\\View\\RedirectionStrategy' => 'UnicaenAuth\\View\\RedirectionStrategy',
      'authUserContext' => 'UnicaenAuth\\Service\\UserContext',
      'AuthenticatedUserSavedListener' => 'Application\\AuthenticatedUserSavedListener',
      'Common\\ORM\\Event\\Listeners\\HistoriqueListener' => 'Common\\ORM\\Event\\Listeners\\HistoriqueListener',
      'ApplicationAnnee' => 'Application\\Service\\Annee',
      'ApplicationContext' => 'Application\\Service\\Context',
      'ApplicationLocalContext' => 'Application\\Service\\LocalContext',
      'ApplicationParametres' => 'Application\\Service\\Parametres',
      'ApplicationUtilisateur' => 'Application\\Service\\Utilisateur',
      'ApplicationTypeIntervention' => 'Application\\Service\\TypeIntervention',
      'ApplicationSource' => 'Application\\Service\\Source',
      'ApplicationAffectation' => 'Application\\Service\\Affectation',
      'ApplicationRole' => 'Application\\Service\\Role',
      'ApplicationPrivilege' => 'Application\\Service\\Privilege',
      'ApplicationPays' => 'Application\\Service\\Pays',
      'ApplicationDepartement' => 'Application\\Service\\Departement',
      'IntervenantNavigationPageVisibility' => 'Application\\Service\\IntervenantNavigationPageVisibility',
      'TestAssertion' => 'Application\\Assertion\\TestAssertion',
      'ApplicationIntervenant' => 'Application\\Service\\Intervenant',
      'ApplicationMotifModificationServiceDu' => 'Application\\Service\\MotifModificationServiceDu',
      'ApplicationCivilite' => 'Application\\Service\\Civilite',
      'ApplicationStatutIntervenant' => 'Application\\Service\\StatutIntervenant',
      'ApplicationTypeIntervenant' => 'Application\\Service\\TypeIntervenant',
      'ApplicationDossier' => 'Application\\Service\\Dossier',
      'IntervenantAssertion' => 'Application\\Assertion\\IntervenantAssertion',
      'ModificationServiceDuAssertion' => 'Application\\Assertion\\ModificationServiceDuAssertion',
      'PeutSaisirDossierRule' => 'Application\\Rule\\Intervenant\\PeutSaisirDossierRule',
      'PeutSaisirServiceRule' => 'Application\\Rule\\Intervenant\\PeutSaisirServiceRule',
      'PeutSaisirReferentielRule' => 'Application\\Rule\\Intervenant\\PeutSaisirReferentielRule',
      'PossedeDossierRule' => 'Application\\Rule\\Intervenant\\PossedeDossierRule',
      'ServiceValideRule' => 'Application\\Rule\\Intervenant\\ServiceValideRule',
      'PeutValiderServiceRule' => 'Application\\Rule\\Intervenant\\PeutValiderServiceRule',
      'ReferentielValideRule' => 'Application\\Rule\\Intervenant\\ReferentielValideRule',
      'NecessiteAgrementRule' => 'Application\\Rule\\Intervenant\\NecessiteAgrementRule',
      'AgrementFourniRule' => 'Application\\Rule\\Intervenant\\AgrementFourniRule',
      'EstAffecteRule' => 'Application\\Rule\\Intervenant\\EstAffecteRule',
      'ApplicationPieceJointe' => 'Application\\Service\\PieceJointe',
      'ApplicationPieceJointeProcess' => 'Application\\Service\\Process\\PieceJointeProcess',
      'ApplicationTypePieceJointe' => 'Application\\Service\\TypePieceJointe',
      'ApplicationTypePieceJointeStatut' => 'Application\\Service\\TypePieceJointeStatut',
      'PeutSaisirPieceJointeRule' => 'Application\\Rule\\Intervenant\\PeutSaisirPieceJointeRule',
      'PieceJointeAssertion' => 'Application\\Assertion\\PieceJointeAssertion',
      'FichierAssertion' => 'Application\\Assertion\\FichierAssertion',
      'ApplicationPersonnel' => 'Application\\Service\\Personnel',
      'ApplicationStructure' => 'Application\\Service\\Structure',
      'ApplicationTypeStructure' => 'Application\\Service\\TypeStructure',
      'ApplicationEtablissement' => 'Application\\Service\\Etablissement',
      'ApplicationService' => 'Application\\Service\\Service',
      'ApplicationServiceReferentiel' => 'Application\\Service\\ServiceReferentiel',
      'ApplicationFonctionReferentiel' => 'Application\\Service\\FonctionReferentiel',
      'ApplicationPeriode' => 'Application\\Service\\Periode',
      'ApplicationMotifNonPaiement' => 'Application\\Service\\MotifNonPaiement',
      'ApplicationModificationServiceDu' => 'Application\\Service\\ModificationServiceDu',
      'ServiceRechercheHydrator' => 'Application\\Entity\\Service\\RechercheHydrator',
      'ServiceRechercheFormHydrator' => 'Application\\Form\\Service\\RechercheFormHydrator',
      'FormServiceSaisieFieldsetHydrator' => 'Application\\Form\\Service\\SaisieFieldsetHydrator',
      'FormServiceSaisieHydrator' => 'Application\\Form\\Service\\SaisieHydrator',
      'FormServiceReferentielSaisieFieldsetHydrator' => 'Application\\Form\\ServiceReferentiel\\SaisieFieldsetHydrator',
      'FormServiceReferentielSaisieHydrator' => 'Application\\Form\\ServiceReferentiel\\SaisieHydrator',
      'ServiceAssertion' => 'Application\\Assertion\\ServiceAssertion',
      'ServiceReferentielAssertion' => 'Application\\Assertion\\ServiceReferentielAssertion',
      'ApplicationVolumeHoraire' => 'Application\\Service\\VolumeHoraire',
      'ApplicationVolumeHoraireReferentiel' => 'Application\\Service\\VolumeHoraireReferentiel',
      'ApplicationTypeVolumeHoraire' => 'Application\\Service\\TypeVolumeHoraire',
      'ApplicationEtatVolumeHoraire' => 'Application\\Service\\EtatVolumeHoraire',
      'FormVolumeHoraireSaisieMultipleHydrator' => 'Application\\Form\\VolumeHoraire\\SaisieMultipleHydrator',
      'ApplicationElementPedagogique' => 'Application\\Service\\ElementPedagogique',
      'ApplicationCheminPedagogique' => 'Application\\Service\\CheminPedagogique',
      'ApplicationEtape' => 'Application\\Service\\Etape',
      'ApplicationTypeFormation' => 'Application\\Service\\TypeFormation',
      'ApplicationGroupeTypeFormation' => 'Application\\Service\\GroupeTypeFormation',
      'ApplicationNiveauEtape' => 'Application\\Service\\NiveauEtape',
      'ApplicationNiveauFormation' => 'Application\\Service\\NiveauFormation',
      'ApplicationModulateur' => 'Application\\Service\\Modulateur',
      'ApplicationElementModulateur' => 'Application\\Service\\ElementModulateur',
      'ApplicationTypeModulateur' => 'Application\\Service\\TypeModulateur',
      'ApplicationDomaineFonctionnel' => 'Application\\Service\\DomaineFonctionnel',
      'FormElementPedagogiqueRechercheHydrator' => 'Application\\Form\\OffreFormation\\ElementPedagogiqueRechercheHydrator',
      'ElementModulateursFormHydrator' => 'Application\\Form\\OffreFormation\\ElementModulateursHydrator',
      'EtapeModulateursFormHydrator' => 'Application\\Form\\OffreFormation\\EtapeModulateursHydrator',
      'EtapeCentreCoutFormHydrator' => 'Application\\Form\\OffreFormation\\EtapeCentreCout\\EtapeCentreCoutFormHydrator',
      'ElementCentreCoutFieldsetHydrator' => 'Application\\Form\\OffreFormation\\EtapeCentreCout\\ElementCentreCoutFieldsetHydrator',
      'ApplicationContrat' => 'Application\\Service\\Contrat',
      'ApplicationTypeContrat' => 'Application\\Service\\TypeContrat',
      'ApplicationContratProcess' => 'Application\\Service\\Process\\ContratProcess',
      'NecessiteContratRule' => 'Application\\Rule\\Intervenant\\NecessiteContratRule',
      'PossedeContratRule' => 'Application\\Rule\\Intervenant\\PossedeContratRule',
      'PeutCreerContratInitialRule' => 'Application\\Rule\\Intervenant\\PeutCreerContratInitialRule',
      'PeutCreerAvenantRule' => 'Application\\Rule\\Intervenant\\PeutCreerAvenantRule',
      'ContratAssertion' => 'Application\\Assertion\\ContratAssertion',
      'ApplicationTypeValidation' => 'Application\\Service\\TypeValidation',
      'ApplicationValidation' => 'Application\\Service\\Validation',
      'ValidationEnseignementRule' => 'Application\\Rule\\Validation\\ValidationEnseignementRule',
      'ValidationReferentielRule' => 'Application\\Rule\\Validation\\ValidationReferentielRule',
      'ClotureRealiseRule' => 'Application\\Rule\\Validation\\ClotureRealiseRule',
      'ValidationAssertion' => 'Application\\Assertion\\ValidationAssertionProxy',
      'ValidationServiceAssertion' => 'Application\\Assertion\\ValidationServiceAssertion',
      'ValidationReferentielAssertion' => 'Application\\Assertion\\ValidationReferentielAssertion',
      'ClotureRealiseAssertion' => 'Application\\Assertion\\ClotureRealiseAssertion',
      'ApplicationPerimetre' => 'Application\\Service\\Perimetre',
      'ApplicationAgrement' => 'Application\\Service\\Agrement',
      'ApplicationTypeAgrement' => 'Application\\Service\\TypeAgrement',
      'ApplicationTypeAgrementStatut' => 'Application\\Service\\TypeAgrementStatut',
      'AgrementNavigationPagesProvider' => 'Application\\Service\\AgrementNavigationPagesProvider',
      'AgrementIntervenantNavigationPagesProvider' => 'Application\\Service\\AgrementIntervenantNavigationPagesProvider',
      'AgrementAssertion' => 'Application\\Assertion\\AgrementAssertion',
      'ApplicationFormuleIntervenant' => 'Application\\Service\\FormuleIntervenant',
      'ApplicationFormuleServiceModifie' => 'Application\\Service\\FormuleServiceModifie',
      'ApplicationFormuleService' => 'Application\\Service\\FormuleService',
      'ApplicationFormuleServiceReferentiel' => 'Application\\Service\\FormuleServiceReferentiel',
      'ApplicationFormuleVolumeHoraire' => 'Application\\Service\\FormuleVolumeHoraire',
      'ApplicationFormuleVolumeHoraireReferentiel' => 'Application\\Service\\FormuleVolumeHoraireReferentiel',
      'ApplicationFormuleResultat' => 'Application\\Service\\FormuleResultat',
      'ApplicationFormuleResultatService' => 'Application\\Service\\FormuleResultatService',
      'ApplicationFormuleResultatServiceReferentiel' => 'Application\\Service\\FormuleResultatServiceReferentiel',
      'ApplicationFormuleResultatVolumeHoraire' => 'Application\\Service\\FormuleResultatVolumeHoraire',
      'ApplicationFormuleResultatVolumeHoraireReferentiel' => 'Application\\Service\\FormuleResultatVolumeHoraireReferentiel',
      'WfEtapeService' => 'Application\\Service\\WfEtape',
      'WfIntervenantEtapeService' => 'Application\\Service\\WfIntervenantEtape',
      'WorkflowIntervenant' => 'Application\\Service\\Workflow\\WorkflowIntervenant',
      'DbFunctionRule' => 'Application\\Rule\\Intervenant\\DbFunctionRule',
      'applicationIndicateur' => 'Application\\Service\\Indicateur',
      'IndicateurService' => 'Application\\Service\\Indicateur',
      'NotificationIndicateurService' => 'Application\\Service\\NotificationIndicateur',
      'AttenteAgrementCR' => 'Application\\Service\\Indicateur\\Agrement\\AttenteAgrementCRIndicateurImpl',
      'AttenteAgrementCA' => 'Application\\Service\\Indicateur\\Agrement\\AttenteAgrementCAIndicateurImpl',
      'AgrementCAMaisPasContrat' => 'Application\\Service\\Indicateur\\Contrat\\AgrementCAMaisPasContratIndicateurImpl',
      'AttenteContrat' => 'Application\\Service\\Indicateur\\Contrat\\AttenteContratIndicateurImpl',
      'AttenteAvenant' => 'Application\\Service\\Indicateur\\Contrat\\AttenteAvenantIndicateurImpl',
      'AttenteRetourContrat' => 'Application\\Service\\Indicateur\\Contrat\\AttenteRetourContratIndicateurImpl',
      'ContratAvenantDeposes' => 'Application\\Service\\Indicateur\\Contrat\\ContratAvenantDeposesIndicateurImpl',
      'SaisieServiceApresContratAvenant' => 'Application\\Service\\Indicateur\\Contrat\\SaisieServiceApresContratAvenantIndicateurImpl',
      'AttenteValidationDonneesPerso' => 'Application\\Service\\Indicateur\\Dossier\\AttenteValidationDonneesPersoIndicateurImpl',
      'DonneesPersoDiffImport' => 'Application\\Service\\Indicateur\\Dossier\\DonneesPersoDiffImportIndicateurImpl',
      'AttenteDemandeMepVac' => 'Application\\Service\\Indicateur\\Paiement\\AttenteDemandeMepVacIndicateurImpl',
      'AttenteDemandeMepPerm' => 'Application\\Service\\Indicateur\\Paiement\\AttenteDemandeMepPermIndicateurImpl',
      'AttenteMepVac' => 'Application\\Service\\Indicateur\\Paiement\\AttenteMepVacIndicateurImpl',
      'AttenteMepPerm' => 'Application\\Service\\Indicateur\\Paiement\\AttenteMepPermIndicateurImpl',
      'AttentePieceJustif' => 'Application\\Service\\Indicateur\\PieceJointe\\AttentePieceJustifIndicateurImpl',
      'AttenteValidationPieceJustif' => 'Application\\Service\\Indicateur\\PieceJointe\\AttenteValidationPieceJustifIndicateurImpl',
      'PermAffectAutreIntervMeme' => 'Application\\Service\\Indicateur\\Service\\Affectation\\PermAffectAutreIntervMemeIndicateurImpl',
      'PermAffectMemeIntervAutre' => 'Application\\Service\\Indicateur\\Service\\Affectation\\PermAffectMemeIntervAutreIndicateurImpl',
      'BiatssAffectMemeIntervAutre' => 'Application\\Service\\Indicateur\\Service\\Affectation\\BiatssAffectMemeIntervAutreIndicateurImpl',
      'PlafondHcPrevuHorsRemuFcDepasse' => 'Application\\Service\\Indicateur\\Service\\Plafond\\PlafondHcPrevuHorsRemuFcDepasseIndicateurImpl',
      'PlafondHcRealiseHorsRemuFcDepasse' => 'Application\\Service\\Indicateur\\Service\\Plafond\\PlafondHcRealiseHorsRemuFcDepasseIndicateurImpl',
      'PlafondRefPrevuDepasse' => 'Application\\Service\\Indicateur\\Service\\Plafond\\PlafondRefPrevuDepasseIndicateurImpl',
      'PlafondRefRealiseDepasse' => 'Application\\Service\\Indicateur\\Service\\Plafond\\PlafondRefRealiseDepasseIndicateurImpl',
      'AttenteValidationEnsPrevuVac' => 'Application\\Service\\Indicateur\\Service\\Validation\\AttenteValidationEnsPrevuVacIndicateurImpl',
      'AttenteValidationEnsPrevuPerm' => 'Application\\Service\\Indicateur\\Service\\Validation\\AttenteValidationEnsPrevuPermIndicateurImpl',
      'AttenteValidationEnsRealiseVac' => 'Application\\Service\\Indicateur\\Service\\Validation\\AttenteValidationEnsRealiseVacIndicateurImpl',
      'AttenteValidationEnsRealisePerm' => 'Application\\Service\\Indicateur\\Service\\Validation\\AttenteValidationEnsRealisePermIndicateurImpl',
      'AttenteValidationRefPrevuPerm' => 'Application\\Service\\Indicateur\\Service\\Validation\\AttenteValidationRefPrevuPermIndicateurImpl',
      'AttenteValidationRefRealisePerm' => 'Application\\Service\\Indicateur\\Service\\Validation\\AttenteValidationRefRealisePermIndicateurImpl',
      'EnsHisto' => 'Application\\Service\\Indicateur\\Service\\EnsHistoIndicateurImpl',
      'EnsRealisePermSaisieNonCloturee' => 'Application\\Service\\Indicateur\\Service\\EnsRealisePermSaisieNonClotureeIndicateurImpl',
      'ApplicationServiceAPayer' => 'Application\\Service\\ServiceAPayer',
      'ApplicationMiseEnPaiement' => 'Application\\Service\\MiseEnPaiement',
      'ApplicationMiseEnPaiementIntervenantStructure' => 'Application\\Service\\MiseEnPaiementIntervenantStructure',
      'ApplicationTypeHeures' => 'Application\\Service\\TypeHeures',
      'ApplicationCentreCout' => 'Application\\Service\\CentreCout',
      'MiseEnPaiementAssertion' => 'Application\\Assertion\\MiseEnPaiementAssertion',
      'MiseEnPaiementExisteRule' => 'Application\\Rule\\Paiement\\MiseEnPaiementExisteRule',
    ),
    'factories' => 
    array (
      'doctrine.cli' => 'DoctrineModule\\Service\\CliFactory',
      'Doctrine\\ORM\\EntityManager' => 'DoctrineORMModule\\Service\\EntityManagerAliasCompatFactory',
      'BjyAuthorize\\Cache' => 'BjyAuthorize\\Service\\CacheFactory',
      'BjyAuthorize\\CacheKeyGenerator' => 'BjyAuthorize\\Service\\CacheKeyGeneratorFactory',
      'BjyAuthorize\\Config' => 'BjyAuthorize\\Service\\ConfigServiceFactory',
      'BjyAuthorize\\Guards' => 'BjyAuthorize\\Service\\GuardsServiceFactory',
      'BjyAuthorize\\RoleProviders' => 'BjyAuthorize\\Service\\RoleProvidersServiceFactory',
      'BjyAuthorize\\ResourceProviders' => 'BjyAuthorize\\Service\\ResourceProvidersServiceFactory',
      'BjyAuthorize\\RuleProviders' => 'BjyAuthorize\\Service\\RuleProvidersServiceFactory',
      'BjyAuthorize\\Guard\\Controller' => 'BjyAuthorize\\Service\\ControllerGuardServiceFactory',
      'BjyAuthorize\\Guard\\Route' => 'BjyAuthorize\\Service\\RouteGuardServiceFactory',
      'BjyAuthorize\\Provider\\Role\\Config' => 'BjyAuthorize\\Service\\ConfigRoleProviderServiceFactory',
      'BjyAuthorize\\Provider\\Role\\ZendDb' => 'BjyAuthorize\\Service\\ZendDbRoleProviderServiceFactory',
      'BjyAuthorize\\Provider\\Rule\\Config' => 'BjyAuthorize\\Service\\ConfigRuleProviderServiceFactory',
      'BjyAuthorize\\Provider\\Resource\\Config' => 'BjyAuthorize\\Service\\ConfigResourceProviderServiceFactory',
      'BjyAuthorize\\Service\\Authorize' => 'Application\\Service\\AuthorizeFactory',
      'BjyAuthorize\\Provider\\Identity\\ProviderInterface' => 'BjyAuthorize\\Service\\IdentityProviderServiceFactory',
      'BjyAuthorize\\Provider\\Identity\\AuthenticationIdentityProvider' => 'BjyAuthorize\\Service\\AuthenticationIdentityProviderServiceFactory',
      'BjyAuthorize\\Provider\\Role\\ObjectRepositoryProvider' => 'BjyAuthorize\\Service\\ObjectRepositoryRoleProviderFactory',
      'BjyAuthorize\\Collector\\RoleCollector' => 'BjyAuthorize\\Service\\RoleCollectorServiceFactory',
      'BjyAuthorize\\Provider\\Identity\\ZfcUserZendDb' => 'BjyAuthorize\\Service\\ZfcUserZendDbIdentityProviderServiceFactory',
      'BjyAuthorize\\View\\UnauthorizedStrategy' => 'BjyAuthorize\\Service\\UnauthorizedStrategyServiceFactory',
      'BjyAuthorize\\Service\\RoleDbTableGateway' => 'BjyAuthorize\\Service\\UserRoleServiceFactory',
      'translator' => 'Zend\\I18n\\Translator\\TranslatorServiceFactory',
      'navigation' => 'Application\\Service\\NavigationFactoryFactory',
      'Zend\\Session\\SessionManager' => 'UnicaenApp\\Session\\SessionManagerFactory',
      'unicaen-app_module_options' => 'UnicaenApp\\Options\\ModuleOptionsFactory',
      'ldap_people_mapper' => 'UnicaenApp\\Mapper\\Ldap\\PeopleFactory',
      'ldap_group_mapper' => 'UnicaenApp\\Mapper\\Ldap\\GroupFactory',
      'ldap_structure_mapper' => 'UnicaenApp\\Mapper\\Ldap\\StructureFactory',
      'ldap_people_service' => 'UnicaenApp\\Service\\Ldap\\PeopleFactory',
      'ldap_group_service' => 'UnicaenApp\\Service\\Ldap\\GroupFactory',
      'ldap_structure_service' => 'UnicaenApp\\Service\\Ldap\\StructureFactory',
      'ViewCsvRenderer' => 'UnicaenApp\\Mvc\\Service\\ViewCsvRendererFactory',
      'ViewCsvStrategy' => 'UnicaenApp\\Mvc\\Service\\ViewCsvStrategyFactory',
      'MessageCollector' => 'UnicaenApp\\Service\\MessageCollectorFactory',
      'unicaen-auth_module_options' => 'UnicaenAuth\\Options\\ModuleOptionsFactory',
      'zfcuser_auth_service' => 'UnicaenAuth\\Authentication\\AuthenticationServiceFactory',
      'UnicaenAuth\\Authentication\\Storage\\Chain' => 'UnicaenAuth\\Authentication\\Storage\\ChainServiceFactory',
      'UnicaenAuth\\Provider\\Identity\\Chain' => 'UnicaenAuth\\Provider\\Identity\\ChainServiceFactory',
      'UnicaenAuth\\Provider\\Identity\\Ldap' => 'UnicaenAuth\\Provider\\Identity\\LdapServiceFactory',
      'UnicaenAuth\\Provider\\Identity\\Db' => 'UnicaenAuth\\Provider\\Identity\\DbServiceFactory',
      'UnicaenAuth\\Provider\\Identity\\Basic' => 'UnicaenAuth\\Provider\\Identity\\BasicServiceFactory',
      'UnicaenAuth\\Provider\\Role\\Config' => 'UnicaenAuth\\Provider\\Role\\ConfigServiceFactory',
      'UnicaenAuth\\Provider\\Role\\DbRole' => 'UnicaenAuth\\Provider\\Role\\DbRoleServiceFactory',
      'UnicaenAuth\\Provider\\Role\\Username' => 'UnicaenAuth\\Provider\\Role\\UsernameServiceFactory',
      'ApplicationRoleProvider' => 'Application\\Provider\\Role\\RoleProviderFactory',
      'ApplicationIdentityProvider' => 'Application\\Provider\\Identity\\IdentityProviderFactory',
    ),
    'abstract_factories' => 
    array (
      'DoctrineModule' => 'DoctrineModule\\ServiceFactory\\AbstractDoctrineServiceFactory',
      0 => 'UnicaenApp\\Service\\Doctrine\\MultipleDbAbstractFactory',
      1 => 'UnicaenAuth\\Authentication\\Adapter\\AbstractFactory',
    ),
    'aliases' => 
    array (
      'zfcuser_zend_db_adapter' => 'Zend\\Db\\Adapter\\Adapter',
      'bjyauthorize_zend_db_adapter' => 'Zend\\Db\\Adapter\\Adapter',
      'Zend\\Authentication\\AuthenticationService' => 'zfcuser_auth_service',
      'PrivilegeProvider' => 'ApplicationPrivilege',
    ),
    'initializers' => 
    array (
      'BjyAuthorize\\Service\\AuthorizeAwareServiceInitializer' => 'BjyAuthorize\\Service\\AuthorizeAwareServiceInitializer',
      0 => 'UnicaenApp\\Service\\EntityManagerAwareInitializer',
      1 => 'UnicaenAuth\\Service\\UserAwareInitializer',
      2 => 'Application\\Service\\Initializer\\IntervenantServiceAwareInitializer',
      3 => 'Application\\Service\\Initializer\\ServiceServiceAwareInitializer',
      4 => 'Application\\Service\\Initializer\\AgrementServiceAwareInitializer',
      5 => 'Application\\Service\\Workflow\\WorkflowIntervenantAwareInitializer',
    ),
  ),
  'controllers' => 
  array (
    'factories' => 
    array (
      'DoctrineModule\\Controller\\Cli' => 'DoctrineModule\\Service\\CliControllerFactory',
    ),
    'invokables' => 
    array (
      'zfcuser' => 'ZfcUser\\Controller\\UserController',
      'UnicaenApp\\Controller\\Application' => 'UnicaenApp\\Controller\\ApplicationController',
      'UnicaenAuth\\Controller\\Utilisateur' => 'Application\\Controller\\UtilisateurController',
      'Application\\Controller\\Index' => 'Application\\Controller\\IndexController',
      'Application\\Controller\\Intervenant' => 'Application\\Controller\\IntervenantController',
      'Application\\Controller\\Dossier' => 'Application\\Controller\\DossierController',
      'Application\\Controller\\ModificationServiceDu' => 'Application\\Controller\\ModificationServiceDuController',
      'Application\\Controller\\PieceJointe' => 'Application\\Controller\\PieceJointeController',
      'Application\\Controller\\Structure' => 'Application\\Controller\\StructureController',
      'Application\\Controller\\Etablissement' => 'Application\\Controller\\EtablissementController',
      'Application\\Controller\\Recherche' => 'Application\\Controller\\RechercheController',
      'Application\\Controller\\Service' => 'Application\\Controller\\ServiceController',
      'Application\\Controller\\ServiceReferentiel' => 'Application\\Controller\\ServiceReferentielController',
      'Application\\Controller\\VolumeHoraire' => 'Application\\Controller\\VolumeHoraireController',
      'Application\\Controller\\VolumeHoraireReferentiel' => 'Application\\Controller\\VolumeHoraireReferentielController',
      'Application\\Controller\\OffreFormation' => 'Application\\Controller\\OffreFormationController',
      'Application\\Controller\\OffreFormation\\Etape' => 'Application\\Controller\\OffreFormation\\EtapeController',
      'Application\\Controller\\OffreFormation\\Modulateur' => 'Application\\Controller\\OffreFormation\\ModulateurController',
      'Application\\Controller\\OffreFormation\\ElementPedagogique' => 'Application\\Controller\\OffreFormation\\ElementPedagogiqueController',
      'Application\\Controller\\OffreFormation\\EtapeCentreCout' => 'Application\\Controller\\OffreFormation\\EtapeCentreCoutController',
      'Application\\Controller\\Contrat' => 'Application\\Controller\\ContratController',
      'Application\\Controller\\Validation' => 'Application\\Controller\\ValidationController',
      'Application\\Controller\\Gestion' => 'Application\\Controller\\GestionController',
      'Application\\Controller\\Agrement' => 'Application\\Controller\\AgrementController',
      'Application\\Controller\\Workflow' => 'Application\\Controller\\WorkflowController',
      'Application\\Controller\\Indicateur' => 'Application\\Controller\\IndicateurController',
      'Application\\Controller\\Notification' => 'Application\\Controller\\NotificationController',
      'Application\\Controller\\Paiement' => 'Application\\Controller\\PaiementController',
      'Import\\Controller\\Import' => 'Import\\Controller\\ImportController',
      'Debug\\Controller\\Debug' => 'Debug\\Debug',
    ),
    'aliases' => 
    array (
      'IntervenantController' => 'Application\\Controller\\Intervenant',
    ),
    'initializers' => 
    array (
      0 => 'Application\\Service\\Initializer\\IntervenantServiceAwareInitializer',
      1 => 'Application\\Service\\Initializer\\ServiceServiceAwareInitializer',
      2 => 'Application\\Service\\Initializer\\AgrementServiceAwareInitializer',
      3 => 'Application\\Service\\Workflow\\WorkflowIntervenantAwareInitializer',
    ),
  ),
  'route_manager' => 
  array (
    'factories' => 
    array (
      'symfony_cli' => 'DoctrineModule\\Service\\SymfonyCliRouteFactory',
    ),
  ),
  'console' => 
  array (
    'router' => 
    array (
      'routes' => 
      array (
        'doctrine_cli' => 
        array (
          'type' => 'symfony_cli',
        ),
        'notifier-indicateurs' => 
        array (
          'type' => 'Simple',
          'options' => 
          array (
            'route' => 'notifier indicateurs [--force] --requestUriHost= [--requestUriScheme=]',
            'defaults' => 
            array (
              'controller' => 'Application\\Controller\\Notification',
              'action' => 'notifier-indicateurs',
            ),
          ),
        ),
      ),
    ),
  ),
  'form_elements' => 
  array (
    'aliases' => 
    array (
      'objectselect' => 'DoctrineModule\\Form\\Element\\ObjectSelect',
      'objectradio' => 'DoctrineModule\\Form\\Element\\ObjectRadio',
      'objectmulticheckbox' => 'DoctrineModule\\Form\\Element\\ObjectMultiCheckbox',
    ),
    'factories' => 
    array (
      'DoctrineModule\\Form\\Element\\ObjectSelect' => 'DoctrineORMModule\\Service\\ObjectSelectFactory',
      'DoctrineModule\\Form\\Element\\ObjectRadio' => 'DoctrineORMModule\\Service\\ObjectRadioFactory',
      'DoctrineModule\\Form\\Element\\ObjectMultiCheckbox' => 'DoctrineORMModule\\Service\\ObjectMultiCheckboxFactory',
    ),
    'invokables' => 
    array (
      'UploadForm' => 'UnicaenApp\\Controller\\Plugin\\Upload\\UploadForm',
      'IntervenantDossier' => 'Application\\Form\\Intervenant\\Dossier',
      'IntervenantHeuresCompForm' => 'Application\\Form\\Intervenant\\HeuresCompForm',
      'IntervenantModificationServiceDuForm' => 'Application\\Form\\Intervenant\\ModificationServiceDuForm',
      'IntervenantModificationServiceDuFieldset' => 'Application\\Form\\Intervenant\\ModificationServiceDuFieldset',
      'IntervenantMotifModificationServiceDuFieldset' => 'Application\\Form\\Intervenant\\MotifModificationServiceDuFieldset',
      'ServiceSaisie' => 'Application\\Form\\Service\\Saisie',
      'ServiceSaisieFieldset' => 'Application\\Form\\Service\\SaisieFieldset',
      'ServiceReferentielSaisie' => 'Application\\Form\\ServiceReferentiel\\Saisie',
      'ServiceReferentielSaisieFieldset' => 'Application\\Form\\ServiceReferentiel\\SaisieFieldset',
      'ServiceRechercheForm' => 'Application\\Form\\Service\\RechercheForm',
      'VolumeHoraireSaisie' => 'Application\\Form\\VolumeHoraire\\Saisie',
      'VolumeHoraireSaisieMultipleFieldset' => 'Application\\Form\\VolumeHoraire\\SaisieMultipleFieldset',
      'VolumeHoraireReferentielSaisie' => 'Application\\Form\\VolumeHoraireReferentiel\\Saisie',
      'VolumeHoraireReferentielSaisieMultipleFieldset' => 'Application\\Form\\VolumeHoraireReferentiel\\SaisieMultipleFieldset',
      'FormElementPedagogiqueRechercheFieldset' => 'Application\\Form\\OffreFormation\\ElementPedagogiqueRechercheFieldset',
      'EtapeSaisie' => 'Application\\Form\\OffreFormation\\EtapeSaisie',
      'ElementPedagogiqueSaisie' => 'Application\\Form\\OffreFormation\\ElementPedagogiqueSaisie',
      'EtapeModulateursSaisie' => 'Application\\Form\\OffreFormation\\EtapeModulateursSaisie',
      'ElementModulateursFieldset' => 'Application\\Form\\OffreFormation\\ElementModulateursFieldset',
      'EtapeCentreCoutSaisieForm' => 'Application\\Form\\OffreFormation\\EtapeCentreCout\\EtapeCentreCoutSaisieForm',
      'ElementCentreCoutSaisieFieldset' => 'Application\\Form\\OffreFormation\\EtapeCentreCout\\ElementCentreCoutSaisieFieldset',
      'GestionRoleForm' => 'Application\\Form\\Gestion\\RoleForm',
      'GestionPrivilegesForm' => 'Application\\Form\\Gestion\\PrivilegesForm',
      'AgrementSaisieForm' => 'Application\\Form\\Agrement\\Saisie',
      'PaiementMiseEnPaiementForm' => 'Application\\Form\\Paiement\\MiseEnPaiementForm',
      'PaiementMiseEnPaiementRechercheForm' => 'Application\\Form\\Paiement\\MiseEnPaiementRechercheForm',
    ),
    'initializers' => 
    array (
      0 => 'UnicaenApp\\Service\\EntityManagerAwareInitializer',
    ),
  ),
  'hydrators' => 
  array (
    'factories' => 
    array (
      'DoctrineModule\\Stdlib\\Hydrator\\DoctrineObject' => 'DoctrineORMModule\\Service\\DoctrineObjectHydratorFactory',
    ),
  ),
  'router' => 
  array (
    'routes' => 
    array (
      'doctrine_orm_module_yuml' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/ocra_service_manager_yuml',
          'defaults' => 
          array (
            'controller' => 'DoctrineORMModule\\Yuml\\YumlController',
            'action' => 'index',
          ),
        ),
      ),
      'zfcuser' => 
      array (
        'type' => 'Literal',
        'priority' => 1000,
        'options' => 
        array (
          'route' => '/auth',
          'defaults' => 
          array (
            'controller' => 'zfcuser',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'login' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/connexion',
              'defaults' => 
              array (
                'controller' => 'zfcuser',
                'action' => 'login',
              ),
            ),
          ),
          'authenticate' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/authenticate',
              'defaults' => 
              array (
                'controller' => 'zfcuser',
                'action' => 'authenticate',
              ),
            ),
          ),
          'logout' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/deconnexion',
              'defaults' => 
              array (
                'controller' => 'zfcuser',
                'action' => 'logout',
              ),
            ),
          ),
          'register' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/creation-compte',
              'defaults' => 
              array (
                'controller' => 'zfcuser',
                'action' => 'register',
              ),
            ),
          ),
          'changepassword' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/change-password',
              'defaults' => 
              array (
                'controller' => 'zfcuser',
                'action' => 'changepassword',
              ),
            ),
          ),
          'changeemail' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/change-email',
              'defaults' => 
              array (
                'controller' => 'zfcuser',
                'action' => 'changeemail',
              ),
            ),
          ),
        ),
      ),
      'home' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/',
          'defaults' => 
          array (
            'controller' => 'Application\\Controller\\Index',
            'action' => 'index',
          ),
        ),
      ),
      'apropos' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/apropos',
          'defaults' => 
          array (
            'controller' => 'UnicaenApp\\Controller\\Application',
            'action' => 'apropos',
          ),
        ),
        'priority' => 9999,
      ),
      'contact' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/contact',
          'defaults' => 
          array (
            'controller' => 'UnicaenApp\\Controller\\Application',
            'action' => 'contact',
          ),
        ),
        'priority' => 9999,
      ),
      'plan' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/plan',
          'defaults' => 
          array (
            'controller' => 'UnicaenApp\\Controller\\Application',
            'action' => 'plan',
          ),
        ),
        'priority' => 9999,
      ),
      'mentions-legales' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/mentions-legales',
          'defaults' => 
          array (
            'controller' => 'UnicaenApp\\Controller\\Application',
            'action' => 'mentions-legales',
          ),
        ),
        'priority' => 9999,
      ),
      'il' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/informatique-et-libertes',
          'defaults' => 
          array (
            'controller' => 'UnicaenApp\\Controller\\Application',
            'action' => 'informatique-et-libertes',
          ),
        ),
        'priority' => 9999,
      ),
      'refresh-session' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/refresh-session',
          'defaults' => 
          array (
            'controller' => 'UnicaenApp\\Controller\\Application',
            'action' => 'refresh-session',
          ),
        ),
      ),
      'application' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/application',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Index',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/[:controller[/:action]]',
              'constraints' => 
              array (
                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Index',
              ),
            ),
          ),
        ),
      ),
      'utilisateur' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/utilisateur',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'UnicaenAuth\\Controller',
            'controller' => 'Utilisateur',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:action[/:id]',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'id' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'index',
              ),
            ),
          ),
        ),
      ),
      'intervenant' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/intervenant',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Intervenant',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:action[/:intervenant]',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'index',
              ),
            ),
          ),
          'rechercher' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/rechercher[/:intervenant]',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'rechercher',
              ),
            ),
          ),
          'fiche' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'voir',
              ),
            ),
          ),
          'voir-heures-comp' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/voir-heures-comp/:intervenant',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'voir-heures-comp',
              ),
            ),
          ),
          'formule-totaux-hetd' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/formule-totaux-hetd/:intervenant/:typeVolumeHoraire/:etatVolumeHoraire',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'intervenant' => '[0-9]*',
                'typeVolumeHoraire' => '[0-9]*',
                'etatVolumeHoraire' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'formule-totaux-hetd',
              ),
            ),
          ),
          'feuille-de-route' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/feuille-de-route',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'feuille-de-route',
              ),
            ),
          ),
          'modification-service-du' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/modification-service-du',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'ModificationServiceDu',
                'action' => 'saisir',
              ),
            ),
          ),
          'saisir-dossier' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/saisir-dossier',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Dossier',
                'action' => 'modifier',
              ),
            ),
          ),
          'services' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/services',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Application\\Controller\\Service',
                'action' => 'index',
                'type-volume-horaire-code' => 'PREVU',
              ),
            ),
          ),
          'referentiel' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/referentiel',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Application\\Controller\\ServiceReferentiel',
                'action' => 'index',
                'type-volume-horaire-code' => 'PREVU',
              ),
            ),
          ),
          'services-realises' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/services-realises',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Application\\Controller\\Service',
                'action' => 'index',
                'type-volume-horaire-code' => 'REALISE',
              ),
            ),
          ),
          'referentiel-realise' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/referentiel',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Application\\Controller\\ServiceReferentiel',
                'action' => 'index',
                'type-volume-horaire-code' => 'REALISE',
              ),
            ),
          ),
          'cloturer-saisie' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/services/:type-volume-horaire-code/cloturer',
              'constraints' => 
              array (
                'id' => '[0-9]*',
                'type-volume-horaire-code' => '[a-zA-Z0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Application\\Controller\\Service',
                'action' => 'cloturer-saisie',
              ),
            ),
          ),
          'demande-mise-en-paiement' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/demande-mise-en-paiement',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Application\\Controller\\Paiement',
                'action' => 'demandeMiseEnPaiement',
              ),
            ),
          ),
          'validation-dossier' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/validation/dossier',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Validation',
                'action' => 'dossier',
              ),
            ),
          ),
          'validation-service' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/validation/service-prevu',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Validation',
                'action' => 'service',
                'type-volume-horaire-code' => 'PREVU',
              ),
            ),
          ),
          'validation-service-realise' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/validation/service-realise',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Validation',
                'action' => 'service',
                'type-volume-horaire-code' => 'REALISE',
              ),
            ),
          ),
          'validation-referentiel' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/validation/referentiel',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Validation',
                'action' => 'referentiel',
                'type-volume-horaire-code' => 'PREVU',
              ),
            ),
          ),
          'validation-referentiel-realise' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/validation/referentiel-realise',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Validation',
                'action' => 'referentiel',
                'type-volume-horaire-code' => 'REALISE',
              ),
            ),
          ),
          'contrat' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/contrat',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Contrat',
                'action' => 'index',
              ),
            ),
          ),
          'agrement' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant/agrement',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'controller' => 'Agrement',
                'action' => 'index',
              ),
            ),
            'may_terminate' => true,
            'child_routes' => 
            array (
              'liste' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/:typeAgrement',
                  'constraints' => 
                  array (
                    'typeAgrement' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'lister',
                  ),
                ),
              ),
              'ajouter' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/:typeAgrement/ajouter',
                  'constraints' => 
                  array (
                    'typeAgrement' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'ajouter',
                  ),
                ),
              ),
              'voir' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/voir/:agrement',
                  'constraints' => 
                  array (
                    'agrement' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'voir',
                  ),
                ),
              ),
              'voir-str' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/:typeAgrement/voir-str[/:structure]',
                  'constraints' => 
                  array (
                    'typeAgrement' => '[0-9]*',
                    'structure' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'voir-str',
                  ),
                ),
              ),
              'modifier' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/modifier/:agrement',
                  'constraints' => 
                  array (
                    'agrement' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'modifier',
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
      'piece-jointe' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/piece-jointe',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'PieceJointe',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'intervenant' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/intervenant/:intervenant',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
                'type' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'index',
              ),
            ),
            'may_terminate' => true,
            'child_routes' => 
            array (
              'validation' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/validation',
                ),
                'defaults' => 
                array (
                  'action' => 'index',
                ),
              ),
              'voir' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/voir/:pieceJointe/vue/:vue',
                  'constraints' => 
                  array (
                    'pieceJointe' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'voir',
                  ),
                ),
              ),
              'voir-type' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/voir-type/:typePieceJointe/vue/:vue',
                  'constraints' => 
                  array (
                    'typePieceJointe' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'voir-type',
                  ),
                ),
              ),
              'lister' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/lister/:typePieceJointe',
                  'constraints' => 
                  array (
                    'typePieceJointe' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'lister',
                  ),
                ),
              ),
              'status' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/status',
                  'defaults' => 
                  array (
                    'action' => 'status',
                  ),
                ),
              ),
              'ajouter' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/ajouter/:typePieceJointe',
                  'constraints' => 
                  array (
                    'typePieceJointe' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'ajouter',
                  ),
                ),
              ),
              'supprimer' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/supprimer/:pieceJointe[/fichier/:fichier]',
                  'constraints' => 
                  array (
                    'pieceJointe' => '[0-9]*',
                    'fichier' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'supprimer',
                  ),
                ),
              ),
              'telecharger' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/telecharger/:pieceJointe[/fichier/:fichier/:nomFichier]',
                  'constraints' => 
                  array (
                    'pieceJointe' => '[0-9]*',
                    'fichier' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'telecharger',
                  ),
                ),
              ),
              'valider' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/valider/:pieceJointe[/fichier/:fichier]',
                  'constraints' => 
                  array (
                    'pieceJointe' => '[0-9]*',
                    'fichier' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'valider',
                  ),
                ),
              ),
              'devalider' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/devalider/:pieceJointe[/fichier/:fichier]',
                  'constraints' => 
                  array (
                    'pieceJointe' => '[0-9]*',
                    'fichier' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'devalider',
                  ),
                ),
              ),
            ),
          ),
          'type-piece-jointe-statut' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/type-piece-jointe-statut',
              'defaults' => 
              array (
                'action' => 'type-piece-jointe-statut',
              ),
            ),
          ),
          'modifier-type-piece-jointe-statut' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/modifier-type-piece-jointe-statut/:typePieceJointe/:statutIntervenant/:premierRecrutement',
              'defaults' => 
              array (
                'action' => 'modifier-type-piece-jointe-statut',
              ),
            ),
          ),
        ),
      ),
      'structure' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/structure',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Structure',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'modifier' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/modifier/:id',
              'constraints' => 
              array (
                'id' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'modifier',
              ),
            ),
          ),
          'recherche' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/recherche[/:term]',
              'defaults' => 
              array (
                'action' => 'recherche',
              ),
            ),
          ),
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:action[/:id]',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'id' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'index',
              ),
            ),
          ),
        ),
      ),
      'etablissement' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/etablissement',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'etablissement',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'modifier' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/modifier/:id',
              'constraints' => 
              array (
                'id' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'modifier',
              ),
            ),
          ),
          'recherche' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/recherche[/:term]',
              'defaults' => 
              array (
                'action' => 'recherche',
              ),
            ),
          ),
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:action[/:id]',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'id' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'index',
              ),
            ),
          ),
        ),
      ),
      'recherche' => 
      array (
        'type' => 'Segment',
        'options' => 
        array (
          'route' => '/recherche/:action',
          'constraints' => 
          array (
            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            'typeIntervenant' => '[0-9]*',
            'structure' => '[0-9]*',
          ),
          'defaults' => 
          array (
            'controller' => 'Application\\Controller\\Recherche',
          ),
        ),
      ),
      'service' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/service',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Service',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'resume' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/resume',
              'defaults' => 
              array (
                'action' => 'resume',
              ),
            ),
          ),
          'export' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/export',
              'defaults' => 
              array (
                'action' => 'export',
              ),
            ),
          ),
          'resume-refresh' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/resume-refresh',
              'defaults' => 
              array (
                'action' => 'resumeRefresh',
              ),
            ),
          ),
          'modifier' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/modifier/:id',
              'constraints' => 
              array (
                'id' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'modifier',
              ),
            ),
          ),
          'recherche' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/recherche',
              'defaults' => 
              array (
                'action' => 'recherche',
              ),
            ),
            'child_routes' => 
            array (
              'default' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/',
                  'defaults' => 
                  array (
                    'action' => 'recherche',
                  ),
                ),
              ),
            ),
          ),
          'rafraichir-ligne' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/rafraichir-ligne/:service',
              'constraints' => 
              array (
                'service' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'rafraichir-ligne',
              ),
            ),
          ),
          'intervenant' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/intervenant/:intervenant',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'index',
              ),
            ),
          ),
          'saisie' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/saisie[/:id]',
              'constraints' => 
              array (
                'id' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'saisie',
              ),
            ),
          ),
          'constatation' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/constatation',
              'defaults' => 
              array (
                'action' => 'constatation',
              ),
            ),
          ),
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:action[/:id]',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
              ),
              'defaults' => 
              array (
                'action' => 'index',
                'id' => '[0-9]*',
              ),
            ),
          ),
        ),
      ),
      'referentiel' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/referentiel',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'ServiceReferentiel',
          ),
        ),
        'may_terminate' => false,
        'child_routes' => 
        array (
          'saisie' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/saisie[/:id]',
              'constraints' => 
              array (
                'id' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'saisie',
              ),
            ),
          ),
          'rafraichir-ligne' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/rafraichir-ligne/:serviceReferentiel',
              'constraints' => 
              array (
                'serviceReferentiel' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'rafraichir-ligne',
              ),
            ),
          ),
          'constatation' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/constatation',
              'defaults' => 
              array (
                'action' => 'constatation',
              ),
            ),
          ),
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:action[/:id]',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'id' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'index',
              ),
            ),
          ),
        ),
      ),
      'volume-horaire' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/volume-horaire',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'VolumeHoraire',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:action[/:id]',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'id' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'index',
              ),
            ),
          ),
          'saisie' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/saisie/:service',
              'constraints' => 
              array (
                'service' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'saisie',
              ),
            ),
          ),
          'modifier' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/modifier/:id',
              'constraints' => 
              array (
                'id' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'modifier',
              ),
            ),
          ),
        ),
      ),
      'volume-horaire-referentiel' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/volume-horaire-referentiel',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'VolumeHoraireReferentiel',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:action[/:id]',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'id' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'index',
              ),
            ),
          ),
          'saisie' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/saisie/:serviceReferentiel',
              'constraints' => 
              array (
                'serviceReferentiel' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'saisie',
              ),
            ),
          ),
          'modifier' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/modifier/:id',
              'constraints' => 
              array (
                'id' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'modifier',
              ),
            ),
          ),
        ),
      ),
      'of' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/offre-de-formation',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'OffreFormation',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'default' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:action[/:id]',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'id' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'index',
              ),
            ),
          ),
          'element' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/element',
              'defaults' => 
              array (
                '__NAMESPACE__' => 'Application\\Controller\\OffreFormation',
                'controller' => 'ElementPedagogique',
              ),
            ),
            'may_terminate' => false,
            'child_routes' => 
            array (
              'default' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/:action[/:id]',
                  'constraints' => 
                  array (
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'id' => '[0-9]*',
                  ),
                ),
              ),
              'voir' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/voir/:id',
                  'constraints' => 
                  array (
                    'id' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'voir',
                  ),
                ),
              ),
              'apercevoir' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/apercevoir/:id',
                  'constraints' => 
                  array (
                    'id' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'apercevoir',
                  ),
                ),
              ),
              'ajouter' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/ajouter/:etape',
                  'constraints' => 
                  array (
                    'etape' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'ajouter',
                  ),
                ),
              ),
              'modifier' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/modifier/:etape/:id',
                  'constraints' => 
                  array (
                    'etape' => '[0-9]*',
                    'id' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'modifier',
                  ),
                ),
              ),
              'supprimer' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/supprimer/:id',
                  'constraints' => 
                  array (
                    'id' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'supprimer',
                  ),
                ),
              ),
              'get-periode' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/get-periode/:elementPedagogique',
                  'constraints' => 
                  array (
                    'elementPedagogique' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'getPeriode',
                  ),
                ),
              ),
            ),
          ),
          'etape' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/etape',
              'defaults' => 
              array (
                '__NAMESPACE__' => 'Application\\Controller\\OffreFormation',
                'controller' => 'Etape',
              ),
            ),
            'may_terminate' => false,
            'child_routes' => 
            array (
              'default' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/:action[/:id]',
                  'constraints' => 
                  array (
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'id' => '[0-9]*',
                  ),
                ),
              ),
              'voir' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/voir/:id',
                  'constraints' => 
                  array (
                    'id' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'voir',
                  ),
                ),
              ),
              'apercevoir' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/apercevoir/:id',
                  'constraints' => 
                  array (
                    'id' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'apercevoir',
                  ),
                ),
              ),
              'ajouter' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/ajouter/:structure[/niveau/:niveau]',
                  'constraints' => 
                  array (
                    'structure' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'ajouter',
                  ),
                ),
              ),
              'modifier' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/modifier/:structure/:id',
                  'constraints' => 
                  array (
                    'id' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'modifier',
                  ),
                ),
              ),
              'supprimer' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/supprimer/:id',
                  'constraints' => 
                  array (
                    'id' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'supprimer',
                  ),
                ),
              ),
              'modulateurs' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/modulateurs/:id',
                  'constraints' => 
                  array (
                    'etape' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    '__NAMESPACE__' => 'Application\\Controller\\OffreFormation',
                    'controller' => 'Modulateur',
                    'action' => 'saisir',
                  ),
                ),
              ),
              'centres-couts' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/centres-couts/:id',
                  'constraints' => 
                  array (
                    'etape' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'controller' => 'EtapeCentreCout',
                    'action' => 'saisir',
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
      'contrat' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/contrat',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Contrat',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'creer' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'creer-contrat',
              ),
            ),
          ),
          'creer-avenant' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'creer-avenant',
              ),
            ),
          ),
          'voir' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'voir',
              ),
            ),
          ),
          'supprimer' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:contrat/supprimer',
              'constraints' => 
              array (
                'contrat' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'supprimer',
              ),
            ),
          ),
          'valider' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:contrat/valider',
              'constraints' => 
              array (
                'contrat' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'valider',
              ),
            ),
          ),
          'devalider' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:contrat/devalider',
              'constraints' => 
              array (
                'contrat' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'devalider',
              ),
            ),
          ),
          'saisir-retour' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:contrat/saisir-retour',
              'constraints' => 
              array (
                'contrat' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'saisir-retour',
              ),
            ),
          ),
          'exporter' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:contrat/exporter',
              'constraints' => 
              array (
                'contrat' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'exporter',
              ),
            ),
          ),
          'deposer-fichier' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:contrat/deposer-fichier',
              'constraints' => 
              array (
                'contrat' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'deposer-fichier',
              ),
            ),
          ),
          'lister-fichier' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:contrat/lister-fichier',
              'constraints' => 
              array (
                'contrat' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'lister-fichier',
              ),
            ),
          ),
          'telecharger-fichier' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:contrat/telecharger-fichier[/:fichier/:nomFichier]',
              'constraints' => 
              array (
                'contrat' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'telecharger-fichier',
              ),
            ),
          ),
          'supprimer-fichier' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:contrat/supprimer-fichier[/:fichier]',
              'constraints' => 
              array (
                'contrat' => '[0-9]*',
                'fichier' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'supprimer-fichier',
              ),
            ),
          ),
        ),
      ),
      'validation' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/validation',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Validation',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'voir' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:validation',
              'constraints' => 
              array (
                'validation' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'voir',
                'validation' => 0,
              ),
            ),
          ),
          'supprimer' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:validation/supprimer',
              'constraints' => 
              array (
                'validation' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'supprimer',
              ),
            ),
          ),
          'liste' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:typeValidation/:intervenant/liste',
              'constraints' => 
              array (
                'typeValidation' => '[0-9]*',
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'liste',
                'typeValidation' => 0,
                'intervenant' => 0,
              ),
            ),
          ),
        ),
      ),
      'gestion' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/gestion',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Gestion',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'droits' => 
          array (
            'type' => 'Literal',
            'may_terminate' => true,
            'options' => 
            array (
              'route' => '/droits',
              'defaults' => 
              array (
                'action' => 'droits',
              ),
            ),
            'child_routes' => 
            array (
              'roles' => 
              array (
                'type' => 'Segment',
                'may_terminate' => true,
                'options' => 
                array (
                  'route' => '/roles',
                  'defaults' => 
                  array (
                    'action' => 'roles',
                  ),
                ),
                'child_routes' => 
                array (
                  'edition' => 
                  array (
                    'type' => 'Segment',
                    'may_terminate' => true,
                    'options' => 
                    array (
                      'route' => '/edition[/:role]',
                      'constraints' => 
                      array (
                        'role' => '[0-9]*',
                      ),
                      'defaults' => 
                      array (
                        'action' => 'role-edition',
                      ),
                    ),
                  ),
                  'suppression' => 
                  array (
                    'type' => 'Segment',
                    'may_terminate' => true,
                    'options' => 
                    array (
                      'route' => '/suppression/:role',
                      'constraints' => 
                      array (
                        'role' => '[0-9]*',
                      ),
                      'defaults' => 
                      array (
                        'action' => 'role-suppression',
                      ),
                    ),
                  ),
                ),
              ),
              'privileges' => 
              array (
                'type' => 'Literal',
                'may_terminate' => true,
                'options' => 
                array (
                  'route' => '/privileges',
                  'defaults' => 
                  array (
                    'action' => 'privileges',
                  ),
                ),
                'child_routes' => 
                array (
                  'modifier' => 
                  array (
                    'type' => 'Segment',
                    'may_terminate' => true,
                    'options' => 
                    array (
                      'route' => '/modifier',
                      'defaults' => 
                      array (
                        'action' => 'privileges-modifier',
                      ),
                    ),
                  ),
                ),
              ),
            ),
          ),
          'agrement' => 
          array (
            'type' => 'Literal',
            'options' => 
            array (
              'route' => '/agrement',
              'defaults' => 
              array (
                'controller' => 'Agrement',
                'action' => 'index',
              ),
            ),
            'may_terminate' => true,
            'child_routes' => 
            array (
              'ajouter-lot' => 
              array (
                'type' => 'Segment',
                'options' => 
                array (
                  'route' => '/:typeAgrement/ajouter-lot',
                  'constraints' => 
                  array (
                    'typeAgrement' => '[0-9]*',
                  ),
                  'defaults' => 
                  array (
                    'action' => 'ajouter-lot',
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
      'workflow' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/workflow',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Workflow',
          ),
        ),
        'may_terminate' => false,
        'child_routes' => 
        array (
          'nav-next' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:intervenant',
              'constraints' => 
              array (
                'intervenant' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'nav-next',
              ),
            ),
          ),
        ),
      ),
      'indicateur' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/gestion/indicateur',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Indicateur',
            'action' => 'index',
          ),
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'result' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:indicateur[/structure/:structure]',
              'constraints' => 
              array (
                'indicateur' => '[0-9]*',
                'structure' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'result',
              ),
            ),
          ),
          'abonner' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:indicateur/abonner',
              'constraints' => 
              array (
                'indicateur' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'abonner',
              ),
            ),
          ),
          'abonnements' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/:personnel/abonnements',
              'constraints' => 
              array (
                'personnel' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'abonnements',
              ),
            ),
          ),
          'result-item' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/result-item/:action/:intervenant',
              'constraints' => 
              array (
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'intervenant' => '[0-9]*',
              ),
            ),
          ),
        ),
      ),
      'notification' => 
      array (
        'type' => 'Literal',
        'options' => 
        array (
          'route' => '/notification',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Notification',
          ),
        ),
        'may_terminate' => false,
        'child_routes' => 
        array (
          'indicateurs' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/indicateurs',
              'defaults' => 
              array (
                'action' => 'indicateurs',
              ),
            ),
          ),
          'indicateur-fetch-title' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/indicateur-fetch-title',
              'defaults' => 
              array (
                'action' => 'indicateur-fetch-title',
              ),
            ),
          ),
          'indicateur-intervenants' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/indicateur-intervenants/:indicateur',
              'constraints' => 
              array (
                'indicateur' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'indicateur-intervenants',
              ),
            ),
          ),
          'notifier-indicateurs' => 
          array (
            'type' => 'Segment',
            'options' => 
            array (
              'route' => '/notifier-indicateurs',
              'defaults' => 
              array (
                'action' => 'notifier-indicateurs',
              ),
            ),
          ),
        ),
      ),
      'paiement' => 
      array (
        'type' => 'Literal',
        'may_terminate' => true,
        'options' => 
        array (
          'route' => '/paiement',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Application\\Controller',
            'controller' => 'Paiement',
            'action' => 'index',
          ),
        ),
        'child_routes' => 
        array (
          'etat-demande-paiement' => 
          array (
            'type' => 'Literal',
            'may_terminate' => true,
            'options' => 
            array (
              'route' => '/etat-demande-paiement',
              'defaults' => 
              array (
                'action' => 'etatPaiement',
                'etat' => 'a-mettre-en-paiement',
              ),
            ),
          ),
          'mise-en-paiement' => 
          array (
            'type' => 'Segment',
            'may_terminate' => true,
            'options' => 
            array (
              'route' => '/mise-en-paiement/:structure/:intervenants',
              'constraints' => 
              array (
                'structure' => '[0-9]*',
              ),
              'defaults' => 
              array (
                'action' => 'MiseEnPaiement',
              ),
            ),
          ),
          'etat-paiement' => 
          array (
            'type' => 'Literal',
            'may_terminate' => true,
            'options' => 
            array (
              'route' => '/etat-paiement',
              'defaults' => 
              array (
                'action' => 'etatPaiement',
                'etat' => 'mis-en-paiement',
              ),
            ),
          ),
          'mises-en-paiement-csv' => 
          array (
            'type' => 'Literal',
            'may_terminate' => true,
            'options' => 
            array (
              'route' => '/mises-en-paiement-csv',
              'defaults' => 
              array (
                'action' => 'misesEnPaiementCsv',
              ),
            ),
          ),
          'extraction-winpaie' => 
          array (
            'type' => 'Segment',
            'may_terminate' => true,
            'options' => 
            array (
              'route' => '/extraction-winpaie[/:type][/:periode]',
              'defaults' => 
              array (
                'action' => 'extractionWinpaie',
              ),
            ),
          ),
        ),
      ),
      'import' => 
      array (
        'type' => 'Segment',
        'options' => 
        array (
          'route' => '/import[/:action][/:table]',
          'defaults' => 
          array (
            '__NAMESPACE__' => 'Import\\Controller',
            'controller' => 'Import',
            'action' => 'index',
            'table' => NULL,
          ),
        ),
      ),
      'debug' => 
      array (
        'type' => 'segment',
        'options' => 
        array (
          'route' => '/debug[/:action]',
          'defaults' => 
          array (
            'controller' => 'Debug\\Controller\\Debug',
            'action' => 'index',
          ),
        ),
      ),
    ),
  ),
  'view_manager' => 
  array (
    'template_map' => 
    array (
      'zend-developer-tools/toolbar/doctrine-orm-queries' => '/var/www/OSE/vendor/doctrine/doctrine-orm-module/config/../view/zend-developer-tools/toolbar/doctrine-orm-queries.phtml',
      'zend-developer-tools/toolbar/doctrine-orm-mappings' => '/var/www/OSE/vendor/doctrine/doctrine-orm-module/config/../view/zend-developer-tools/toolbar/doctrine-orm-mappings.phtml',
      'error/403' => '/var/www/OSE/vendor/unicaen/unicaen-auth/config/../view/error/403.phtml',
      'zend-developer-tools/toolbar/bjy-authorize-role' => '/var/www/OSE/vendor/bjyoungblood/bjy-authorize/config/../view/zend-developer-tools/toolbar/bjy-authorize-role.phtml',
      'layout/layout' => '/var/www/OSE/vendor/unicaen/unicaen-app/config/../view/layout/layout.phtml',
      'error/404' => '/var/www/OSE/vendor/unicaen/unicaen-app/config/../view/error/404.phtml',
      'error/index' => '/var/www/OSE/vendor/unicaen/unicaen-app/config/../view/error/index.phtml',
    ),
    'template_path_stack' => 
    array (
      'zfcuser' => '/var/www/OSE/vendor/zf-commons/zfc-user/config/../view',
      0 => '/var/www/OSE/vendor/unicaen/unicaen-app/config/../view',
      'unicaen-auth' => '/var/www/OSE/vendor/unicaen/unicaen-auth/config/../view',
      1 => '/var/www/OSE/module/Application/config/../view',
      'import' => '/var/www/OSE/module/Import/config/../view',
      'debug' => '/var/www/modules/Debug/config/../view',
      'zenddevelopertools' => '/var/www/OSE/vendor/zendframework/zend-developer-tools/config/../view',
    ),
    'display_not_found_reason' => true,
    'not_found_template' => 'error/404',
    'display_exceptions' => true,
    'exception_template' => 'error/index',
    'doctype' => 'HTML5',
    'layout' => 'layout/layout',
    'strategies' => 
    array (
      0 => 'ViewJsonStrategy',
      1 => 'ViewCsvStrategy',
    ),
  ),
  'zenddevelopertools' => 
  array (
    'profiler' => 
    array (
      'collectors' => 
      array (
        'doctrine.sql_logger_collector.orm_default' => 'doctrine.sql_logger_collector.orm_default',
        'doctrine.mapping_collector.orm_default' => 'doctrine.mapping_collector.orm_default',
        'bjy_authorize_role_collector' => 'BjyAuthorize\\Collector\\RoleCollector',
      ),
      'enabled' => true,
      'strict' => true,
      'flush_early' => false,
      'cache_dir' => 'data/cache',
      'matcher' => 
      array (
      ),
    ),
    'toolbar' => 
    array (
      'entries' => 
      array (
        'doctrine.sql_logger_collector.orm_default' => 'zend-developer-tools/toolbar/doctrine-orm-queries',
        'doctrine.mapping_collector.orm_default' => 'zend-developer-tools/toolbar/doctrine-orm-mappings',
        'bjy_authorize_role_collector' => 'zend-developer-tools/toolbar/bjy-authorize-role',
        'config' => false,
        'db' => false,
      ),
      'enabled' => true,
      'auto_hide' => false,
      'position' => 'bottom',
      'version_check' => false,
    ),
    'events' => 
    array (
      'enabled' => true,
      'collectors' => 
      array (
      ),
      'identifiers' => 
      array (
      ),
    ),
  ),
  'bjyauthorize' => 
  array (
    'default_role' => 'guest',
    'authenticated_role' => 'user',
    'identity_provider' => 'UnicaenAuth\\Provider\\Identity\\Chain',
    'role_providers' => 
    array (
      'UnicaenAuth\\Provider\\Role\\Config' => 
      array (
        'guest' => 
        array (
          'name' => 'Non authentifié(e)',
          'selectable' => false,
          'children' => 
          array (
            'user' => 
            array (
              'name' => 'Authentifié(e)',
              'selectable' => false,
            ),
          ),
        ),
      ),
      'UnicaenAuth\\Provider\\Role\\DbRole' => 
      array (
        'object_manager' => 'doctrine.entitymanager.orm_default',
        'role_entity_class' => 'UnicaenAuth\\Entity\\Db\\Role',
      ),
      'UnicaenAuth\\Provider\\Role\\Username' => 
      array (
      ),
      'ApplicationRoleProvider' => 
      array (
        0 => 'Application\\Acl\\Role',
        1 => 'Application\\Acl\\AdministrateurRole',
        2 => 'Application\\Acl\\ComposanteRole',
        3 => 'Application\\Acl\\DrhRole',
        4 => 'Application\\Acl\\EtablissementRole',
        5 => 'Application\\Acl\\IntervenantRole',
        6 => 'Application\\Acl\\IntervenantExterieurRole',
        7 => 'Application\\Acl\\IntervenantPermanentRole',
      ),
    ),
    'resource_providers' => 
    array (
      'ApplicationPrivilege' => 
      array (
      ),
      'BjyAuthorize\\Provider\\Resource\\Config' => 
      array (
        'Intervenant' => 
        array (
        ),
        'PieceJointe' => 
        array (
        ),
        'Fichier' => 
        array (
        ),
        'Service' => 
        array (
        ),
        'ServiceReferentiel' => 
        array (
        ),
        'ServiceListView' => 
        array (
        ),
        'ServiceController' => 
        array (
        ),
        'Contrat' => 
        array (
        ),
        'Validation' => 
        array (
        ),
        'Agrement' => 
        array (
        ),
        'MiseEnPaiement' => 
        array (
        ),
      ),
    ),
    'rule_providers' => 
    array (
      'Application\\Provider\\Rule\\PrivilegeRuleProvider' => 
      array (
        'allow' => 
        array (
          0 => 
          array (
            0 => 'modif-service-du-edition',
            1 => 'Intervenant',
            2 => 
            array (
              0 => 'modif-service-du-edition',
            ),
            3 => 'ModificationServiceDuAssertion',
          ),
          1 => 
          array (
            0 => 'mise-en-paiement-demande',
            1 => 'MiseEnPaiement',
            2 => 
            array (
              0 => 'mise-en-paiement-demande',
            ),
            3 => 'MiseEnPaiementAssertion',
          ),
        ),
      ),
      'BjyAuthorize\\Provider\\Rule\\Config' => 
      array (
        'allow' => 
        array (
          0 => 
          array (
            0 => 
            array (
              0 => 'administrateur',
              1 => 'composante',
              2 => 'drh',
              3 => 'etablissement',
              4 => 'intervenant',
            ),
            1 => 'Intervenant',
            2 => 
            array (
              0 => 'total-heures-comp',
            ),
            3 => 'IntervenantAssertion',
          ),
          1 => 
          array (
            0 => 
            array (
              0 => 'intervenant-exterieur',
              1 => 'composante',
              2 => 'administrateur',
            ),
            1 => 'PieceJointe',
            2 => 
            array (
              0 => 'create',
              1 => 'read',
              2 => 'delete',
              3 => 'create-fichier',
            ),
            3 => 'PieceJointeAssertion',
          ),
          2 => 
          array (
            0 => 
            array (
              0 => 'composante',
              1 => 'administrateur',
            ),
            1 => 'PieceJointe',
            2 => 
            array (
              0 => 'valider',
              1 => 'devalider',
            ),
            3 => 'PieceJointeAssertion',
          ),
          3 => 
          array (
            0 => 
            array (
              0 => 'intervenant-exterieur',
              1 => 'composante',
              2 => 'administrateur',
            ),
            1 => 'Fichier',
            2 => 
            array (
              0 => 'create',
              1 => 'read',
              2 => 'delete',
              3 => 'telecharger',
            ),
            3 => 'FichierAssertion',
          ),
          4 => 
          array (
            0 => 
            array (
              0 => 'composante',
              1 => 'administrateur',
            ),
            1 => 'Fichier',
            2 => 
            array (
              0 => 'valider',
              1 => 'devalider',
            ),
            3 => 'FichierAssertion',
          ),
          5 => 
          array (
            0 => 
            array (
              0 => 'user',
            ),
            1 => 'Service',
            2 => 
            array (
              0 => 'create',
              1 => 'read',
              2 => 'delete',
              3 => 'update',
            ),
            3 => 'ServiceAssertion',
          ),
          6 => 
          array (
            0 => 
            array (
              0 => 'composante',
            ),
            1 => 'ServiceListView',
            2 => 
            array (
              0 => 'info-only-structure',
            ),
            3 => 'ServiceAssertion',
          ),
          7 => 
          array (
            0 => 
            array (
              0 => 'intervenant',
            ),
            1 => 'ServiceListView',
            2 => 
            array (
              0 => 'aide-intervenant',
            ),
            3 => 'ServiceAssertion',
          ),
          8 => 
          array (
            0 => 
            array (
              0 => 'intervenant',
              1 => 'composante',
              2 => 'administrateur',
            ),
            1 => 'ServiceReferentiel',
            2 => 
            array (
              0 => 'create',
              1 => 'read',
              2 => 'delete',
              3 => 'update',
            ),
            3 => 'ServiceReferentielAssertion',
          ),
          9 => 
          array (
            0 => 
            array (
              0 => 'intervenant-exterieur',
              1 => 'composante',
              2 => 'administrateur',
            ),
            1 => 'Contrat',
            2 => 
            array (
              0 => 'read',
            ),
            3 => 'ContratAssertion',
          ),
          10 => 
          array (
            0 => 
            array (
              0 => 'composante',
              1 => 'administrateur',
            ),
            1 => 'Contrat',
            2 => 
            array (
              0 => 'create',
              1 => 'delete',
              2 => 'update',
              3 => 'exporter',
              4 => 'valider',
              5 => 'devalider',
              6 => 'date_retour',
              7 => 'deposer',
            ),
            3 => 'ContratAssertion',
          ),
          11 => 
          array (
            0 => 
            array (
              0 => 'intervenant',
              1 => 'composante',
              2 => 'administrateur',
            ),
            1 => 'Validation',
            2 => 
            array (
              0 => 'read',
            ),
            3 => 'ValidationAssertion',
          ),
          12 => 
          array (
            0 => 
            array (
              0 => 'intervenant',
              1 => 'composante',
              2 => 'administrateur',
            ),
            1 => 'Validation',
            2 => 
            array (
              0 => 'create',
              1 => 'delete',
              2 => 'update',
            ),
            3 => 'ValidationAssertion',
          ),
          13 => 
          array (
            0 => 
            array (
              0 => 'composante',
              1 => 'administrateur',
            ),
            1 => 'Agrement',
            2 => 
            array (
              0 => 'create',
              1 => 'read',
              2 => 'delete',
              3 => 'update',
            ),
            3 => 'AgrementAssertion',
          ),
        ),
      ),
    ),
    'guards' => 
    array (
      'BjyAuthorize\\Guard\\Controller' => 
      array (
        0 => 
        array (
          'controller' => 'index',
          'action' => 'index',
          'roles' => 
          array (
          ),
        ),
        1 => 
        array (
          'controller' => 'zfcuser',
          'roles' => 
          array (
          ),
        ),
        2 => 
        array (
          'controller' => 'Application\\Controller\\Index',
          'roles' => 
          array (
          ),
        ),
        3 => 
        array (
          'controller' => 'UnicaenApp\\Controller\\Application',
          'action' => 'etab',
          'roles' => 
          array (
          ),
        ),
        4 => 
        array (
          'controller' => 'UnicaenApp\\Controller\\Application',
          'action' => 'apropos',
          'roles' => 
          array (
          ),
        ),
        5 => 
        array (
          'controller' => 'UnicaenApp\\Controller\\Application',
          'action' => 'contact',
          'roles' => 
          array (
          ),
        ),
        6 => 
        array (
          'controller' => 'UnicaenApp\\Controller\\Application',
          'action' => 'plan',
          'roles' => 
          array (
          ),
        ),
        7 => 
        array (
          'controller' => 'UnicaenApp\\Controller\\Application',
          'action' => 'mentions-legales',
          'roles' => 
          array (
          ),
        ),
        8 => 
        array (
          'controller' => 'UnicaenApp\\Controller\\Application',
          'action' => 'informatique-et-libertes',
          'roles' => 
          array (
          ),
        ),
        9 => 
        array (
          'controller' => 'UnicaenApp\\Controller\\Application',
          'action' => 'refresh-session',
          'roles' => 
          array (
          ),
        ),
        10 => 
        array (
          'controller' => 'UnicaenAuth\\Controller\\Utilisateur',
          'action' => 'selectionner-profil',
          'roles' => 
          array (
          ),
        ),
        11 => 
        array (
          'controller' => 'Application\\Controller\\Intervenant',
          'action' => 
          array (
            0 => 'formule-totaux-hetd',
          ),
          'roles' => 
          array (
            0 => 'administrateur',
            1 => 'composante',
            2 => 'drh',
            3 => 'etablissement',
            4 => 'intervenant',
          ),
          'assertion' => 'IntervenantAssertion',
        ),
        12 => 
        array (
          'controller' => 'Application\\Controller\\Intervenant',
          'action' => 
          array (
            0 => 'apercevoir',
          ),
          'roles' => 
          array (
            0 => 'user',
          ),
        ),
        13 => 
        array (
          'controller' => 'Application\\Controller\\Intervenant',
          'action' => 
          array (
            0 => 'index',
            1 => 'feuille-de-route',
          ),
          'roles' => 
          array (
            0 => 'intervenant',
            1 => 'composante',
            2 => 'administrateur',
          ),
        ),
        14 => 
        array (
          'controller' => 'Application\\Controller\\Intervenant',
          'action' => 
          array (
            0 => 'choisir',
            1 => 'rechercher',
            2 => 'search',
          ),
          'roles' => 
          array (
            0 => 'composante',
            1 => 'administrateur',
          ),
        ),
        15 => 
        array (
          'controller' => 'Application\\Controller\\Dossier',
          'action' => 
          array (
            0 => 'voir',
            1 => 'modifier',
          ),
          'roles' => 
          array (
            0 => 'intervenant-exterieur',
            1 => 'composante',
            2 => 'administrateur',
          ),
        ),
        16 => 
        array (
          'controller' => 'Application\\Controller\\PieceJointe',
          'action' => 
          array (
            0 => 'index',
            1 => 'ajouter',
            2 => 'supprimer',
            3 => 'voir',
            4 => 'voir-type',
            5 => 'lister',
            6 => 'telecharger',
            7 => 'status',
          ),
          'roles' => 
          array (
            0 => 'intervenant-exterieur',
            1 => 'composante',
            2 => 'administrateur',
          ),
          'assertion' => 'PieceJointeAssertion',
        ),
        17 => 
        array (
          'controller' => 'Application\\Controller\\PieceJointe',
          'action' => 
          array (
            0 => 'valider',
            1 => 'devalider',
          ),
          'roles' => 
          array (
            0 => 'composante',
            1 => 'administrateur',
          ),
          'assertion' => 'PieceJointeAssertion',
        ),
        18 => 
        array (
          'controller' => 'Application\\Controller\\PieceJointe',
          'action' => 
          array (
            0 => 'type-piece-jointe-statut',
            1 => 'modifier-type-piece-jointe-statut',
          ),
          'roles' => 
          array (
            0 => 'administrateur',
          ),
        ),
        19 => 
        array (
          'controller' => 'Application\\Controller\\Structure',
          'action' => 
          array (
            0 => 'voir',
            1 => 'apercevoir',
          ),
          'roles' => 
          array (
            0 => 'user',
          ),
        ),
        20 => 
        array (
          'controller' => 'Application\\Controller\\Structure',
          'action' => 
          array (
            0 => 'index',
            1 => 'choisir',
            2 => 'recherche',
          ),
          'roles' => 
          array (
            0 => 'intervenant',
            1 => 'composante',
            2 => 'administrateur',
          ),
        ),
        21 => 
        array (
          'controller' => 'Application\\Controller\\Etablissement',
          'action' => 
          array (
            0 => 'index',
            1 => 'choisir',
            2 => 'recherche',
            3 => 'voir',
            4 => 'apercevoir',
          ),
          'roles' => 
          array (
            0 => 'user',
          ),
        ),
        22 => 
        array (
          'controller' => 'Application\\Controller\\Recherche',
          'roles' => 
          array (
            0 => 'user',
          ),
        ),
        23 => 
        array (
          'controller' => 'Application\\Controller\\Service',
          'action' => 
          array (
            0 => 'index',
            1 => 'export',
            2 => 'saisie',
            3 => 'suppression',
            4 => 'voir',
            5 => 'rafraichir-ligne',
            6 => 'volumes-horaires-refresh',
            7 => 'constatation',
            8 => 'cloturer-saisie',
          ),
          'roles' => 
          array (
            0 => 'user',
          ),
        ),
        24 => 
        array (
          'controller' => 'Application\\Controller\\Service',
          'action' => 
          array (
            0 => 'resume',
            1 => 'resume-refresh',
            2 => 'recherche',
          ),
          'roles' => 
          array (
            0 => 'administrateur',
            1 => 'composante',
            2 => 'drh',
            3 => 'etablissement',
          ),
        ),
        25 => 
        array (
          'controller' => 'Application\\Controller\\ServiceReferentiel',
          'action' => 
          array (
            0 => 'index',
            1 => 'saisie',
            2 => 'suppression',
            3 => 'rafraichir-ligne',
            4 => 'constatation',
          ),
          'roles' => 
          array (
            0 => 'user',
          ),
        ),
        26 => 
        array (
          'controller' => 'Application\\Controller\\VolumeHoraire',
          'action' => 
          array (
            0 => 'voir',
            1 => 'liste',
            2 => 'saisie',
          ),
          'roles' => 
          array (
            0 => 'intervenant',
            1 => 'composante',
            2 => 'administrateur',
          ),
        ),
        27 => 
        array (
          'controller' => 'Application\\Controller\\VolumeHoraireReferentiel',
          'action' => 
          array (
            0 => 'voir',
            1 => 'liste',
            2 => 'saisie',
          ),
          'roles' => 
          array (
            0 => 'intervenant',
            1 => 'composante',
            2 => 'administrateur',
          ),
        ),
        28 => 
        array (
          'controller' => 'Application\\Controller\\OffreFormation',
          'action' => 
          array (
            0 => 'search-structures',
            1 => 'search-niveaux',
          ),
          'roles' => 
          array (
            0 => 'intervenant',
            1 => 'composante',
            2 => 'administrateur',
          ),
        ),
        29 => 
        array (
          'controller' => 'Application\\Controller\\OffreFormation',
          'action' => 
          array (
            0 => 'index',
            1 => 'export',
          ),
          'roles' => 
          array (
            0 => 'composante',
            1 => 'administrateur',
          ),
        ),
        30 => 
        array (
          'controller' => 'Application\\Controller\\OffreFormation\\Etape',
          'action' => 
          array (
            0 => 'voir',
            1 => 'apercevoir',
            2 => 'search',
          ),
          'roles' => 
          array (
            0 => 'user',
          ),
        ),
        31 => 
        array (
          'controller' => 'Application\\Controller\\OffreFormation\\Etape',
          'action' => 
          array (
            0 => 'ajouter',
            1 => 'modifier',
            2 => 'supprimer',
          ),
          'roles' => 
          array (
            0 => 'composante',
            1 => 'administrateur',
          ),
        ),
        32 => 
        array (
          'controller' => 'Application\\Controller\\OffreFormation\\ElementPedagogique',
          'action' => 
          array (
            0 => 'voir',
            1 => 'apercevoir',
            2 => 'search',
            3 => 'getPeriode',
          ),
          'roles' => 
          array (
            0 => 'user',
          ),
        ),
        33 => 
        array (
          'controller' => 'Application\\Controller\\OffreFormation\\ElementPedagogique',
          'action' => 
          array (
            0 => 'ajouter',
            1 => 'modifier',
            2 => 'supprimer',
          ),
          'roles' => 
          array (
            0 => 'composante',
            1 => 'administrateur',
          ),
        ),
        34 => 
        array (
          'controller' => 'Application\\Controller\\OffreFormation\\Modulateur',
          'action' => 
          array (
            0 => 'saisir',
          ),
          'roles' => 
          array (
            0 => 'composante',
            1 => 'administrateur',
          ),
        ),
        35 => 
        array (
          'controller' => 'Application\\Controller\\OffreFormation\\EtapeCentreCout',
          'action' => 
          array (
            0 => 'saisir',
          ),
          'roles' => 
          array (
            0 => 'composante',
            1 => 'administrateur',
          ),
        ),
        36 => 
        array (
          'controller' => 'Application\\Controller\\Contrat',
          'action' => 
          array (
            0 => 'creer',
            1 => 'supprimer',
            2 => 'exporter',
            3 => 'valider',
            4 => 'devalider',
            5 => 'saisir-retour',
            6 => 'deposer-fichier',
            7 => 'supprimer-fichier',
          ),
          'roles' => 
          array (
            0 => 'composante',
            1 => 'administrateur',
          ),
        ),
        37 => 
        array (
          'controller' => 'Application\\Controller\\Contrat',
          'action' => 
          array (
            0 => 'index',
            1 => 'voir',
            2 => 'telecharger-fichier',
            3 => 'lister-fichier',
          ),
          'roles' => 
          array (
            0 => 'intervenant-exterieur',
            1 => 'composante',
            2 => 'administrateur',
          ),
        ),
        38 => 
        array (
          'controller' => 'Application\\Controller\\Validation',
          'action' => 
          array (
            0 => 'index',
            1 => 'liste',
            2 => 'voir',
          ),
          'roles' => 
          array (
            0 => 'intervenant',
            1 => 'composante',
            2 => 'administrateur',
          ),
        ),
        39 => 
        array (
          'controller' => 'Application\\Controller\\Validation',
          'action' => 
          array (
            0 => 'dossier',
          ),
          'roles' => 
          array (
            0 => 'intervenant-exterieur',
            1 => 'composante',
            2 => 'administrateur',
          ),
        ),
        40 => 
        array (
          'controller' => 'Application\\Controller\\Validation',
          'action' => 
          array (
            0 => 'service',
            1 => 'referentiel',
          ),
          'roles' => 
          array (
            0 => 'intervenant',
            1 => 'composante',
            2 => 'administrateur',
          ),
        ),
        41 => 
        array (
          'controller' => 'Application\\Controller\\Validation',
          'action' => 
          array (
            0 => 'referentiel',
          ),
          'roles' => 
          array (
            0 => 'intervenant',
            1 => 'composante',
            2 => 'administrateur',
          ),
        ),
        42 => 
        array (
          'controller' => 'Application\\Controller\\Validation',
          'action' => 
          array (
            0 => 'supprimer',
          ),
          'roles' => 
          array (
            0 => 'composante',
            1 => 'administrateur',
          ),
        ),
        43 => 
        array (
          'controller' => 'Application\\Controller\\Agrement',
          'action' => 
          array (
            0 => 'index',
            1 => 'lister',
            2 => 'voir',
          ),
          'roles' => 
          array (
            0 => 'intervenant',
            1 => 'composante',
            2 => 'administrateur',
          ),
          'assertion' => 'AgrementAssertion',
        ),
        44 => 
        array (
          'controller' => 'Application\\Controller\\Agrement',
          'action' => 
          array (
            0 => 'ajouter',
            1 => 'ajouter-lot',
            2 => 'modifier',
            3 => 'supprimer',
            4 => 'voir-str',
          ),
          'roles' => 
          array (
            0 => 'composante',
            1 => 'administrateur',
          ),
          'assertion' => 'AgrementAssertion',
        ),
        45 => 
        array (
          'controller' => 'Application\\Controller\\Workflow',
          'action' => 
          array (
            0 => 'nav-next',
          ),
          'roles' => 
          array (
            0 => 'user',
          ),
        ),
        46 => 
        array (
          'controller' => 'Application\\Controller\\Indicateur',
          'action' => 
          array (
            0 => 'index',
            1 => 'result',
            2 => 'abonner',
            3 => 'abonnements',
            4 => 'result-item-donnees-perso-diff-import',
          ),
          'roles' => 
          array (
            0 => 'user',
            1 => 'composante',
            2 => 'drh',
            3 => 'administrateur',
          ),
        ),
        47 => 
        array (
          'controller' => 'Application\\Controller\\Notification',
          'action' => 
          array (
            0 => 'indicateurs',
            1 => 'indicateur-fetch-title',
          ),
          'roles' => 
          array (
            0 => 'administrateur',
          ),
        ),
        48 => 
        array (
          'controller' => 'Application\\Controller\\Notification',
          'action' => 
          array (
            0 => 'indicateur-intervenants',
          ),
          'roles' => 
          array (
            0 => 'composante',
            1 => 'administrateur',
          ),
        ),
        49 => 
        array (
          'controller' => 'Application\\Controller\\Notification',
          'action' => 
          array (
            0 => 'notifier-indicateurs',
          ),
          'roles' => 
          array (
          ),
        ),
        50 => 
        array (
          'controller' => 'Debug\\Controller\\Debug',
          'roles' => 
          array (
          ),
        ),
      ),
      'Application\\Guard\\PrivilegeController' => 
      array (
        0 => 
        array (
          'controller' => 'Application\\Controller\\Intervenant',
          'action' => 
          array (
            0 => 'index',
            1 => 'rechercher',
          ),
          'privileges' => 
          array (
            0 => 'intervenant-recherche',
          ),
        ),
        1 => 
        array (
          'controller' => 'Application\\Controller\\Intervenant',
          'action' => 
          array (
            0 => 'voir',
          ),
          'privileges' => 
          array (
            0 => 'intervenant-fiche',
          ),
        ),
        2 => 
        array (
          'controller' => 'Application\\Controller\\ModificationServiceDu',
          'action' => 
          array (
            0 => 'saisir',
          ),
          'privileges' => 
          array (
            0 => 'modif-service-du-visualisation',
          ),
          'assertion' => 'ModificationServiceDuAssertion',
        ),
        3 => 
        array (
          'controller' => 'Application\\Controller\\Intervenant',
          'action' => 
          array (
            0 => 'voir-heures-comp',
          ),
          'privileges' => 
          array (
            0 => 'intervenant-calcul-hetd',
          ),
        ),
        4 => 
        array (
          'controller' => 'Application\\Controller\\Gestion',
          'action' => 
          array (
            0 => 'index',
          ),
          'roles' => 
          array (
            0 => 'composante',
            1 => 'administrateur',
          ),
          'privileges' => 
          array (
            0 => 'mise-en-paiement-export-paie',
            1 => 'mise-en-paiement-visualisation',
          ),
        ),
        5 => 
        array (
          'controller' => 'Application\\Controller\\Gestion',
          'action' => 
          array (
            0 => 'droits',
            1 => 'roles',
            2 => 'privileges',
          ),
          'privileges' => 
          array (
            0 => 'privilege-visualisation',
          ),
        ),
        6 => 
        array (
          'controller' => 'Application\\Controller\\Gestion',
          'action' => 
          array (
            0 => 'role-edition',
            1 => 'role-suppression',
            2 => 'privileges-modifier',
          ),
          'privileges' => 
          array (
            0 => 'privilege-edition',
          ),
        ),
        7 => 
        array (
          'controller' => 'Application\\Controller\\Paiement',
          'action' => 
          array (
            0 => 'demandeMiseEnPaiement',
          ),
          'privileges' => 
          array (
            0 => 'mise-en-paiement-demande',
          ),
          'assertion' => 'MiseEnPaiementAssertion',
        ),
        8 => 
        array (
          'controller' => 'Application\\Controller\\Paiement',
          'action' => 
          array (
            0 => 'etatPaiement',
          ),
          'privileges' => 
          array (
            0 => 'mise-en-paiement-visualisation',
          ),
        ),
        9 => 
        array (
          'controller' => 'Application\\Controller\\Paiement',
          'action' => 
          array (
            0 => 'miseEnPaiement',
          ),
          'privileges' => 
          array (
            0 => 'mise-en-paiement-visualisation',
          ),
        ),
        10 => 
        array (
          'controller' => 'Application\\Controller\\Paiement',
          'action' => 
          array (
            0 => 'misesEnPaiementCsv',
          ),
          'privileges' => 
          array (
            0 => 'mise-en-paiement-export-csv',
          ),
        ),
        11 => 
        array (
          'controller' => 'Application\\Controller\\Paiement',
          'action' => 
          array (
            0 => 'extractionWinpaie',
          ),
          'privileges' => 
          array (
            0 => 'mise-en-paiement-export-paie',
          ),
        ),
        12 => 
        array (
          'controller' => 'Import\\Controller\\Import',
          'action' => 
          array (
            0 => 'index',
          ),
          'privileges' => 
          array (
            0 => 'import-ecarts',
            1 => 'import-maj',
            2 => 'import-tbl',
            3 => 'import-vues-procedures',
          ),
        ),
        13 => 
        array (
          'controller' => 'Import\\Controller\\Import',
          'action' => 
          array (
            0 => 'showDiff',
          ),
          'privileges' => 
          array (
            0 => 'import-ecarts',
          ),
        ),
        14 => 
        array (
          'controller' => 'Import\\Controller\\Import',
          'action' => 
          array (
            0 => 'showImportTbl',
          ),
          'privileges' => 
          array (
            0 => 'import-tbl',
          ),
        ),
        15 => 
        array (
          'controller' => 'Import\\Controller\\Import',
          'action' => 
          array (
            0 => 'update',
            1 => 'updateTables',
          ),
          'privileges' => 
          array (
            0 => 'import-maj',
          ),
        ),
        16 => 
        array (
          'controller' => 'Import\\Controller\\Import',
          'action' => 
          array (
            0 => 'updateViewsAndPackages',
          ),
          'privileges' => 
          array (
            0 => 'import-vues-procedures',
          ),
        ),
      ),
    ),
    'unauthorized_strategy' => 'UnicaenAuth\\View\\RedirectionStrategy',
    'template' => 'error/403',
    'cache_options' => 
    array (
      'adapter' => 
      array (
        'name' => 'memory',
      ),
      'plugins' => 
      array (
        0 => 'serializer',
      ),
    ),
    'cache_key' => 'bjyauthorize_acl',
  ),
  'asset_manager' => 
  array (
    'resolver_configs' => 
    array (
      'paths' => 
      array (
        0 => '/var/www/OSE/vendor/unicaen/unicaen-app/config/../public',
      ),
    ),
  ),
  'view_helpers' => 
  array (
    'invokables' => 
    array (
      'Uploader' => 'UnicaenApp\\View\\Helper\\Upload\\UploaderHelper',
      'historiqueDl' => 'Application\\View\\Helper\\HistoriqueDl',
      'validationDl' => 'Application\\View\\Helper\\ValidationDl',
      'mailto' => 'Application\\View\\Helper\\Mailto',
      'intervenantDl' => 'Application\\View\\Helper\\IntervenantDl',
      'adresseDl' => 'Application\\View\\Helper\\AdresseDl',
      'elementPedagogiqueDl' => 'Application\\View\\Helper\\OffreFormation\\ElementPedagogiqueDl',
      'etapeDl' => 'Application\\View\\Helper\\OffreFormation\\EtapeDl',
      'fieldsetElementPedagogiqueRecherche' => 'Application\\View\\Helper\\OffreFormation\\FieldsetElementPedagogiqueRecherche',
      'formuleTotauxHetd' => 'Application\\View\\Helper\\Intervenant\\TotauxHetdViewHelper',
      'Intervenant' => 'Application\\View\\Helper\\Intervenant\\IntervenantViewHelper',
      'structureDl' => 'Application\\View\\Helper\\StructureDl',
      'structure' => 'Application\\View\\Helper\\StructureViewHelper',
      'etablissementDl' => 'Application\\View\\Helper\\EtablissementDl',
      'etablissement' => 'Application\\View\\Helper\\EtablissementViewHelper',
      'serviceDl' => 'Application\\View\\Helper\\Service\\Dl',
      'serviceReferentielDl' => 'Application\\View\\Helper\\ServiceReferentiel\\Dl',
      'serviceSaisieForm' => 'Application\\View\\Helper\\Service\\SaisieForm',
      'formServiceReferentielSaisie' => 'Application\\View\\Helper\\ServiceReferentiel\\FormSaisie',
      'serviceResume' => 'Application\\View\\Helper\\Service\\Resume',
      'FonctionReferentiel' => 'Application\\View\\Helper\\ServiceReferentiel\\FonctionReferentielViewHelper',
      'volumeHoraireDl' => 'Application\\View\\Helper\\VolumeHoraire\\Dl',
      'volumeHoraireListe' => 'Application\\View\\Helper\\VolumeHoraire\\Liste',
      'volumeHoraireReferentielDl' => 'Application\\View\\Helper\\VolumeHoraireReferentiel\\Dl',
      'volumeHoraireReferentielListe' => 'Application\\View\\Helper\\VolumeHoraireReferentiel\\Liste',
      'EtapeModulateursSaisieForm' => 'Application\\View\\Helper\\OffreFormation\\EtapeModulateursSaisieForm',
      'ElementModulateursSaisieFieldset' => 'Application\\View\\Helper\\OffreFormation\\ElementModulateursSaisieFieldset',
      'ElementPedagogique' => 'Application\\View\\Helper\\OffreFormation\\ElementPedagogique',
      'Etape' => 'Application\\View\\Helper\\OffreFormation\\EtapeViewHelper',
      'FormEtapeCentreCoutSaisie' => 'Application\\View\\Helper\\OffreFormation\\FormEtapeCentreCoutSaisieHelper',
      'FieldsetElementCentreCoutSaisie' => 'Application\\View\\Helper\\OffreFormation\\FieldsetElementCentreCoutSaisieHelper',
      'agrementDl' => 'Application\\View\\Helper\\AgrementDl',
      'isAllowedCRUD' => 'Application\\View\\Helper\\IsAllowedCRUD',
      'Workflow' => 'Application\\View\\Helper\\Workflow',
      'DemandeMiseEnPaiement' => 'Application\\View\\Helper\\Paiement\\DemandeMiseEnPaiementViewHelper',
      'TypeHeures' => 'Application\\View\\Helper\\Paiement\\TypeHeuresViewHelper',
    ),
    'initializers' => 
    array (
      0 => 'UnicaenApp\\Service\\EntityManagerAwareInitializer',
      1 => 'Application\\Service\\Workflow\\WorkflowIntervenantAwareInitializer',
    ),
    'factories' => 
    array (
      'userProfileSelectRadioItem' => 'Application\\View\\Helper\\UserProfileSelectRadioItemFactory',
      'appLink' => 'Application\\View\\Helper\\AppLinkFactory',
      'serviceListe' => 'Application\\View\\Helper\\Service\\ListeFactory',
      'serviceLigne' => 'Application\\View\\Helper\\Service\\LigneFactory',
      'serviceReferentielListe' => 'Application\\View\\Helper\\ServiceReferentiel\\ListeFactory',
      'serviceReferentielLigne' => 'Application\\View\\Helper\\ServiceReferentiel\\LigneFactory',
    ),
    'javascript' => 
    array (
      0 => '/test.js',
    ),
    'css' => 
    array (
    ),
  ),
  'translator' => 
  array (
    'translation_file_patterns' => 
    array (
      0 => 
      array (
        'type' => 'phparray',
        'base_dir' => '/var/www/OSE/vendor/unicaen/unicaen-app/config/../language',
        'pattern' => '/%s/Zend_Captcha.php',
      ),
      1 => 
      array (
        'type' => 'phparray',
        'base_dir' => '/var/www/OSE/vendor/unicaen/unicaen-app/config/../language',
        'pattern' => '/%s/Zend_Validate.php',
      ),
      2 => 
      array (
        'type' => 'gettext',
        'base_dir' => '/var/www/OSE/vendor/unicaen/unicaen-app/config/../language',
        'pattern' => '%s.mo',
      ),
      3 => 
      array (
        'type' => 'gettext',
        'base_dir' => '/var/www/OSE/vendor/unicaen/unicaen-auth/config/../language',
        'pattern' => '%s.mo',
      ),
      4 => 
      array (
        'type' => 'phparray',
        'base_dir' => '/var/www/OSE/module/Common/config/../language',
        'pattern' => '/%s/Oracle_Errors.php',
      ),
      5 => 
      array (
        'type' => 'gettext',
        'base_dir' => '/var/www/OSE/module/Application/config/../language',
        'pattern' => '%s.mo',
      ),
    ),
    'locale' => 'fr_FR',
  ),
  'navigation' => 
  array (
    'default' => 
    array (
      'home' => 
      array (
        'label' => 'Accueil',
        'title' => 'Page d\'accueil de l\'application',
        'route' => 'home',
        'order' => -100,
        'pages' => 
        array (
          'etab' => 
          array (
            'label' => 'Université de Caen - Basse Normandie',
            'title' => 'Page d\'accueil du site de l\'Université de Caen - Basse Normandie',
            'uri' => 'http://www.unicaen.fr/',
            'class' => 'ucbn',
            'visible' => false,
            'footer' => true,
            'resource' => 'controller/UnicaenApp\\Controller\\Application:etab',
            'order' => 1000,
          ),
          'apropos' => 
          array (
            'label' => 'À propos',
            'title' => 'À propos de cette application',
            'route' => 'apropos',
            'class' => 'apropos',
            'visible' => false,
            'footer' => true,
            'sitemap' => true,
            'resource' => 'controller/UnicaenApp\\Controller\\Application:apropos',
            'order' => 1001,
          ),
          'contact' => 
          array (
            'label' => 'Contact',
            'title' => 'Contact concernant l\'application',
            'route' => 'contact',
            'class' => 'contact',
            'visible' => false,
            'footer' => true,
            'sitemap' => true,
            'resource' => 'controller/UnicaenApp\\Controller\\Application:contact',
            'order' => 1002,
          ),
          'plan' => 
          array (
            'label' => 'Plan de navigation',
            'title' => 'Plan de navigation au sein de l\'application',
            'route' => 'plan',
            'class' => 'plan',
            'visible' => false,
            'footer' => true,
            'sitemap' => true,
            'resource' => 'controller/UnicaenApp\\Controller\\Application:plan',
            'order' => 1003,
          ),
          'mentions-legales' => 
          array (
            'label' => 'Mentions légales',
            'title' => 'Mentions légales',
            'uri' => 'http://www.unicaen.fr/outils-portail-institutionnel/mentions-legales/',
            'class' => 'ml',
            'visible' => false,
            'footer' => true,
            'sitemap' => true,
            'resource' => 'controller/UnicaenApp\\Controller\\Application:mentions-legales',
            'order' => 1004,
          ),
          'informatique-et-libertes' => 
          array (
            'label' => 'Informatique et libertés',
            'title' => 'Informatique et libertés',
            'uri' => 'http://www.unicaen.fr/outils-portail-institutionnel/informatique-et-libertes/',
            'class' => 'il',
            'visible' => false,
            'footer' => true,
            'sitemap' => true,
            'resource' => 'controller/UnicaenApp\\Controller\\Application:informatique-et-libertes',
            'order' => 1005,
          ),
          'login' => 
          array (
            'label' => 'Connexion',
            'route' => 'zfcuser/login',
            'visible' => false,
          ),
          'register' => 
          array (
            'label' => 'Enregistrement',
            'route' => 'zfcuser/register',
            'visible' => false,
          ),
          'intervenant' => 
          array (
            'label' => 'Intervenant',
            'title' => 'Intervenant',
            'route' => 'intervenant',
            'resource' => 'controller/Application\\Controller\\Intervenant:index',
            'pages' => 
            array (
              'rechercher' => 
              array (
                'label' => ' Rechercher',
                'title' => 'Rechercher un intervenant',
                'route' => 'intervenant/rechercher',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'icon' => 'glyphicon glyphicon-search',
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Intervenant:rechercher',
              ),
              'fiche' => 
              array (
                'label' => 'Fiche individuelle',
                'title' => 'Consultation de la fiche de l\'intervenant {id}',
                'route' => 'intervenant/fiche',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Intervenant:voir',
              ),
              'voir-heures-comp' => 
              array (
                'label' => 'Calcul HETD',
                'title' => 'Calcul des heures équivalent TD {id}',
                'route' => 'intervenant/voir-heures-comp',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'action' => 'voir-heures-comp',
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Intervenant:voir-heures-comp',
              ),
              'modification-service-du' => 
              array (
                'label' => 'Modification de service dû',
                'title' => 'Modification de service dû de l\'intervenant {id}',
                'route' => 'intervenant/modification-service-du',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\ModificationServiceDu:saisir',
              ),
              'dossier' => 
              array (
                'label' => 'Données personnelles',
                'title' => 'Saisir les données personnelles d\'un intervenant vacataire',
                'route' => 'intervenant/saisir-dossier',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Dossier:modifier',
                'visible' => 'IntervenantNavigationPageVisibility',
              ),
              'service' => 
              array (
                'label' => 'Enseignements prévisionnels',
                'title' => 'Enseignements  prévisionnelsde l\'intervenant',
                'route' => 'intervenant/services',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Service:index',
                'visible' => 'IntervenantNavigationPageVisibility',
              ),
              'pieces-jointes-saisie' => 
              array (
                'label' => 'Pièces justificatives',
                'title' => 'Pièces justificatives du dossier de l\'intervenant',
                'route' => 'piece-jointe/intervenant',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\PieceJointe:index',
                'visible' => 'IntervenantNavigationPageVisibility',
              ),
              'pieces-jointes-validation' => 
              array (
                'label' => 'Validation des pièces justificatives',
                'title' => 'Validation des pièces justificatives du dossier de l\'intervenant',
                'route' => 'piece-jointe/intervenant/validation',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\PieceJointe:index',
                'visible' => 'IntervenantNavigationPageVisibility',
              ),
              'validation-dossier' => 
              array (
                'label' => 'Validation des données personnelles',
                'title' => 'Validation des données personnelles de l\'intervenant',
                'route' => 'intervenant/validation-dossier',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Validation:dossier',
                'visible' => 'IntervenantNavigationPageVisibility',
              ),
              'validation-service-prevu' => 
              array (
                'label' => 'Validation des enseignements prévisionnels',
                'title' => 'Validation des enseignements prévisionnels de l\'intervenant',
                'route' => 'intervenant/validation-service',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Validation:service',
                'visible' => 'IntervenantNavigationPageVisibility',
              ),
              'validation-referentiel-prevu' => 
              array (
                'label' => 'Validation du référentiel prévisionnel',
                'title' => 'Validation du référentiel prévisionnel de l\'intervenant',
                'route' => 'intervenant/validation-referentiel',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Validation:referentiel',
                'visible' => 'IntervenantNavigationPageVisibility',
              ),
              'agrement' => 
              array (
                'label' => 'Agrément',
                'title' => 'Agrément de l\'intervenant',
                'route' => 'intervenant/agrement',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Agrement:index',
                'visible' => 'IntervenantNavigationPageVisibility',
                'pagesProvider' => 
                array (
                  'type' => 'AgrementIntervenantNavigationPagesProvider',
                  'route' => 'intervenant/agrement/liste',
                  'paramsInject' => 
                  array (
                    0 => 'intervenant',
                  ),
                  'withtarget' => true,
                  'resource' => 'controller/Application\\Controller\\Agrement:lister',
                  'visible' => 'IntervenantNavigationPageVisibility',
                ),
              ),
              'contrat' => 
              array (
                'label' => 'Contrat / avenant',
                'title' => 'Contrat et avenants de l\'intervenant',
                'route' => 'intervenant/contrat',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Contrat:index',
                'visible' => 'IntervenantNavigationPageVisibility',
              ),
              'services-realises' => 
              array (
                'label' => 'Enseignements réalisés',
                'title' => 'Constatation des enseignements réalisés',
                'route' => 'intervenant/services-realises',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Service:index',
                'visible' => 'IntervenantNavigationPageVisibility',
              ),
              'validation-service-realise' => 
              array (
                'label' => 'Validation des enseignements réalisés',
                'title' => 'Validation des enseignements réalisés de l\'intervenant',
                'route' => 'intervenant/validation-service-realise',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Validation:service',
                'visible' => 'IntervenantNavigationPageVisibility',
              ),
              'validation-referentiel-realise' => 
              array (
                'label' => 'Validation du référentiel réalisé',
                'title' => 'Validation du référentiel réalisé de l\'intervenant',
                'route' => 'intervenant/validation-referentiel-realise',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Validation:referentiel',
                'visible' => 'IntervenantNavigationPageVisibility',
              ),
              'demande-mise-en-paiement' => 
              array (
                'label' => 'Demande de mise en paiement',
                'title' => 'Demande de mise en paiement',
                'route' => 'intervenant/demande-mise-en-paiement',
                'paramsInject' => 
                array (
                  0 => 'intervenant',
                ),
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Paiement:demandemiseenpaiement',
                'visible' => 'IntervenantNavigationPageVisibility',
              ),
            ),
          ),
          'service' => 
          array (
            'label' => 'Enseignements',
            'title' => 'Résumé des enseignements',
            'route' => 'service/resume',
            'resource' => 'controller/Application\\Controller\\Service:resume',
            'pages' => 
            array (
            ),
          ),
          'of' => 
          array (
            'label' => 'Offre de formation',
            'title' => 'Gestion de l\'offre de formation',
            'route' => 'of',
            'resource' => 'controller/Application\\Controller\\OffreFormation:index',
            'pages' => 
            array (
              'element-ajouter' => 
              array (
                'label' => 'Créer un nouvel enseignement',
                'title' => 'Créer un nouvel enseignement pour la formation sélectionnée',
                'route' => 'of/element/ajouter',
                'resource' => 'controller/Application\\Controller\\OffreFormation\\ElementPedagogique:ajouter',
                'visible' => false,
                'icon' => 'glyphicon glyphicon-plus',
                'category' => 'element',
              ),
              'element-modifier' => 
              array (
                'label' => 'Modifier cet enseignement',
                'title' => 'Modifier cet enseignement',
                'route' => 'of/element/modifier',
                'resource' => 'controller/Application\\Controller\\OffreFormation\\ElementPedagogique:modifier',
                'visible' => false,
                'icon' => 'glyphicon glyphicon-edit',
                'withtarget' => true,
                'category' => 'element',
              ),
              'element-supprimer' => 
              array (
                'label' => 'Supprimer cette formation',
                'title' => 'Supprimer cette formation',
                'route' => 'of/element/supprimer',
                'resource' => 'controller/Application\\Controller\\OffreFormation\\ElementPedagogique:supprimer',
                'visible' => false,
                'icon' => 'glyphicon glyphicon-remove',
                'withtarget' => true,
                'category' => 'element',
              ),
              'etape-ajouter' => 
              array (
                'label' => 'Créer une nouvelle formation',
                'title' => 'Créer une nouvelle formation',
                'route' => 'of/etape/ajouter',
                'resource' => 'controller/Application\\Controller\\OffreFormation\\Etape:ajouter',
                'visible' => false,
                'icon' => 'glyphicon glyphicon-plus',
                'category' => 'etape',
              ),
              'etape-modifier' => 
              array (
                'label' => 'Modifier cette formation',
                'title' => 'Modifier cette formation',
                'route' => 'of/etape/modifier',
                'resource' => 'controller/Application\\Controller\\OffreFormation\\Etape:modifier',
                'visible' => false,
                'icon' => 'glyphicon glyphicon-edit',
                'withtarget' => true,
                'category' => 'etape',
              ),
              'etape-supprimer' => 
              array (
                'label' => 'Supprimer cette formation',
                'title' => 'Supprimer cette formation',
                'route' => 'of/etape/supprimer',
                'resource' => 'controller/Application\\Controller\\OffreFormation\\Etape:supprimer',
                'visible' => false,
                'icon' => 'glyphicon glyphicon-remove',
                'withtarget' => true,
                'category' => 'etape',
              ),
              'etape-modulateurs' => 
              array (
                'label' => 'Editer les modulateurs liés à cette formation',
                'title' => 'Editer les modulateurs liés à cette formation',
                'route' => 'of/etape/modulateurs',
                'resource' => 'controller/Application\\Controller\\OffreFormation\\Modulateur:saisir',
                'visible' => false,
                'icon' => 'glyphicon glyphicon-list-alt',
                'withtarget' => true,
                'category' => 'modulateur',
              ),
              'etape-centres-couts' => 
              array (
                'label' => 'Paramétrer les centres de coûts',
                'title' => 'Paramétrer les centres de coûts liés à cette formation',
                'route' => 'of/etape/centres-couts',
                'resource' => 'controller/Application\\Controller\\OffreFormation\\EtapeCentreCout:saisir',
                'visible' => false,
                'icon' => 'glyphicon glyphicon-euro',
                'withtarget' => true,
                'category' => 'centres-couts',
              ),
            ),
          ),
          'gestion' => 
          array (
            'label' => 'Gestion',
            'route' => 'gestion',
            'resource' => 'controller/Application\\Controller\\Gestion:index',
            'pages' => 
            array (
              'droits' => 
              array (
                'label' => 'Droits d\'accès',
                'title' => 'Gestion des droits d\'accès',
                'route' => 'gestion/droits',
                'resource' => 'privilege/privilege-visualisation',
                'pages' => 
                array (
                  'roles' => 
                  array (
                    'label' => 'Rôles',
                    'title' => 'Gestion des rôles',
                    'route' => 'gestion/droits/roles',
                    'withtarget' => true,
                  ),
                  'privileges' => 
                  array (
                    'label' => 'Privilèges',
                    'title' => 'Gestion des privilèges',
                    'route' => 'gestion/droits/privileges',
                    'withtarget' => true,
                  ),
                ),
              ),
              'agrement' => 
              array (
                'label' => 'Agréments par lot',
                'title' => 'Gestion des agréments par lot',
                'route' => 'gestion/agrement',
                'resource' => 'controller/Application\\Controller\\Agrement:index',
                'pagesProvider' => 
                array (
                  'type' => 'AgrementNavigationPagesProvider',
                  'route' => 'gestion/agrement/ajouter-lot',
                  'withtarget' => true,
                  'resource' => 'controller/Application\\Controller\\Agrement:ajouter-lot',
                  'privilege' => 'ajouter-lot',
                ),
              ),
              'indicateurs' => 
              array (
                'label' => 'Indicateurs',
                'title' => 'Indicateurs',
                'route' => 'indicateur',
                'resource' => 'controller/Application\\Controller\\Indicateur:index',
              ),
              'etat-demande-paiement' => 
              array (
                'label' => 'Mises en paiement',
                'title' => 'Mises en paiement',
                'route' => 'paiement/etat-demande-paiement',
                'resource' => 'privilege/mise-en-paiement-visualisation',
              ),
              'etat-paiement' => 
              array (
                'label' => 'État de paiement',
                'title' => 'État de paiement',
                'route' => 'paiement/etat-paiement',
                'resource' => 'privilege/mise-en-paiement-visualisation',
              ),
              'mises-en-paiement-csv' => 
              array (
                'label' => 'Mises en paiement (CSV)',
                'title' => 'Extraction des mises en paiement et demandes de mises en paiement au format tableur (CSV)',
                'route' => 'paiement/mises-en-paiement-csv',
                'resource' => 'privilege/mise-en-paiement-export-csv',
              ),
              'extraction-winpaie' => 
              array (
                'label' => 'Extraction Winpaie',
                'title' => 'Export des données de paiement au format Winpaie',
                'route' => 'paiement/extraction-winpaie',
                'resource' => 'privilege/mise-en-paiement-export-paie',
              ),
            ),
          ),
          'structure' => 
          array (
            'label' => 'Structures',
            'title' => 'Gestion des structures',
            'route' => 'structure',
            'visible' => false,
            'params' => 
            array (
              'action' => 'index',
            ),
            'pages' => 
            array (
              'voir' => 
              array (
                'label' => 'Voir',
                'title' => 'Voir une structure',
                'route' => 'structure',
                'visible' => false,
                'withtarget' => true,
                'pages' => 
                array (
                ),
              ),
            ),
          ),
          'volume-horaire' => 
          array (
            'label' => 'Volumes horaires',
            'title' => 'Gestion des volumes horaires',
            'visible' => false,
            'route' => 'volume-horaire',
            'params' => 
            array (
              'action' => 'index',
            ),
            'pages' => 
            array (
              'consultation' => 
              array (
                'label' => 'Consultation',
                'title' => 'Consultation des volumes horaires',
                'route' => 'volume-horaire',
                'visible' => false,
                'withtarget' => true,
                'pages' => 
                array (
                ),
              ),
            ),
          ),
          'validation' => 
          array (
            'label' => 'Validation',
            'route' => 'validation/liste',
            'visible' => false,
            'resource' => 'controller/Application\\Controller\\Validation:liste',
            'pages' => 
            array (
              'voir' => 
              array (
                'label' => 'Détails',
                'title' => 'Détails d\'une validation',
                'route' => 'validation/voir',
                'withtarget' => true,
                'resource' => 'controller/Application\\Controller\\Validation:voir',
              ),
            ),
          ),
          'import' => 
          array (
            'label' => 'Import',
            'order' => 1,
            'route' => 'import',
            'resource' => 'controller/Import\\Controller\\Import:index',
            'pages' => 
            array (
              'admin' => 
              array (
                'label' => 'Tableau de bord principal',
                'route' => 'import',
                'resource' => 'controller/Import\\Controller\\Import:showimporttbl',
                'params' => 
                array (
                  'action' => 'showImportTbl',
                ),
                'visible' => true,
                'pages' => 
                array (
                ),
              ),
              'showDiff' => 
              array (
                'label' => 'Écarts entre OSE et ses sources',
                'route' => 'import',
                'resource' => 'controller/Import\\Controller\\Import:showdiff',
                'params' => 
                array (
                  'action' => 'showDiff',
                ),
                'visible' => true,
              ),
            ),
          ),
          'debug' => 
          array (
            'label' => 'Debug',
            'route' => 'debug',
            'params' => 
            array (
              'action' => 'index',
            ),
          ),
        ),
      ),
    ),
  ),
  'zfcuser' => 
  array (
    'enable_registration' => false,
    'auth_identity_fields' => 
    array (
      0 => 'username',
      1 => 'email',
    ),
    'login_redirect_route' => 'home',
    'logout_redirect_route' => 'home',
    'enable_username' => false,
    'enable_display_name' => true,
    'auth_adapters' => 
    array (
      300 => 'UnicaenAuth\\Authentication\\Adapter\\Ldap',
      200 => 'UnicaenAuth\\Authentication\\Adapter\\Db',
      100 => 'UnicaenAuth\\Authentication\\Adapter\\Cas',
    ),
    'user_entity_class' => 'Application\\Entity\\Db\\Utilisateur',
    'enable_default_entities' => false,
  ),
  'unicaen-auth' => 
  array (
    'identity_providers' => 
    array (
      300 => 'UnicaenAuth\\Provider\\Identity\\Basic',
      200 => 'UnicaenAuth\\Provider\\Identity\\Db',
      100 => 'UnicaenAuth\\Provider\\Identity\\Ldap',
      50 => 'ApplicationIdentityProvider',
    ),
    'save_ldap_user_in_database' => true,
    'enable_registration' => false,
    'cas' => 
    array (
    ),
    'usurpation_allowed_usernames' => 
    array (
      0 => 'gauthierb',
      1 => 'lecluse',
      2 => 'bernardb',
      3 => 'zebulon',
    ),
  ),
  'unicaen-ldap' => 
  array (
    'host' => 'ldap.unicaen.fr',
    'port' => 389,
    'version' => 3,
    'baseDn' => 'ou=people,dc=unicaen,dc=fr',
    'bindRequiresDn' => true,
    'username' => 'uid=applidev,ou=system,dc=unicaen,dc=fr',
    'password' => 'Ifq1pdeS2of_7DC',
  ),
  'controller_plugins' => 
  array (
    'invokables' => 
    array (
      'em' => 'Application\\Controller\\Plugin\\Em',
      'context' => 'Application\\Controller\\Plugin\\Context',
    ),
    'factories' => 
    array (
      'mail' => 'Application\\Controller\\Plugin\\MailWithLogPluginFactory',
    ),
  ),
  'public_files' => 
  array (
    'js' => 
    array (
      0 => 'js/elementPedagogiqueRecherche.js',
      1 => 'js/service.js',
      2 => 'js/service-referentiel.js',
      3 => 'js/paiement.js',
      4 => 'bootstrap-select/js/bootstrap-select.min.js',
      5 => 'js/gestion.js',
    ),
    'css' => 
    array (
      0 => 'bootstrap-select/css/bootstrap-select.min.css',
    ),
  ),
  'unicaen-app' => 
  array (
    'app_infos' => 
    array (
      'nom' => 'OSE',
      'desc' => 'Organisation des Services d\'Enseignement',
      'version' => '1.5.3',
      'date' => '10/06/2015',
      'contact' => 
      array (
        'mail' => 'Contactez votre composante.',
      ),
      'mentionsLegales' => 'http://www.unicaen.fr/outils-portail-institutionnel/mentions-legales/',
      'informatiqueEtLibertes' => 'http://www.unicaen.fr/outils-portail-institutionnel/informatique-et-libertes/',
    ),
    'session_refresh_period' => 600000,
    'ldap' => 
    array (
      'connection' => 
      array (
        'default' => 
        array (
          'params' => 
          array (
            'host' => 'ldap.unicaen.fr',
            'username' => 'uid=applidev,ou=system,dc=unicaen,dc=fr',
            'password' => 'Ifq1pdeS2of_7DC',
            'baseDn' => 'ou=people,dc=unicaen,dc=fr',
            'bindRequiresDn' => true,
            'accountFilterFormat' => '(&(objectClass=posixAccount)(supannAliasLogin=%s))',
          ),
        ),
      ),
    ),
    'mail' => 
    array (
      'transport_options' => 
      array (
        'host' => 'smtp.unicaen.fr',
        'port' => 25,
      ),
      'redirect_to' => 
      array (
        0 => 'bertrand.gauthier@unicaen.fr',
        1 => 'laurent.lecluse@unicaen.fr',
      ),
      'do_not_send' => false,
    ),
  ),
);