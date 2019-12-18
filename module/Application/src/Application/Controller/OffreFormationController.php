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



    public function reconductionAction()
    {
        $this->initFilterHistorique();
        list($structure, $niveau, $etape) = $this->getParams();
        //Get role of user
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $qb = $this->getServiceStructure()->finderByHistorique();
        $this->getServiceStructure()->finderByEnseignement($qb);
        $this->getServiceStructure()->finderByRole($role, $qb);
        $structures = $this->getServiceStructure()->getList($qb);

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
