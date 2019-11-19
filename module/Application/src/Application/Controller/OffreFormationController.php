<?php

namespace Application\Controller;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\Db\GroupeTypeFormation;
use Application\Entity\Db\TypeFormation;
use Application\Entity\Db\TypeModulateur;
use Application\Entity\NiveauEtape;
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
                $elemendockt->getTauxFa(),
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
        $role         = $this->getServiceContext()->getSelectedIdentityRole();
        $structures   = $this->getServiceStructure()->getList($this->getServiceStructure()->finderByRole($role));
        $anneeEnCours = $this->getServiceContext()->getAnnee();
        list($offresComplementaires, $mappingEtape, $reconductionTotale) = $this->getOffreComplementaire();


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



    protected function getNeep($structure, $niveau, $etape, $annee = null)
    {

        if (is_null($annee)) {
            $annee = $this->getServiceContext()->getAnnee();
        }

        if (!$structure) return [[], [], []];

        $niveaux  = [];
        $etapes   = [];
        $elements = [];

        $query = $this->em()->createQuery('SELECT
                partial e.{id,code,annee,libelle,sourceCode,niveau,histoDestruction},
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
              (s = :structure OR ep.structure = :structure) AND e.annee = :annee
            ORDER BY
              gtf.ordre, e.niveau
            ');
        $query->setParameter('structure', $structure);
        $query->setParameter('annee', $annee);
        $result = $query->getResult();

        foreach ($result as $object) {
            if ($object instanceof Etape) {
                $n = NiveauEtape::getInstanceFromEtape($object);
                if ($object->estNonHistorise()) {
                    $niveaux[$n->getId()] = $n;
                }
                if (!$niveau || $niveau->getId() == $n->getId()) {
                    if ($object->estNonHistorise() || $object->getElementPedagogique()->count() > 0) {
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



    /**
     * @return array
     */

    public function getOffreComplementaire()
    {
        $offresComplementaires = [];
        $anneeEnCours          = $this->getServiceContext()->getAnnee();
        $anneeSuivante         = $this->getServiceAnnee()->getSuivante($anneeEnCours);

        //Récupération des paramètres GET
        list($structure, $niveau, $etape) = $this->getParams();

        $this->getServiceLocalContext()
            ->setStructure($structure)
            ->setNiveau($niveau)
            ->setEtape($etape);


        //Offre année en cours
        list($niveaux, $etapes, $elements) = $this->getServiceOffreFormation()->getNeepComplementaire($structure, $niveau, $etape, $anneeEnCours);
        //Offre année suivante
        list($niveauxN1, $etapesN1, $elementsN1) = $this->getServiceOffreFormation()->getNeepComplementaire($structure, $niveau, $etape, $anneeSuivante);

        //Organisation pour traitement dans la vue
        $codesEtapeN1          = [];
        $codesElementN1        = [];
        $etapesNonReconduits   = array_diff($etapes, $etapesN1);
        $elementsNonReconduits = array_diff($elements, $elementsN1);

        $reconductionTotale = 'non';
        if (empty($etapesNonReconduits) && empty($elementsNonReconduits)) {
            $reconductionTotale = 'oui';
        }

        foreach ($elementsN1 as $v) {
            $codesElementN1[] = $v->getCode();
        }
        foreach ($etapesN1 as $v) {
            $codesEtapeN1[] = $v->getCode();
        }

        foreach ($etapes as $v) {

            /*if (!$v->isFromSourceOse()) {
                continue;
            }*/
            $offresComplementaires[$v->getId()]['reconduction_partiel'] = 'non';
            $offresComplementaires[$v->getId()]['reconduction']         = (in_array($v->getCode(), $codesEtapeN1)) ? 'oui' : 'non';
            $offresComplementaires[$v->getId()]['etape']                = $v;
            $offresComplementaires[$v->getId()]['elements_pedagogique'] = [];
        }

        foreach ($elements as $v) {

            /*if (!$v->getEtape()->isFromSourceOse()) {
                continue;
            }*/

            $etapeId = $v->getEtape()->getId();

            if (!in_array($v->getCode(), $codesElementN1)) {
                $offresComplementaires[$etapeId]['reconduction_partiel'] = 'oui';
            }

            $offresComplementaires[$etapeId]['elements_pedagogique'][$v->getId()]['reconduction'] = (in_array($v->getCode(), $codesElementN1)) ? 'oui' : 'non';
            $offresComplementaires[$etapeId]['elements_pedagogique'][$v->getId()]['element']      = $v;
        }

        $mappingEtape = $this->createMappingEtapeNEtapeN1($etapes, $etapesN1);

        return [$offresComplementaires, $mappingEtape, $reconductionTotale];
    }



    public function createMappingEtapeNEtapeN1($etapesN, $etapesN1)
    {
        $codesEtapeN  = [];
        $codesEtapeN1 = [];
        $mappingEtape = [];


        foreach ($etapesN1 as $v) {
            $codesEtapeN1[$v->getCode()] = $v->getId();
        }

        foreach ($etapesN as $v) {
            $codesEtapeN[$v->getCode()] = $v->getId();
        }

        foreach ($codesEtapeN as $k => $v) {
            if (array_key_exists($k, $codesEtapeN1)) {
                $mappingEtape[$v] = $codesEtapeN1[$k];
            }
        }

        return $mappingEtape;
    }

}
