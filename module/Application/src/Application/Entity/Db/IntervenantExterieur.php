<?php

namespace Application\Entity\Db;


/**
 * IntervenantExterieur
 */
class IntervenantExterieur extends Intervenant
{

    /**
     * @var \Application\Entity\Db\SituationFamiliale
     */
    protected $situationFamiliale;



    /**
     * Set situationFamiliale
     *
     * @param \Application\Entity\Db\SituationFamiliale $situationFamiliale
     *
     * @return IntervenantExterieur
     */
    public function setSituationFamiliale(\Application\Entity\Db\SituationFamiliale $situationFamiliale = null)
    {
        $this->situationFamiliale = $situationFamiliale;

        return $this;
    }



    /**
     * Get situationFamiliale
     *
     * @return \Application\Entity\Db\SituationFamiliale
     */
    public function getSituationFamiliale()
    {
        return $this->situationFamiliale;
    }




}