<?php

namespace Application\Entity\Db\Indicateur;
use Application\Entity\Db\Traits\TypeAgrementAwareTrait;


class Indicateur210 extends AbstractIndicateur
{
    use TypeAgrementAwareTrait;



    /**
     * @return array
     */
    public function getUrlParams()
    {
        $up = parent::getUrlParams();
        $up['typeAgrement'] = $this->getTypeAgrement()->getId();
        return $up;
    }
}
