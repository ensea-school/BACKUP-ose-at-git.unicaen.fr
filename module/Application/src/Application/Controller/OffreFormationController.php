<?php

namespace Application\Controller;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\Db\GroupeTypeFormation;
use Application\Entity\Db\TypeFormation;
use Application\Entity\Db\TypeModulateur;
use Application\Entity\NiveauEtape;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\ElementPedagogiqueAwareTrait;
use Application\Service\Traits\EtapeAwareTrait;
use Application\Service\Traits\LocalContextAwareTrait;
use Application\Service\Traits\NiveauEtapeAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenApp\View\Model\CsvModel;
use Zend\Form\Form;
use Zend\Session\Container;


/**
 * Description of OffreFormationController
 *
 *
 */
class OffreFormationController extends AbstractController
{
    use ContextAwareTrait;
    use LocalContextAwareTrait;
    use StructureAwareTrait;
    use ElementPedagogiqueAwareTrait;
    use EtapeAwareTrait;
    use NiveauEtapeAwareTrait;

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
                partial e.{id,libelle,sourceCode,niveau},
                partial tf.{id},
                partial gtf.{id, libelleCourt, ordre},
                partial ep.{id,libelle,sourceCode,etape,periode,tauxFoad,fi,fc,fa,tauxFi,tauxFc,tauxFa},
                partial eff.{id,fi,fa,fc}
            FROM
              Application\Entity\Db\Etape e
              JOIN e.structure s
              JOIN e.typeFormation tf
              JOIN  tf.groupe gtf
              LEFT JOIN e.elementPedagogique ep
              LEFT JOIN ep.effectifs eff
            WHERE
              s = :structure
            ORDER BY
              gtf.ordre, e.niveau
            ');
        $query->setParameter('structure', $structure);
        $result = $query->getResult();

        foreach ($result as $object) {
            if ($object instanceof Etape) {
                $n                    = NiveauEtape::getInstanceFromEtape($object);
                $niveaux[$n->getId()] = $n;
                if (!$niveau || $niveau->getId() == $n->getId()) {
                    $etapes[] = $object;
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
        $ep = new SearchAndSelect('element');
        $ep
            ->setAutocompleteSource($this->url()->fromRoute('of/element/search', [], ['query' => $params]))
            ->setLabel("Recherche :")
            ->setAttributes(['title' => "Saisissez 2 lettres au moins"]);
        $form = new Form('search');
        $form->setAttributes(['class' => 'element-rech']);
        $form->add($ep);

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
            'form'           => $form,
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

}
