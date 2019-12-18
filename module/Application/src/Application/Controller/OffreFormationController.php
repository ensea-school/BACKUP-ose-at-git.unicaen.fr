<?php

namespace Application\Controller;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\Db\GroupeTypeFormation;
use Application\Entity\Db\TypeFormation;
use Application\Entity\Db\TypeModulateur;
use Application\Processus\Traits\ReconductionProcessusAwareTrait;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Application\Service\Traits\EtapeServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\NiveauEtapeServiceAwareTrait;
use Application\Service\Traits\OffreFormationServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;


/**
 * Description of OffreFormationController
 *
 *
 */
class OffreFormationController extends AbstractController
{
    use ContextServiceAwareTrait;
    use LocalContextServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;
    use EtapeServiceAwareTrait;
    use NiveauEtapeServiceAwareTrait;
    use AnneeServiceAwareTrait;
    use ReconductionProcessusAwareTrait;
    use OffreFormationServiceAwareTrait;

    /**
     * @var Container
     */
    protected $sessionContainer;



    public function indexAction()
    {
        $this->initFilters();


        list($structure, $niveau, $etape) = $this->getParams();

        // persiste les filtres dans le contexte local
        $this->getServiceLocalContext()
            ->setStructure($structure)
            ->setNiveau($niveau)
            ->setEtape($etape);

        $structures = $this->getServiceStructure()->getList($this->getServiceStructure()->finderByEnseignement());
        list($niveaux, $etapes, $elements) = $this->getServiceOffreFormation()->getNeep($structure, $niveau, $etape);

        $params = [];
        if ($structure) $params['structure'] = $structure->getId();
        if ($niveau) $params['niveau'] = $niveau->getId();
        if ($etape) $params['etape'] = $etape->getId();


        // élément pédagogique sélectionné dans le champ de recherche
        if (($element = $this->params()->fromPost('element')) && isset($element['id'])) {
            $form->get('element')->setValue($element);
        }

        return [
            'structures'     => $structures,
            'niveaux'        => $niveaux,
            'etapes'         => $etapes,
            'elements'       => $elements,
            'structure'      => $structure,
            'niveau'         => $niveau,
            'etape'          => $etape,
            'serviceEtape'   => $this->getServiceEtape(), // pour déterminer les droits
            'serviceElement' => $this->getServiceElementPedagogique(), // pour déterminer les droits
        ];
    }



    public function exportAction()
    {
        $this->initFilters();

        list($structure, $niveau, $etape) = $this->getParams();

        $elements = $this->getServiceOffreFormation()->getNeep($structure, $niveau, $etape)[2];
        /* @var $elements ElementPedagogique[] */

        $csvModel = new CsvModel();
        $csvModel->setHeader([
            'Code formation',
            'Libellé formation',
            'Niveau',
            'Code enseignement',
            'Libellé enseignement',
            'Code discipline',
            'Libellé discipline',
            'Période',
            'FOAD',
            'Taux FI / effectifs année préc.',
            'Taux FA / effectifs année préc.',
            'Taux FC / effectifs année préc.',
            'Effectifs FI actuels',
            'Effectifs FA actuels',
            'Effectifs FC actuels',
        ]);
        foreach ($elements as $element) {
            $etape      = $element->getEtape();
            $effectifs  = $element->getEffectifs();
            $discipline = $element->getDiscipline();
            $csvModel->addLine([
                $etape->getSourceCode(),
                $etape->getLibelle(),
                $etape->getNiveauToString(),
                $element->getSourceCode(),
                $element->getLibelle(),
                $discipline ? $discipline->getSourceCode() : null,
                $discipline ? $discipline->getLibelleLong() : null,
                $element->getPeriode(),
                $element->getTauxFoad(),
                $element->getTauxFi(),
                $element->getTauxFa(),
                $element->getTauxFc(),
                $effectifs ? $effectifs->getFi() : null,
                $effectifs ? $effectifs->getFa() : null,
                $effectifs ? $effectifs->getFc() : null,
            ]);
        }
        $csvModel->setFilename('offre-de-formation.csv');

        return $csvModel;
    }



    public function administrationOffreAction()
    {
        return [];
    }



