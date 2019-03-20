<?php

namespace Application\View\Helper\Paiement;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\TypeHeures;
use Application\Entity\Db\Traits\TypeHeuresAwareTrait;

/**
 * Description of ElementPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeHeuresViewHelper extends AbstractHelper
{
    use TypeHeuresAwareTrait;

    /**
     *
     * @param Entity $typeHeures
     * @return self
     */
    public function __invoke( TypeHeures $typeHeures = null )
    {
        if ($typeHeures) $this->setTypeHeures($typeHeures);
        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     *
     *
     * @return string Code HTML
     */
    public function render()
    {
        $typeHeures = $this->getTypeHeures();
        if (! $typeHeures) return '';

        if ($typeHeures->getHistoDestruction()){
            return '<p class="bg-danger"><abbr title="Ce type d\'heures n\'existe plus">'.$typeHeures.'</abbr></p>';
        }

        $out = '<abbr title="'.$typeHeures->getLibelleLong().'">'.$typeHeures.'</abbr>';
        return $out;
    }
}