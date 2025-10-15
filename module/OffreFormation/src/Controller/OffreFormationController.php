<?php

namespace OffreFormation\Controller;

use Application\Controller\AbstractController;
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
        if ($structure) {
            $params['structure'] = $structure->getId();
        }
        if ($niveau) {
            $params['niveau'] = ($niveau->getPertinence()) ? $niveau->getId() : $niveau->getLib();
        }
        if ($etape) {
            $params['etape'] = $etape->getId();
        }


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



    public function exportAction(): CsvModel
    {
        $this->initFilters();
        [$structure, $niveau, $etape] = $this->getParams();
        return $this->getServiceOffreFormation()->generateCsvExport($structure, $niveau, $etape);

    }



    public function reconductionAction()
    {
        $this->initFilterHistorique();
        [$structure, $niveau, $etape] = $this->getParams();

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
        if (!$structure = $this->getServiceContext()->getStructure()) {
            $structureId = $this->params()->fromQuery('structure');
            $structure   = $structureId ? $this->em()->find(\Lieu\Entity\Db\Structure::class, $structureId) : null;
        }

        $etapeId = $this->params()->fromQuery('etape');
        $etape = $etapeId ? $this->em()->find(Etape::class, $etapeId) : null;

        $niveau = $this->params()->fromQuery('niveau');
        if ($niveau) {
            $niveau = $this->getServiceNiveauEtape()->get($niveau);
        }

        return [$structure, $niveau, $etape];
    }

}