    public function reconductionAction()
    {
        $this->initFilterHistorique();
        list($structure, $niveau, $etape) = $this->getParams();
        //Get role of user
        $role         = $this->getServiceContext()->getSelectedIdentityRole();
        $structures   = $this->getServiceStructure()->getList($this->getServiceStructure()->finderByRole($role));
        $anneeEnCours = $this->getServiceContext()->getAnnee();
        list($offresComplementaires, $mappingEtape, $reconductionTotale) = $this->getServiceOffreFormation()->getOffreComplementaire($structure, $niveau, $etape);

        $reconductionStep = '';
        $messageStep      = '';
        $fromPost         = false;


        $request = $this->getRequest();
        if ($request->isPost()) {
            $datas    = $request->getPost();
            $fromPost = true;
            //Ajout du mapping des EtapesN et EtapesN1 pour pouvoir reconduire un element pédagogique sur une etape déjà reconduite.
            $datas['mappingEtape'] = $mappingEtape;
            $reconductionProcessus = $this->getProcessusReconduction();
            try {
                if ($reconductionProcessus->reconduction($datas)) {
                    $this->flashMessenger()->addSuccessMessage("Les éléments ont bien été reconduits pour l'année universitaire prochaine.");
                } else {
                    $this->flashMessenger()->addErrorMessage("Les éléments n'ont pas pu être reconduits. Merci de contacter le support.");
                }
            } catch (\Exception $e) {
                $reconductionStep = false;
                $messageStep      = $e->getMessage();
                echo $e->getMessage();
            }
        }


        //Chargement JS nécessaire uniquement sur cette page
        $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
        $headScript        = $viewHelperManager->get('headScript');
        $headScript->offsetSetFile(100, '/js/reconduction-offre.js');


        return [
            'fromPost'              => $fromPost,
            'offresComplementaires' => $offresComplementaires,
            'reconductionTotale'    => $reconductionTotale,
            'structure'             => $structure,
            'structures'            => $structures,
            'anneeEnCours'          => $anneeEnCours,
            'reconductionStep'      => $reconductionStep,
            'messageStep'           => $messageStep,
        ];
    }



