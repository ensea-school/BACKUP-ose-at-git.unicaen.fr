<?php

namespace Workflow\Service;

use Application\Service\AbstractEntityService;
use Application\Service\Collection;
use Application\Service\RuntimeException;
use Doctrine\ORM\QueryBuilder;
use Laminas\Form\Element\Select;
use Workflow\Entity\Db\WfEtape;


/**
 * Description of Service
 *
 * @author Bertrand
 */
class WfEtapeService extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return WfEtape::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'e';
    }



    /**
     * Recherche une étapde par son code.
     *
     * @param string $code
     *
     * @return WfEtape
     */
    public function getByCode($code)
    {
        return $this->finderByCode($code)->getQuery()->getOneOrNullResult();
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->orderBy($alias . '.ordre');

        return $qb;
    }



    /**
     * @param Collection|null $wfEtapes
     *
     * @return Select
     */

    public function getWfEtapeElement(?Collection $wfEtapes = null)
    {
        $wfEtapesElement = new Select('select-wfetapes');
        $wfEtapesElement->setLabel('Liste étapes');
        $attributes = [
            'multiple'                  => 'multiple',
            'class'                     => 'selectpicker',
            'data-selected-text-format' => 'count',
            'data-count-selected-text'  => '{0} étape(s) sélectionnée(s)',
            'data-with'                 => 'auto',
            'title'                     => 'Choisissez les étapes du workflow devant être validées',
        ];
        $wfEtapesElement->setAttributes($attributes);

        $qb = $this->finderByHistorique();
        $qb->orderBy('ordre', 'ASC');
        $wfEtapesElement->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getList($qb)));

        if ($wfEtapes) {
            $ids = [];
            foreach ($wfEtapes as $wfEtape) {
                $ids[] = $wfEtape->getId();
            }
            $wfEtapesElement->setValue($ids);
        }

        return $wfEtapesElement;
    }

}