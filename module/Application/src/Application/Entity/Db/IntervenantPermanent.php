<?php

namespace Application\Entity\Db;

/**
 * IntervenantPermanent
 */
class IntervenantPermanent extends Intervenant
{
    /**
     * @var \Application\Entity\Db\Corps
     */
    private $corps;

    /**
     * @var \Application\Entity\Db\SectionCnu
     */
    private $sectionCnu;

    /**
     * Set corps
     *
     * @param \Application\Entity\Db\Corps $corps
     * @return IntervenantPermanent
     */
    public function setCorps(\Application\Entity\Db\Corps $corps = null)
    {
        $this->corps = $corps;

        return $this;
    }

    /**
     * Get corps
     *
     * @return \Application\Entity\Db\Corps 
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * Set sectionCnu
     *
     * @param \Application\Entity\Db\SectionCnu $sectionCnu
     * @return IntervenantPermanent
     */
    public function setSectionCnu(\Application\Entity\Db\SectionCnu $sectionCnu = null)
    {
        $this->sectionCnu = $sectionCnu;

        return $this;
    }

    /**
     * Get sectionCnu
     *
     * @return \Application\Entity\Db\SectionCnu 
     */
    public function getSectionCnu()
    {
        return $this->sectionCnu;
    }
    /**
     * @var \Application\Entity\Db\Historique
     */
    private $historique;


    /**
     * Set historique
     *
     * @param \Application\Entity\Db\Historique $historique
     * @return IntervenantPermanent
     */
    public function setHistorique(\Application\Entity\Db\Historique $historique = null)
    {
        $this->historique = $historique;

        return $this;
    }

    /**
     * Get historique
     *
     * @return \Application\Entity\Db\Historique 
     */
    public function getHistorique()
    {
        return $this->historique;
    }
}