    public function reconductionCentreCoutAction()
    {
        $this->initFilterHistorique();
        $anneeN            = $this->getServiceContext()->getAnnee();
        $anneeN1           = $this->getServiceContext()->getAnneeSuivante();
        $fromPost          = false;
        $etapesReconduites = [];
        list($structure, $niveau, $etape) = $this->getParams();

        //Get role of user
        $role       = $this->getServiceContext()->getSelectedIdentityRole();
        $structures = $this->getServiceStructure()->getList($this->getServiceStructure()->finderByRole($role));


        //Récupération de toutes les étapes éligibles à la reconduction des coûts
        if (!empty($structure)) {
            $etapesReconduitesResult = $this->getServiceEtape()->getEtapeReconduit($structure);
            if ($etapesReconduitesResult) {
                foreach ($etapesReconduitesResult as $etape) {
                    if ($etape->getAnnee()->getLibelle() == $this->getServiceContext()->getAnnee()->getLibelle()) {
                        $etapesReconduites[$etape->getCode()]['N']['etape']    = $etape;
                        $etapesReconduites[$etape->getCode()]['N']['epWithCc'] = $this->getServiceElementPedagogique()->countEpWithCc($etape);
                    } else {
                        $etapesReconduites[$etape->getCode()]['N1']['etape']    = $etape;
                        $etapesReconduites[$etape->getCode()]['N1']['epWithCc'] = $this->getServiceElementPedagogique()->countEpWithCc($etape);
                    }
                }
            }
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $fromPost = true;
            $datas    = $request->getPost();
            //Reconduire les centres de coût des EP de l'étape.
            try {
                $etapesReconduitesCc = [];

                foreach (current($datas) as $code) {

                    if (array_key_exists($code, $etapesReconduites)) {
                        $etapesReconduitesCc[$code] = $etapesReconduites[$code];
                    }
                }
                $result = $this->getProcessusReconduction()->reconduireCCFormation($etapesReconduitesCc);
                if ($result > 0) {
                    $this->flashMessenger()->addSuccessMessage("Les centres de coût ont bien été reconduit sur le $result 
                élément(s) pédagogique(s) existant pour la prochaine année universitaire");
                } else {
                    $this->flashMessenger()->addWarningMessage("Aucun centre de cout n'a été reconduit car aucun élément
                pédagogique n'existe pour cette formation pour la prochaine année universitaire.");
                }
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
            $fromPost = true;
        }


        return [
            'anneeN'            => $anneeN,
            'anneeN1'           => $anneeN1,
            'structures'        => $structures,
            'structure'         => $structure,
            'etapesReconduites' => $etapesReconduites,
            'fromPost'          => $fromPost,
        ];
    }



    public function reconductionModulateurAction()
    {
        $this->initFilterHistorique();
        $anneeN  = $this->getServiceContext()->getAnnee();
        $anneeN1 = $this->getServiceContext()->getAnneeSuivante();

        list($structure, $niveau, $etape) = $this->getParams();
        $etapesReconduites = [];
        $fromPost          = 0;
        $role              = $this->getServiceContext()->getSelectedIdentityRole();
        $structures        = $this->getServiceStructure()->getList($this->getServiceStructure()->finderByRole($role));

        //Récupération de toutes les étapes éligibles à la reconduction des coûts
        if (!empty($structure)) {
            $etapesReconduitesResult = $this->getServiceEtape()->getEtapeReconduit($structure);
            if ($etapesReconduitesResult) {
                foreach ($etapesReconduitesResult as $etape) {
                    if ($etape->getAnnee()->getLibelle() == $this->getServiceContext()->getAnnee()->getLibelle()) {
                        $etapesReconduites[$etape->getCode()]['N']['etape']            = $etape;
                        $etapesReconduites[$etape->getCode()]['N']['epWithModulateur'] = $this->getServiceElementPedagogique()->countEpWithModulateur($etape);
                    } else {
                        $etapesReconduites[$etape->getCode()]['N1']['etape']            = $etape;
                        $etapesReconduites[$etape->getCode()]['N1']['epWithModulateur'] = $this->getServiceElementPedagogique()->countEpWithModulateur($etape);
                    }
                }
            }
        }


        $request = $this->getRequest();
        if ($request->isPost()) {
            $fromPost = true;
            $datas    = $request->getPost();
            //Reconduire les modulateurs des EP de l'étape.
            try {
                $etapesReconduitesCc = [];

                foreach (current($datas) as $code) {

                    if (array_key_exists($code, $etapesReconduites)) {
                        $etapesReconduitesCc[$code] = $etapesReconduites[$code];
                    }
                }
                $result = $this->getProcessusReconduction()->reconduireModulateurFormation($etapesReconduitesCc);
                if ($result > 0) {
                    $this->flashMessenger()->addSuccessMessage("Les modulateurs ont bien été reconduit sur le $result 
                élément(s) pédagogique(s) existant pour la prochaine année universitaire");
                } else {
                    $this->flashMessenger()->addWarningMessage("Aucun modulateur n'a été reconduit car aucun élément
                pédagogique n'existe pour cette formation pour la prochaine année universitaire.");
                }
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
            $fromPost = true;
        }

        return [
            'anneeN'            => $anneeN,
            'anneeN1'           => $anneeN1,
            'structures'        => $structures,
            'etapesReconduites' => $etapesReconduites,
            'structure'         => $structure,
            'fromPost'          => $fromPost,
        ];
    }



    protected function initFilters()
    {
        $this->initFilterAnnee();
        $this->initFilterHistorique();
    }



    protected function initFilterAnnee()
    {
        $this->em()->getFilters()->enable('annee')->init([
            ElementPedagogique::class,
            Etape::class,
        ]);
    }



    protected function initFilterHistorique()
    {
        /* Mise en place des filtres */
        $this->em()->getFilters()->enable('historique')->init([
            ElementPedagogique::class,
            TypeFormation::class,
            GroupeTypeFormation::class,
            TypeModulateur::class,
        ]);
    }



    protected function disableFilters($name)
    {
        $this->em()->getFilters()->disable($name);
    }



    protected function getParams()
    {
        $structure = $this->context()->structureFromQuery() ?: $this->getServiceContext()->getStructure();
        $niveau    = $this->context()->niveauFromQuery();
        $etape     = $this->context()->etapeFromQuery();
        if ($niveau) $niveau = $this->getServiceNiveauEtape()->get($niveau); // entité Niveau

        return [$structure, $niveau, $etape];
    }

}
