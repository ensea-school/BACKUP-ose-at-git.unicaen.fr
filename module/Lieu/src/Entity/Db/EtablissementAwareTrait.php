<?php


namespace Lieu\Entity\Db;

/**
 * Description of EtablissementAwareTrait
 *
 * @author UnicaenCode
 */
trait EtablissementAwareTrait
{
    protected ?Etablissement $etablissement = null;



    /**
     * @param  $etablissement
     *
     * @return self
     */
    public function setEtablissement( ?Etablissement $etablissement )
    {
        $this->etablissement = $etablissement;

        return $this;
    }



    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }
}