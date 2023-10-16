<?php

namespace OffreFormation\Controller;

use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Lieu\Form\Element\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use OffreFormation\Entity\Db\CheminPedagogique;
use OffreFormation\Entity\Db\ElementPedagogique;
use OffreFormation\Entity\Db\Etape;
use OffreFormation\Entity\Db\GroupeTypeFormation;
use OffreFormation\Entity\Db\TypeFormation;
use OffreFormation\Entity\Db\VolumeHoraireEns;
use OffreFormation\Processus\Traits\ReconductionProcessusAwareTrait;
use OffreFormation\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use OffreFormation\Service\Traits\EtapeServiceAwareTrait;
use OffreFormation\Service\Traits\NiveauEtapeServiceAwareTrait;
use OffreFormation\Service\Traits\OffreFormationServiceAwareTrait;
use Paiement\Entity\Db\TypeModulateur;
use UnicaenApp\View\Model\CsvModel;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;


/**
 * Description of OffreFormationController
 *
 *
 */
class OffreFormationController extends \Application\Controller\AbstractController
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
    use SchemaServiceAwareTrait;

    private Structure $structureElement;



    public function __construct(Structure $structureElement)
    {
        $this->structureElement = $structureElement;
    }



    public function indexAction()
    {
        $this->initFilters();


        [$structure, $niveau, $etape] = $this->getParams();

        // persiste les filtres dans le contexte local
        $this->getServiceLocalContext()
            ->setStructure($structure)
            ->setNiveau($niveau)
            ->setEtape($etape);

        [$niveaux, $etapes, $elements] = $this->getServiceOffreFormation()->getNeep($structure, $niveau, $etape);

        $params = [];
        if ($structure) $params['structure'] = $structure->getId();
        if ($niveau) $params['niveau'] = ($niveau->getPertinence()) ? $niveau->getId() : $niveau->getLib();
        if ($etape) $params['etape'] = $etape->getId();


        // élément pédagogique sélectionné dans le champ de recherche
        if (($element = $this->params()->fromPost('element')) && isset($element['id'])) {
            $form->get('element')->setValue($element);
        }

        return [
            'structureElement' => $this->structureElement,
            'niveaux'          => $niveaux,
            'etapes'           => $etapes,
            'elements'         => $elements,
            'structure'        => $structure,
            'niveau'           => $niveau,
            'etape'            => $etape,
            'serviceEtape'     => $this->getServiceEtape(), // pour déterminer les droits
            'serviceElement'   => $this->getServiceElementPedagogique(), // pour déterminer les droits
            'serviceSchema'    => $this->getServiceSchema(),
        ];
    }



    public function exportAction()
    {
        $this->initFilters();

        [$structure, $niveau, $etape] = $this->getParams();

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
            'Nbr heures CM',
            'Nbr groupes CM',
            'Nbr heures TD',
            'Nbr groupes TD',
            'Nbr heures TP',
            'Nbr groupes TP',
        ]);


        foreach ($elements as $element) {
            $cm = '0';
            $td = '0';
            $tp = '0';
            $cmGroupe = '0';
            $tdGroupe = '0';
            $tpGroupe = '0';

            foreach ($element->getVolumeHoraireEns() as $vhe) {
                if ($vhe->getTypeIntervention()->getCode() == 'CM') {
                    $cm = (!empty($vhe->getHeures())) ? $vhe->getHeures() : '0';
                    $cmGroupe = (!empty($vhe->getGroupes())) ? $vhe->getGroupes() : '0';
                }
                if ($vhe->getTypeIntervention()->getCode() == 'TD') {
                    $td = (!empty($vhe->getHeures())) ? $vhe->getHeures() : '0';
                    $tdGroupe = (!empty($vhe->getGroupes())) ? $vhe->getGroupes() : '0';
                }
                if ($vhe->getTypeIntervention()->getCode() == 'TP') {
                    $tp = (!empty($vhe->getHeures())) ? $vhe->getHeures() : '0';
                    $tpGroupe = (!empty($vhe->getGroupes())) ? $vhe->getGroupes() : '0';
                }
            }

            $etape = $element->getEtape();
            $effectifs = $element->getEffectifs();
            $discipline = $element->getDiscipline();
            $csvModel->addLine([
                $etape->getCode(),
                $etape->getLibelle(),
                $etape->getNiveauToString(),
                $element->getCode(),
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
                $cm,
                $cmGroupe,
                $td,
                $tdGroupe,
                $tp,
                $tpGroupe,
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
        [$structure, $niveau, $etape] = $this->getParams();
        //Get role of user
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $anneeEnCours = $this->getServiceContext()->getAnnee();
        [$offresComplementaires, $mappingEtape, $reconductionTotale] = $this->getServiceOffreFormation()->getOffreComplementaire($structure, $niveau, $etape);

        $reconductionStep = '';
        $messageStep = '';
        $fromPost = false;


        $request = $this->getRequest();
        if ($request->isPost()) {
            $datas = $request->getPost();
            $fromPost = true;
            //Ajout du mapping des EtapesN et EtapesN1 pour pouvoir reconduire un element pédagogique sur une etape déjà reconduite.
            $datas['mappingEtape'] = $mappingEtape;
            $reconductionProcessus = $this->getProcessusReconduction();
            try {
                //Disable filter historique pour regarder si étape ou element avec date de desctruction
                $this->disableFilters('historique');
                if ($reconductionProcessus->reconduction($datas)) {
                    $this->flashMessenger()->addSuccessMessage("Les éléments ont bien été reconduits pour l'année universitaire prochaine.");
                } else {
                    $this->flashMessenger()->addErrorMessage("Les éléments n'ont pas pu être reconduits. Merci de contacter le support.");
                }
            } catch (\Exception $e) {
                $reconductionStep = false;
                $messageStep = $e->getMessage();
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        }


        return [
            'fromPost'              => $fromPost,
            'offresComplementaires' => $offresComplementaires,
            'reconductionTotale'    => $reconductionTotale,
            'structure'             => $structure,
            'anneeEnCours'          => $anneeEnCours,
            'reconductionStep'      => $reconductionStep,
            'messageStep'           => $messageStep,
        ];
    }



    public function reconductionCentreCoutAction()
    {
        $this->initFilterHistorique();
        $etapesReconduites = [];
        [$structure, $niveau, $etape] = $this->getParams();

        //Get role of user
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $request = $this->getRequest();
        if ($request->isPost()) {

            $datas = $request->getPost();
            //Reconduire les centres de coût des EP de l'étape.
            try {
                $etapesReconduites = $this->getServiceEtape()->getEtapeCentreCoutReconductible($structure);
                $etapesReconduitesCc = [];
                if (isset($datas['etapes'])) {
                    foreach ($datas['etapes'] as $code) {
                        if (array_key_exists($code, $etapesReconduites)) {
                            $etapesReconduitesCc[$code] = $etapesReconduites[$code];
                        }
                    }
                }
                $result = $this->getProcessusReconduction()->reconduireCCFormation($etapesReconduitesCc);
                $etapesReconduites = $this->getServiceEtape()->getEtapeCentreCoutReconductible($structure);

                $this->flashMessenger()->addSuccessMessage("$result centre(s) de coût(s) ont été reconduit pour l'année prochaine. ");
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        }

        if (empty($etapesReconduites) && !empty($structure)) {
            $etapesReconduites = $this->getServiceEtape()->getEtapeCentreCoutReconductible($structure);
        }


        return [
            'structure'         => $structure,
            'etapesReconduites' => $etapesReconduites,
        ];
    }



    public function reconductionModulateurAction()
    {
        $this->initFilterHistorique();

        [$structure, $niveau, $etape] = $this->getParams();
        $etapesReconduites = [];
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $datas = $request->getPost();
            $etapesReconduites = $this->getServiceEtape()->getEtapeModulateurReconductible($structure);
            try {
                $etapesReconduitesCc = [];
                if (isset($datas['etapes'])) {
                    foreach ($datas['etapes'] as $code) {

                        if (array_key_exists($code, $etapesReconduites)) {
                            $etapesReconduitesCc[$code] = $etapesReconduites[$code];
                        }
                    }
                }
                $result = $this->getProcessusReconduction()->reconduireModulateurFormation($etapesReconduitesCc);

                $this->flashMessenger()->addSuccessMessage("$result modulateur(s) ont été reconduits pour l'année prochaine. ");

                $etapesReconduites = $this->getServiceEtape()->getEtapeModulateurReconductible($structure);
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        }

        //Récupération de toutes les étapes éligibles à la reconduction des coûts
        if (empty($etapesReconduites) && !empty($structure)) {
            $etapesReconduites = $this->getServiceEtape()->getEtapeModulateurReconductible($structure);
        }


        return [
            'etapesReconduites' => $etapesReconduites,
            'structure'         => $structure,
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
            CheminPedagogique::class,
            TypeFormation::class,
            GroupeTypeFormation::class,
            TypeModulateur::class,
            VolumeHoraireEns::class,
        ]);
    }



    protected function disableFilters($name)
    {
        $this->em()->getFilters()->disable($name);
    }



    protected function getParams()
    {
        $structure = $this->context()->structureFromQuery() ?: $this->getServiceContext()->getStructure();
        $niveau = $this->context()->niveauFromQuery();
        $etape = $this->context()->etapeFromQuery();
        if ($etape) $etape = $this->getServiceEtape()->get($etape->getId()); // entité Niveau
        if ($niveau) $niveau = $this->getServiceNiveauEtape()->get($niveau); // entité Niveau
        if ($structure && !$structure instanceof \Lieu\Entity\Db\Structure) $structure = $this->getServiceStructure()->get($structure);

        return [$structure, $niveau, $etape];
    }

}
