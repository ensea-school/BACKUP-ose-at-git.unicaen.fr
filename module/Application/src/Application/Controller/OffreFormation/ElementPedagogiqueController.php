<?php

namespace Application\Controller\OffreFormation;

use Application\Form\OffreFormation\Traits\ElementPedagogiqueSaisieAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Exception\DbException;
use Application\Service\Traits\ElementPedagogiqueAwareTrait;
use Application\Service\Traits\ContextAwareTrait;

/**
 * Description of ElementPedagogiqueController
 *
 * @method \Doctrine\ORM\EntityManager            em()
 * @method \Application\Controller\Plugin\Context context()
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ElementPedagogiqueController extends AbstractActionController
{
    use ElementPedagogiqueAwareTrait;
    use ContextAwareTrait;
    use ElementPedagogiqueSaisieAwareTrait;



    public function voirAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\CheminPedagogique::class,
            \Application\Entity\Db\VolumeHoraire::class,
        ]);
        $element = $this->getEvent()->getParam('elementPedagogique');
        $title   = "Enseignement";

        return compact('element', 'title');
    }



    protected function saisirAction()
    {
        $element = $this->getEvent()->getParam('elementPedagogique');
        $title   = $element ? "Modification d'un enseignement" : "Création d'un enseignement";
        $form    = $this->getFormOffreFormationElementPedagogiqueSaisie();
        $errors  = [];

        if ($element) {
            $form->bind($element);
        } else {
            $element = $this->getServiceElementPedagogique()->newEntity();
            $form->setObject($element);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getServiceElementPedagogique()->save($element);
                    $form->get('id')->setValue($element->getId()); // transmet le nouvel ID
                } catch (\Exception $e) {
                    $e        = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }
        }

        return compact('form', 'title', 'errors');
    }



    public function supprimerAction()
    {
        $element = $this->getEvent()->getParam('elementPedagogique');
        if (!$element) {
            throw new \Common\Exception\RuntimeException('L\'identifiant n\'est pas bon ou n\'a pas été fourni');
        }

        $title  = "Suppression d'enseignement";
        $form   = new \Application\Form\Supprimer('suppr');
        $errors = [];
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $deleted = false;

        if ($this->getRequest()->isPost()) {
            try {
                $this->getServiceElementPedagogique()->delete($element);
                $deleted = true;
            } catch (\Exception $e) {
                $e        = DbException::translate($e);
                $errors[] = $e->getMessage();
            }
        }

        return compact('element', 'title', 'form', 'errors', 'deleted');
    }



    /**
     * Action pour rechercher des éléments pédagogiques.
     *
     * Les filtres pris en compte sont :
     * - structure du contexte local,
     * - niveau du contexte local,
     * - étape du contexte local,
     * Éventuellement écrasés par ceux-là :
     * - paramètre GET 'structure' (id d'une structure),
     * - paramètre GET 'niveau' (ex: 'L-2'),
     * - paramètre GET 'etape' (id d'une étape),
     *
     * NB: Les résultats sont renvoyés au format JSON.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function searchAction()
    {
        $this->em()->getFilters()->enable('annee')->init([
            \Application\Entity\Db\ElementPedagogique::class,
        ]);

        $structure = $this->context()->structureFromQuery();
        $niveau    = $this->context()->niveauFromQuery();
        $etape     = $this->context()->etapeFromQuery();
        $element   = $this->context()->elementPedagogiqueFromQuery();
        $term      = $this->params()->fromQuery('term');

        if (!$etape && !$term) {
            exit;
        }

        // respect des filtres éventuels spécifiés en GET ou sinon en session
        $params              = [];
        $params['structure'] = $structure;
        $params['niveau']    = $niveau;
        $params['etape']     = $etape;
        $params['element']   = $element;
        $params['term']      = $term;
        $params['limit']     = $limit = 101;

        // fetch
        $found = $this->getServiceElementPedagogique()->getSearchResultByTerm($params);

        $result = [];
        foreach ($found as $item) {
            if ($item['NB_CH'] > 1) {
                $item['LIBELLE_ETAPE'] = 'Enseignement commun à plusieurs parcours';
            }

            $extra = '';
            if (!$niveau) {
                $extra .= sprintf('<span class="element-rech niveau" title="%s">%s</span>', "Niveau", $item['LIBELLE_GTF'] . $item['NIVEAU']);
            }
            if (!$etape) {
                $extra .= sprintf('<span class="element-rech etape" title="%s">%s</span>', "Formation", $item['LIBELLE_ETAPE']);
            }
            $extra .= "Année" !== $item['LIBELLE_PE'] ? sprintf('<span class="element-rech periode" title="%s">%s</span>', "Période", $item['LIBELLE_PE']) : null;
            $template            = sprintf('<span class="element-rech extra">{extra}</span><span class="element-rech element" title="%s">{label}</span>', "Enseignement");
            $result[$item['ID']] = [
                'id'       => $item['ID'],
                'label'    => $item['SOURCE_CODE'] . ' ' . $item['LIBELLE'],
                'extra'    => $extra,
                'template' => $template,
            ];
        };

        $result = \UnicaenApp\Form\Element\SearchAndSelect::truncatedResult($result, $limit - 1);

        return new \Zend\View\Model\JsonModel($result);
    }



    public function getPeriodeAction()
    {
        $elementPedagogique = $this->context()->elementPedagogiqueFromRoute();
        $code               = null;
        if ($elementPedagogique) {
            if ($periode = $elementPedagogique->getPeriode()) {
                $code = $periode->getCode();
            }
        }
        $result = ['periode' => ['code' => $code]];

        return new \Zend\View\Model\JsonModel($result);
    }

}
