<?php

namespace Application\Controller;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\Db\GroupeTypeFormation;
use Application\Entity\Db\Traits\CheminPedagogiqueAwareTrait;
use Application\Entity\Db\TypeFormation;
use Application\Entity\Db\TypeModulateur;
use Application\Entity\NiveauEtape;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Application\Service\Traits\EtapeServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\NiveauEtapeServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;
use Zend\Debug\Debug;
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

    /**
     * @var Container
     */
    protected $sessionContainer;



    protected function initFilters()
    {
        /* Mise en place des filtres */
        $this->em()->getFilters()->enable('historique')->init([
            ElementPedagogique::class,
            TypeFormation::class,
            GroupeTypeFormation::class,
            TypeModulateur::class,
        ]);
        $this->em()->getFilters()->enable('annee')->init([
            ElementPedagogique::class,
            Etape::class,
        ]);
    }



    protected function getParams()
    {
        $structure = $this->context()->structureFromQuery() ?: $this->getServiceContext()->getStructure();
        $niveau    = $this->context()->niveauFromQuery();
        $etape     = $this->context()->etapeFromQuery();
        if ($niveau) $niveau = $this->getServiceNiveauEtape()->get($niveau); // entité Niveau
        return [$structure, $niveau, $etape];
    }



    protected function getNeep($structure, $niveau, $etape)
    {
        if (!$structure) return [[], [], []];

        $niveaux  = [];
        $etapes   = [];
        $elements = [];

        $query = $this->em()->createQuery('SELECT
                partial e.{id,code,libelle,sourceCode,niveau,histoDestruction},
                partial tf.{id},
                partial gtf.{id, libelleCourt, ordre},
                partial ep.{id,code,libelle,sourceCode,etape,periode,tauxFoad,fi,fc,fa,tauxFi,tauxFc,tauxFa}
            FROM
              Application\Entity\Db\Etape e
              JOIN e.structure s
              JOIN e.typeFormation tf
              JOIN tf.groupe gtf
              LEFT JOIN e.elementPedagogique ep
            WHERE
              s = :structure OR ep.structure = :structure
            ORDER BY
              gtf.ordre, e.niveau
            ');
        $query->setParameter('structure', $structure);
        $result = $query->getResult();

        foreach ($result as $object) {
            if ($object instanceof Etape) {
                $n                    = NiveauEtape::getInstanceFromEtape($object);
                if ($object->estNonHistorise()) {
                    $niveaux[$n->getId()] = $n;
                }
                if (!$niveau || $niveau->getId() == $n->getId()) {
                    if ($object->estNonHistorise() || $object->getElementPedagogique()->count() > 0){
                        $etapes[] = $object;
                    }
                    if (!$etape || $etape === $object) {
                        foreach ($object->getElementPedagogique() as $ep) {
                            $elements[$ep->getId()] = $ep;
                        }
                    }
                }
            }
        }

        /* Tris */
        uasort($etapes, function (Etape $e1, Etape $e2) {
            $e1Lib = ($e1->getElementPedagogique()->isEmpty() ? 'a_' : 'z_') . strtolower(trim($e1->getLibelle()));
            $e2Lib = ($e2->getElementPedagogique()->isEmpty() ? 'a_' : 'z_') . strtolower(trim($e2->getLibelle()));

            return $e1Lib > $e2Lib;
        });

        uasort($elements, function (ElementPedagogique $e1, ElementPedagogique $e2) {
            $e1Lib = strtolower(trim($e1->getEtape()->getLibelle() . ' ' . $e1->getLibelle()));
            $e2Lib = strtolower(trim($e2->getEtape()->getLibelle() . ' ' . $e2->getLibelle()));

            return $e1Lib > $e2Lib;
        });

        return [$niveaux, $etapes, $elements];
    }



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
        list($niveaux, $etapes, $elements) = $this->getNeep($structure, $niveau, $etape);

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

        $elements = $this->getNeep($structure, $niveau, $etape)[2];
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


        $structures = $this->getServiceStructure()->getList($this->getServiceStructure()->finderByEnseignement());
        $anneeEnCours = $this->getServiceContext()->getAnnee();
        $anneeSuivante = $this->getServiceContext()->getAnneeSuivante();
        $this->getServiceMiseEnPaiementIntervenantStructure()
        /*$serviceAnnee = $this->getServiceAnnee();
        $selectedAnnee = $this->getServiceAnnee()->get('2020');
        Debug::dump($selectedAnnee);*/


        list($structure, $niveau, $etape) = $this->getParams();

        //Reconduction oc selectionnée
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datas = $request->getPost();
            //Duplicate formation
            //TODO : faire un service pour déporter le code du controlleur
            if(!empty($datas['etape']))
            {
                foreach($datas['etape'] as $v)
                {
                    $etapeEnCours = $this->getServiceEtape()->get($v);
                    $reconductionEtape = clone $etapeEnCours;
                    $reconductionEtape->setAnnee($anneeSuivante);
                    $reconductionEtape->setSourceCode(md5(microtime()));
                    // $this->em()->persist($reconductionEtape);
                    //$this->em()->flush();
                    unset($etapeEnCours, $reconductionEtape);

                }
            }

            //duplicate element pédagogique
            if(!empty($datas['element']))
            {
                foreach($datas['element'] as $v)
                {
                    $elementEnCours = $this->getServiceElementPedagogique()->get($v);
                    $reconductionElement = clone $elementEnCours;
                    $reconductionElement->setAnnee($anneeSuivante);
                    $reconductionElement->setSourceCode(md5(microtime()));
                    //$this->em()->persist($reconductionElement);
                   // $this->em()->flush();
                    unset($etapeEnCours, $reconductionEtape);

                }
            }

        }


        $this->getServiceLocalContext()
            ->setStructure($structure)
            ->setNiveau($niveau)
            ->setEtape($etape);

        list($niveaux, $etapes, $elements) = $this->getNeep($structure, $niveau, $etape);
        //Order ETAPE > ELEMENT PEDAGOGIQUE
        $etapesComplementaires = [];
        foreach ($etapes as $v)
        {
            $etapesComplementaires[$v->getId()]['etape'] = $v;
            $etapesComplementaires[$v->getId()]['elements_pedagogique'] = [];

        }
        foreach ($elements as $v)
        {
            $structureId = $v->getStructure()->getId();
            $etapesComplementaires[$structureId]['elements_pedagogique'][] = $v;
        }

        //Load specific JS
        //TODO : A mettre dans un plugin générique
        $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
        $headScript = $viewHelperManager->get('headScript');
        $headScript->appendFile('/js/reconduction-offre.js');

        return [
            'etapesComplementaires' => $etapesComplementaires,
            'anneeEnCours' => $anneeEnCours,
            'structure'  => $structure,
            'structures' => $structures,
            'niveau'     => $niveau,
            'niveaux'    => $niveaux,
            'etape'  => $etape,
            'etapes' => $etapes,
            'elements' => $elements
        ];



    }

}
