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

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Indicateur210
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     *
     * @return Indicateur210
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }

    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     *
     * @return Indicateur210
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }
}
