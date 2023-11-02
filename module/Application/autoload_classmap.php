<?php
/**
 * Generated by UnicaenCode
 * Commande : php public/index.php UnicaenCode  module='Application'
 */
return [
    'Application'                                                        => __DIR__ . '/Application.php',
    'Application\Acl\Role'                                               => __DIR__ . '/src/Acl/Role.php',
    'Application\Assertion\ChargensAssertion'                            => __DIR__ . '/src/Assertion/ChargensAssertion.php',
    'Application\Assertion\GestionAssertion'                             => __DIR__ . '/src/Assertion/GestionAssertion.php',
    'Application\Assertion\InformationAssertion'                         => __DIR__ . '/src/Assertion/InformationAssertion.php',
    'Application\Assertion\IntervenantAssertion'                         => __DIR__ . '/src/Assertion/IntervenantAssertion.php',
    'Application\Assertion\WorkflowAssertion'                            => __DIR__ . '/src/Assertion/WorkflowAssertion.php',
    'Application\Cache\CacheContainer'                                   => __DIR__ . '/src/Cache/CacheContainer.php',
    'Application\Cache\CacheService'                                     => __DIR__ . '/src/Cache/CacheService.php',
    'Application\Cache\Factory\CacheServiceFactory'                      => __DIR__ . '/src/Cache/Factory/CacheServiceFactory.php',
    'Application\ConfigFactory'                                          => __DIR__ . '/src/ConfigFactory.php',
    'Application\Connecteur\Factory\LdapConnecteurFactory'               => __DIR__ . '/src/Connecteur/Factory/LdapConnecteurFactory.php',
    'Application\Connecteur\LdapConnecteur'                              => __DIR__ . '/src/Connecteur/LdapConnecteur.php',
    'Application\Constants'                                              => __DIR__ . '/src/Constants.php',
    'Application\Controller\AbstractController'                          => __DIR__ . '/src/Controller/AbstractController.php',
    'Application\Controller\AdministrationController'                    => __DIR__ . '/src/Controller/AdministrationController.php',
    'Application\Controller\ChargensController'                          => __DIR__ . '/src/Controller/ChargensController.php',
    'Application\Controller\CorpsController'                             => __DIR__ . '/src/Controller/CorpsController.php',
    'Application\Controller\DomaineFonctionnelController'                => __DIR__ . '/src/Controller/DomaineFonctionnelController.php',
    'Application\Controller\DroitsController'                            => __DIR__ . '/src/Controller/DroitsController.php',
    'Application\Controller\EtatSortieController'                        => __DIR__ . '/src/Controller/EtatSortieController.php',
    'Application\Controller\Factory\AdministrationControllerFactory'     => __DIR__ . '/src/Controller/Factory/AdministrationControllerFactory.php',
    'Application\Controller\Factory\ChargensControllerFactory'           => __DIR__ . '/src/Controller/Factory/ChargensControllerFactory.php',
    'Application\Controller\Factory\CorpsControllerFactory'              => __DIR__ . '/src/Controller/Factory/CorpsControllerFactory.php',
    'Application\Controller\Factory\DroitsControllerFactory'             => __DIR__ . '/src/Controller/Factory/DroitsControllerFactory.php',
    'Application\Controller\Factory\EtatSortieControllerFactory'         => __DIR__ . '/src/Controller/Factory/EtatSortieControllerFactory.php',
    'Application\Controller\Factory\FormuleControllerFactory'            => __DIR__ . '/src/Controller/Factory/FormuleControllerFactory.php',
    'Application\Controller\Factory\IndexControllerFactory'              => __DIR__ . '/src/Controller/Factory/IndexControllerFactory.php',
    'Application\Controller\Factory\IntervenantControllerFactory'        => __DIR__ . '/src/Controller/Factory/IntervenantControllerFactory.php',
    'Application\Controller\Factory\PeriodeControllerFactory'            => __DIR__ . '/src/Controller/Factory/PeriodeControllerFactory.php',
    'Application\Controller\Factory\UtilisateurControllerFactory'        => __DIR__ . '/src/Controller/Factory/UtilisateurControllerFactory.php',
    'Application\Controller\Factory\WorkflowControllerFactory'           => __DIR__ . '/src/Controller/Factory/WorkflowControllerFactory.php',
    'Application\Controller\FormuleController'                           => __DIR__ . '/src/Controller/FormuleController.php',
    'Application\Controller\GestionController'                           => __DIR__ . '/src/Controller/GestionController.php',
    'Application\Controller\GradeController'                             => __DIR__ . '/src/Controller/GradeController.php',
    'Application\Controller\IndexController'                             => __DIR__ . '/src/Controller/IndexController.php',
    'Application\Controller\IntervenantController'                       => __DIR__ . '/src/Controller/IntervenantController.php',
    'Application\Controller\ParametreController'                         => __DIR__ . '/src/Controller/ParametreController.php',
    'Application\Controller\PeriodeController'                           => __DIR__ . '/src/Controller/PeriodeController.php',
    'Application\Controller\Plugin\Context'                              => __DIR__ . '/src/Controller/Plugin/Context.php',
    'Application\Controller\Plugin\ContextFactory'                       => __DIR__ . '/src/Controller/Plugin/ContextFactory.php',
    'Application\Controller\RechercheController'                         => __DIR__ . '/src/Controller/RechercheController.php',
    'Application\Controller\UtilisateurController'                       => __DIR__ . '/src/Controller/UtilisateurController.php',
    'Application\Controller\WorkflowController'                          => __DIR__ . '/src/Controller/WorkflowController.php',
    'Application\Entity\Chargens\Lien'                                   => __DIR__ . '/src/Entity/Chargens/Lien.php',
    'Application\Entity\Chargens\Noeud'                                  => __DIR__ . '/src/Entity/Chargens/Noeud.php',
    'Application\Entity\Chargens\ScenarioLien'                           => __DIR__ . '/src/Entity/Chargens/ScenarioLien.php',
    'Application\Entity\Chargens\ScenarioNoeud'                          => __DIR__ . '/src/Entity/Chargens/ScenarioNoeud.php',
    'Application\Entity\Chargens\ScenarioNoeudEffectif'                  => __DIR__ . '/src/Entity/Chargens/ScenarioNoeudEffectif.php',
    'Application\Entity\Chargens\ScenarioNoeudSeuil'                     => __DIR__ . '/src/Entity/Chargens/ScenarioNoeudSeuil.php',
    'Application\Entity\Collection'                                      => __DIR__ . '/src/Entity/Collection.php',
    'Application\Entity\Db\Affectation'                                  => __DIR__ . '/src/Entity/Db/Affectation.php',
    'Application\Entity\Db\AffectationRecherche'                         => __DIR__ . '/src/Entity/Db/AffectationRecherche.php',
    'Application\Entity\Db\Annee'                                        => __DIR__ . '/src/Entity/Db/Annee.php',
    'Application\Entity\Db\CategoriePrivilege'                           => __DIR__ . '/src/Entity/Db/CategoriePrivilege.php',
    'Application\Entity\Db\Civilite'                                     => __DIR__ . '/src/Entity/Db/Civilite.php',
    'Application\Entity\Db\Corps'                                        => __DIR__ . '/src/Entity/Db/Corps.php',
    'Application\Entity\Db\DomaineFonctionnel'                           => __DIR__ . '/src/Entity/Db/DomaineFonctionnel.php',
    'Application\Entity\Db\EtatSortie'                                   => __DIR__ . '/src/Entity/Db/EtatSortie.php',
    'Application\Entity\Db\Fichier'                                      => __DIR__ . '/src/Entity/Db/Fichier.php',
    'Application\Entity\Db\Formule'                                      => __DIR__ . '/src/Entity/Db/Formule.php',
    'Application\Entity\Db\FormuleResultat'                              => __DIR__ . '/src/Entity/Db/FormuleResultat.php',
    'Application\Entity\Db\FormuleResultatService'                       => __DIR__ . '/src/Entity/Db/FormuleResultatService.php',
    'Application\Entity\Db\FormuleResultatServiceReferentiel'            => __DIR__ . '/src/Entity/Db/FormuleResultatServiceReferentiel.php',
    'Application\Entity\Db\FormuleResultatVolumeHoraire'                 => __DIR__ . '/src/Entity/Db/FormuleResultatVolumeHoraire.php',
    'Application\Entity\Db\FormuleResultatVolumeHoraireReferentiel'      => __DIR__ . '/src/Entity/Db/FormuleResultatVolumeHoraireReferentiel.php',
    'Application\Entity\Db\FormuleTestIntervenant'                       => __DIR__ . '/src/Entity/Db/FormuleTestIntervenant.php',
    'Application\Entity\Db\FormuleTestVolumeHoraire'                     => __DIR__ . '/src/Entity/Db/FormuleTestVolumeHoraire.php',
    'Application\Entity\Db\Grade'                                        => __DIR__ . '/src/Entity/Db/Grade.php',
    'Application\Entity\Db\Intervenant'                                  => __DIR__ . '/src/Entity/Db/Intervenant.php',
    'Application\Entity\Db\Parametre'                                    => __DIR__ . '/src/Entity/Db/Parametre.php',
    'Application\Entity\Db\Perimetre'                                    => __DIR__ . '/src/Entity/Db/Perimetre.php',
    'Application\Entity\Db\Periode'                                      => __DIR__ . '/src/Entity/Db/Periode.php',
    'Application\Entity\Db\Privilege'                                    => __DIR__ . '/src/Entity/Db/Privilege.php',
    'Application\Entity\Db\Role'                                         => __DIR__ . '/src/Entity/Db/Role.php',
    'Application\Entity\Db\Scenario'                                     => __DIR__ . '/src/Entity/Db/Scenario.php',
    'Application\Entity\Db\SeuilCharge'                                  => __DIR__ . '/src/Entity/Db/SeuilCharge.php',
    'Application\Entity\Db\TblContrat'                                   => __DIR__ . '/src/Entity/Db/TblContrat.php',
    'Application\Entity\Db\TblWorkflow'                                  => __DIR__ . '/src/Entity/Db/TblWorkflow.php',
    'Application\Entity\Db\TypePoste'                                    => __DIR__ . '/src/Entity/Db/TypePoste.php',
    'Application\Entity\Db\TypeValidation'                               => __DIR__ . '/src/Entity/Db/TypeValidation.php',
    'Application\Entity\Db\Utilisateur'                                  => __DIR__ . '/src/Entity/Db/Utilisateur.php',
    'Application\Entity\Db\Validation'                                   => __DIR__ . '/src/Entity/Db/Validation.php',
    'Application\Entity\Db\WfDepBloquante'                               => __DIR__ . '/src/Entity/Db/WfDepBloquante.php',
    'Application\Entity\Db\WfEtape'                                      => __DIR__ . '/src/Entity/Db/WfEtape.php',
    'Application\Entity\Db\WfEtapeDep'                                   => __DIR__ . '/src/Entity/Db/WfEtapeDep.php',
    'Application\Entity\NiveauEtape'                                     => __DIR__ . '/src/Entity/NiveauEtape.php',
    'Application\Entity\WorkflowEtape'                                   => __DIR__ . '/src/Entity/WorkflowEtape.php',
    'Application\Filter\DateTimeFromString'                              => __DIR__ . '/src/Filter/DateTimeFromString.php',
    'Application\Filter\FloatFromString'                                 => __DIR__ . '/src/Filter/FloatFromString.php',
    'Application\Filter\StringFromFloat'                                 => __DIR__ . '/src/Filter/StringFromFloat.php',
    'Application\Form\AbstractFieldset'                                  => __DIR__ . '/src/Form/AbstractFieldset.php',
    'Application\Form\AbstractForm'                                      => __DIR__ . '/src/Form/AbstractForm.php',
    'Application\Form\Chargens\DifferentielForm'                         => __DIR__ . '/src/Form/Chargens/DifferentielForm.php',
    'Application\Form\Chargens\DuplicationScenarioForm'                  => __DIR__ . '/src/Form/Chargens/DuplicationScenarioForm.php',
    'Application\Form\Chargens\FiltreForm'                               => __DIR__ . '/src/Form/Chargens/FiltreForm.php',
    'Application\Form\Chargens\ScenarioFiltreForm'                       => __DIR__ . '/src/Form/Chargens/ScenarioFiltreForm.php',
    'Application\Form\Chargens\ScenarioForm'                             => __DIR__ . '/src/Form/Chargens/ScenarioForm.php',
    'Application\Form\Corps\CorpsSaisieForm'                             => __DIR__ . '/src/Form/Corps/CorpsSaisieForm.php',
    'Application\Form\Corps\CorpsSaisieFormFactory'                      => __DIR__ . '/src/Form/Corps/CorpsSaisieFormFactory.php',
    'Application\Form\DomaineFonctionnel\DomaineFonctionnelSaisieForm'   => __DIR__ . '/src/Form/DomaineFonctionnel/DomaineFonctionnelSaisieForm.php',
    'Application\Form\Droits\AffectationForm'                            => __DIR__ . '/src/Form/Droits/AffectationForm.php',
    'Application\Form\Droits\RoleForm'                                   => __DIR__ . '/src/Form/Droits/RoleForm.php',
    'Application\Form\Droits\RoleFormFactory'                            => __DIR__ . '/src/Form/Droits/RoleFormFactory.php',
    'Application\Form\EtatSortieForm'                                    => __DIR__ . '/src/Form/EtatSortieForm.php',
    'Application\Form\Factory\EtatSortieFormFactory'                     => __DIR__ . '/src/Form/Factory/EtatSortieFormFactory.php',
    'Application\Form\Grade\GradeSaisieForm'                             => __DIR__ . '/src/Form/Grade/GradeSaisieForm.php',
    'Application\Form\Intervenant\EditionForm'                           => __DIR__ . '/src/Form/Intervenant/EditionForm.php',
    'Application\Form\Intervenant\Factory\EditionFormFactory'            => __DIR__ . '/src/Form/Intervenant/Factory/EditionFormFactory.php',
    'Application\Form\Intervenant\HeuresCompForm'                        => __DIR__ . '/src/Form/Intervenant/HeuresCompForm.php',
    'Application\Form\ParametresForm'                                    => __DIR__ . '/src/Form/ParametresForm.php',
    'Application\Form\Periode\PeriodeSaisieForm'                         => __DIR__ . '/src/Form/Periode/PeriodeSaisieForm.php',
    'Application\Form\Periode\PeriodeSaisieFormFactory'                  => __DIR__ . '/src/Form/Periode/PeriodeSaisieFormFactory.php',
    'Application\Form\Supprimer'                                         => __DIR__ . '/src/Form/Supprimer.php',
    'Application\Form\View\Helper\FormSearchAndSelect'                   => __DIR__ . '/src/Form/View/Helper/FormSearchAndSelect.php',
    'Application\Form\Workflow\DependanceForm'                           => __DIR__ . '/src/Form/Workflow/DependanceForm.php',
    'Application\HostLocalization\HostLocalizationOse'                   => __DIR__ . '/src/HostLocalization/HostLocalizationOse.php',
    'Application\HostLocalization\HostLocalizationOseFactory'            => __DIR__ . '/src/HostLocalization/HostLocalizationOseFactory.php',
    'Application\Hydrator\Chargens\LienDbHydrator'                       => __DIR__ . '/src/Hydrator/Chargens/LienDbHydrator.php',
    'Application\Hydrator\Chargens\LienDiagrammeHydrator'                => __DIR__ . '/src/Hydrator/Chargens/LienDiagrammeHydrator.php',
    'Application\Hydrator\Chargens\NoeudDbHydrator'                      => __DIR__ . '/src/Hydrator/Chargens/NoeudDbHydrator.php',
    'Application\Hydrator\Chargens\NoeudDiagrammeHydrator'               => __DIR__ . '/src/Hydrator/Chargens/NoeudDiagrammeHydrator.php',
    'Application\Hydrator\Chargens\ScenarioLienDbHydrator'               => __DIR__ . '/src/Hydrator/Chargens/ScenarioLienDbHydrator.php',
    'Application\Hydrator\Chargens\ScenarioNoeudDbHydrator'              => __DIR__ . '/src/Hydrator/Chargens/ScenarioNoeudDbHydrator.php',
    'Application\Hydrator\Chargens\ScenarioNoeudEffectifDbHydrator'      => __DIR__ . '/src/Hydrator/Chargens/ScenarioNoeudEffectifDbHydrator.php',
    'Application\Hydrator\Chargens\ScenarioNoeudSeuilDbHydrator'         => __DIR__ . '/src/Hydrator/Chargens/ScenarioNoeudSeuilDbHydrator.php',
    'Application\Hydrator\FormuleTestIntervenantHydrator'                => __DIR__ . '/src/Hydrator/FormuleTestIntervenantHydrator.php',
    'Application\Hydrator\GenericHydrator'                               => __DIR__ . '/src/Hydrator/GenericHydrator.php',
    'Application\Model\FormuleCalcul'                                    => __DIR__ . '/src/Model/FormuleCalcul.php',
    'Application\Model\TreeNode'                                         => __DIR__ . '/src/Model/TreeNode.php',
    'Application\Module'                                                 => __DIR__ . '/Module.php',
    'Application\Mouchard\MouchardCompleterContext'                      => __DIR__ . '/src/Mouchard/MouchardCompleterContext.php',
    'Application\Mouchard\MouchardCompleterContextFactory'               => __DIR__ . '/src/Mouchard/MouchardCompleterContextFactory.php',
    'Application\Navigation\NavigationFactory'                           => __DIR__ . '/src/Navigation/NavigationFactory.php',
    'Application\ORM\Event\Listeners\EntityManagerListener'              => __DIR__ . '/src/ORM/Event/Listeners/EntityManagerListener.php',
    'Application\ORM\Event\Listeners\HistoriqueListener'                 => __DIR__ . '/src/ORM/Event/Listeners/HistoriqueListener.php',
    'Application\ORM\Event\Listeners\HistoriqueListenerFactory'          => __DIR__ . '/src/ORM/Event/Listeners/HistoriqueListenerFactory.php',
    'Application\ORM\Event\Listeners\ParametreEntityListener'            => __DIR__ . '/src/ORM/Event/Listeners/ParametreEntityListener.php',
    'Application\ORM\Filter\AbstractFilter'                              => __DIR__ . '/src/ORM/Filter/AbstractFilter.php',
    'Application\ORM\Filter\AnneeFilter'                                 => __DIR__ . '/src/ORM/Filter/AnneeFilter.php',
    'Application\ORM\Filter\HistoriqueFilter'                            => __DIR__ . '/src/ORM/Filter/HistoriqueFilter.php',
    'Application\ORM\Query\Functions\Convert'                            => __DIR__ . '/src/ORM/Query/Functions/Convert.php',
    'Application\ORM\RouteEntitiesInjector'                              => __DIR__ . '/src/ORM/RouteEntitiesInjector.php',
    'Application\ORM\RouteEntitiesInjectorFactory'                       => __DIR__ . '/src/ORM/RouteEntitiesInjectorFactory.php',
    'Application\Processus\AbstractProcessus'                            => __DIR__ . '/src/Processus/AbstractProcessus.php',
    'Application\Processus\Factory\IntervenantProcessusFactory'          => __DIR__ . '/src/Processus/Factory/IntervenantProcessusFactory.php',
    'Application\Processus\IntervenantProcessus'                         => __DIR__ . '/src/Processus/IntervenantProcessus.php',
    'Application\Processus\Intervenant\RechercheProcessus'               => __DIR__ . '/src/Processus/Intervenant/RechercheProcessus.php',
    'Application\Processus\Intervenant\SuppressionProcessus'             => __DIR__ . '/src/Processus/Intervenant/SuppressionProcessus.php',
    'Application\Provider\Chargens\ChargensProvider'                     => __DIR__ . '/src/Provider/Chargens/ChargensProvider.php',
    'Application\Provider\Chargens\ChargensProviderFactory'              => __DIR__ . '/src/Provider/Chargens/ChargensProviderFactory.php',
    'Application\Provider\Chargens\EntityProvider'                       => __DIR__ . '/src/Provider/Chargens/EntityProvider.php',
    'Application\Provider\Chargens\ExportProvider'                       => __DIR__ . '/src/Provider/Chargens/ExportProvider.php',
    'Application\Provider\Chargens\LienProvider'                         => __DIR__ . '/src/Provider/Chargens/LienProvider.php',
    'Application\Provider\Chargens\NoeudProvider'                        => __DIR__ . '/src/Provider/Chargens/NoeudProvider.php',
    'Application\Provider\Chargens\ScenarioLienProvider'                 => __DIR__ . '/src/Provider/Chargens/ScenarioLienProvider.php',
    'Application\Provider\Chargens\ScenarioNoeudProvider'                => __DIR__ . '/src/Provider/Chargens/ScenarioNoeudProvider.php',
    'Application\Provider\Identity\IdentityProvider'                     => __DIR__ . '/src/Provider/Identity/IdentityProvider.php',
    'Application\Provider\Identity\IdentityProviderFactory'              => __DIR__ . '/src/Provider/Identity/IdentityProviderFactory.php',
    'Application\Provider\Privilege\Privileges'                          => __DIR__ . '/src/Provider/Privilege/Privileges.php',
    'Application\Provider\Resource\ResourceProvider'                     => __DIR__ . '/src/Provider/Resource/ResourceProvider.php',
    'Application\Provider\Resource\ResourceProviderFactory'              => __DIR__ . '/src/Provider/Resource/ResourceProviderFactory.php',
    'Application\Provider\Role\RoleProvider'                             => __DIR__ . '/src/Provider/Role/RoleProvider.php',
    'Application\Provider\Role\RoleProviderFactory'                      => __DIR__ . '/src/Provider/Role/RoleProviderFactory.php',
    'Application\Resource\WorkflowResource'                              => __DIR__ . '/src/Resource/WorkflowResource.php',
    'Application\Service\AbstractEntityService'                          => __DIR__ . '/src/Service/AbstractEntityService.php',
    'Application\Service\AbstractService'                                => __DIR__ . '/src/Service/AbstractService.php',
    'Application\Service\AffectationService'                             => __DIR__ . '/src/Service/AffectationService.php',
    'Application\Service\AnneeService'                                   => __DIR__ . '/src/Service/AnneeService.php',
    'Application\Service\CiviliteService'                                => __DIR__ . '/src/Service/CiviliteService.php',
    'Application\Service\ContextService'                                 => __DIR__ . '/src/Service/ContextService.php',
    'Application\Service\CorpsService'                                   => __DIR__ . '/src/Service/CorpsService.php',
    'Application\Service\EtatSortieService'                              => __DIR__ . '/src/Service/EtatSortieService.php',
    'Application\Service\Factory\ContextServiceFactory'                  => __DIR__ . '/src/Service/Factory/ContextServiceFactory.php',
    'Application\Service\Factory\EtatSortieServiceFactory'               => __DIR__ . '/src/Service/Factory/EtatSortieServiceFactory.php',
    'Application\Service\Factory\FormuleServiceFactory'                  => __DIR__ . '/src/Service/Factory/FormuleServiceFactory.php',
    'Application\Service\Factory\FormuleTestIntervenantServiceFactory'   => __DIR__ . '/src/Service/Factory/FormuleTestIntervenantServiceFactory.php',
    'Application\Service\Factory\IntervenantServiceFactory'              => __DIR__ . '/src/Service/Factory/IntervenantServiceFactory.php',
    'Application\Service\Factory\PrivilegeServiceFactory'                => __DIR__ . '/src/Service/Factory/PrivilegeServiceFactory.php',
    'Application\Service\Factory\SeuilChargeServiceFactory'              => __DIR__ . '/src/Service/Factory/SeuilChargeServiceFactory.php',
    'Application\Service\Factory\UtilisateurServiceFactory'              => __DIR__ . '/src/Service/Factory/UtilisateurServiceFactory.php',
    'Application\Service\Factory\WorkflowServiceFactory'                 => __DIR__ . '/src/Service/Factory/WorkflowServiceFactory.php',
    'Application\Service\FichierService'                                 => __DIR__ . '/src/Service/FichierService.php',
    'Application\Service\FormuleResultatService'                         => __DIR__ . '/src/Service/FormuleResultatService.php',
    'Application\Service\FormuleResultatServiceReferentielService'       => __DIR__ . '/src/Service/FormuleResultatServiceReferentielService.php',
    'Application\Service\FormuleResultatServiceService'                  => __DIR__ . '/src/Service/FormuleResultatServiceService.php',
    'Application\Service\FormuleResultatVolumeHoraireReferentielService' => __DIR__ . '/src/Service/FormuleResultatVolumeHoraireReferentielService.php',
    'Application\Service\FormuleResultatVolumeHoraireService'            => __DIR__ . '/src/Service/FormuleResultatVolumeHoraireService.php',
    'Application\Service\FormuleService'                                 => __DIR__ . '/src/Service/FormuleService.php',
    'Application\Service\FormuleTestIntervenantService'                  => __DIR__ . '/src/Service/FormuleTestIntervenantService.php',
    'Application\Service\GradeService'                                   => __DIR__ . '/src/Service/GradeService.php',
    'Application\Service\IntervenantService'                             => __DIR__ . '/src/Service/IntervenantService.php',
    'Application\Service\LocalContextService'                            => __DIR__ . '/src/Service/LocalContextService.php',
    'Application\Service\ParametresService'                              => __DIR__ . '/src/Service/ParametresService.php',
    'Application\Service\PerimetreService'                               => __DIR__ . '/src/Service/PerimetreService.php',
    'Application\Service\PeriodeService'                                 => __DIR__ . '/src/Service/PeriodeService.php',
    'Application\Service\PrivilegeService'                               => __DIR__ . '/src/Service/PrivilegeService.php',
    'Application\Service\RoleService'                                    => __DIR__ . '/src/Service/RoleService.php',
    'Application\Service\ScenarioService'                                => __DIR__ . '/src/Service/ScenarioService.php',
    'Application\Service\SeuilChargeService'                             => __DIR__ . '/src/Service/SeuilChargeService.php',
    'Application\Service\SourceService'                                  => __DIR__ . '/src/Service/SourceService.php',
    'Application\Service\TypeValidationService'                          => __DIR__ . '/src/Service/TypeValidationService.php',
    'Application\Service\UtilisateurService'                             => __DIR__ . '/src/Service/UtilisateurService.php',
    'Application\Service\ValidationService'                              => __DIR__ . '/src/Service/ValidationService.php',
    'Application\Service\WfEtapeDepService'                              => __DIR__ . '/src/Service/WfEtapeDepService.php',
    'Application\Service\WfEtapeService'                                 => __DIR__ . '/src/Service/WfEtapeService.php',
    'Application\Service\WorkflowService'                                => __DIR__ . '/src/Service/WorkflowService.php',
    'Application\Util'                                                   => __DIR__ . '/src/Util.php',
    'Application\View\Helper\AppLink'                                    => __DIR__ . '/src/View/Helper/AppLink.php',
    'Application\View\Helper\AppLinkFactory'                             => __DIR__ . '/src/View/Helper/AppLinkFactory.php',
    'Application\View\Helper\CartridgeViewHelper'                        => __DIR__ . '/src/View/Helper/CartridgeViewHelper.php',
    'Application\View\Helper\Chargens\ChargensViewHelper'                => __DIR__ . '/src/View/Helper/Chargens/ChargensViewHelper.php',
    'Application\View\Helper\FormButtonGroupViewHelper'                  => __DIR__ . '/src/View/Helper/FormButtonGroupViewHelper.php',
    'Application\View\Helper\FormSupprimerViewHelper'                    => __DIR__ . '/src/View/Helper/FormSupprimerViewHelper.php',
    'Application\View\Helper\Import\CheminPedagogiqueViewHelper'         => __DIR__ . '/src/View/Helper/Import/CheminPedagogiqueViewHelper.php',
    'Application\View\Helper\Import\ElementPedagogiqueViewHelper'        => __DIR__ . '/src/View/Helper/Import/ElementPedagogiqueViewHelper.php',
    'Application\View\Helper\Import\EtapeViewHelper'                     => __DIR__ . '/src/View/Helper/Import/EtapeViewHelper.php',
    'Application\View\Helper\Import\IntervenantViewHelper'               => __DIR__ . '/src/View/Helper/Import/IntervenantViewHelper.php',
    'Application\View\Helper\Intervenant\FeuilleDeRouteViewHelper'       => __DIR__ . '/src/View/Helper/Intervenant/FeuilleDeRouteViewHelper.php',
    'Application\View\Helper\Intervenant\IntervenantViewHelper'          => __DIR__ . '/src/View/Helper/Intervenant/IntervenantViewHelper.php',
    'Application\View\Helper\Intervenant\TotauxHetdViewHelper'           => __DIR__ . '/src/View/Helper/Intervenant/TotauxHetdViewHelper.php',
    'Application\View\Helper\TabViewHelper'                              => __DIR__ . '/src/View/Helper/TabViewHelper.php',
    'Application\View\Helper\TabViewHelperFactory'                       => __DIR__ . '/src/View/Helper/TabViewHelperFactory.php',
    'Application\View\Helper\TreeViewHelper'                             => __DIR__ . '/src/View/Helper/TreeViewHelper.php',
    'Application\View\Helper\UserProfileSelectRadioItem'                 => __DIR__ . '/src/View/Helper/UserProfileSelectRadioItem.php',
    'Application\View\Helper\UserProfileSelectRadioItemFactory'          => __DIR__ . '/src/View/Helper/UserProfileSelectRadioItemFactory.php',
    'Application\View\Helper\UtilisateurViewHelper'                      => __DIR__ . '/src/View/Helper/UtilisateurViewHelper.php',
    'Application\View\Renderer\PhpRenderer'                              => __DIR__ . '/src/View/Renderer/PhpRenderer.php',
];