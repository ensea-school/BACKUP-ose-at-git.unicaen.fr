<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * NumeroInsee
 *
 * @ORM\Table(name="NUMERO_INSEE")
 * @ORM\Entity
 */
class NumeroInsee
{
    /**
     * @var string
     *
     * @ORM\Column(name="CLE", type="string", length=2, nullable=false)
     */
    private $cle;

    /**
     * @var string
     *
     * @ORM\Column(name="NUMERO", type="string", length=13, nullable=false)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="PROVISOIRE", type="string", length=1, nullable=false)
     */
    private $provisoire;

    /**
     * @var \Application\Entity\Db\Intervenant
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Db\Intervenant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="INTERVENANT_ID", referencedColumnName="ID")
     * })
     */
    private $intervenant;



    /**
     * Set cle
     *
     * @param string $cle
     * @return NumeroInsee
     */
    public function setCle($cle)
    {
        $this->cle = $cle;

        return $this;
    }

    /**
     * Get cle
     *
     * @return string 
     */
    public function getCle()
    {
        return $this->cle;
    }

    /**
     * Set numero
     *
     * @param string $numero
     * @return NumeroInsee
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set provisoire
     *
     * @param string $provisoire
     * @return NumeroInsee
     */
    public function setProvisoire($provisoire)
    {
        $this->provisoire = $provisoire;

        return $this;
    }

    /**
     * Get provisoire
     *
     * @return string 
     */
    public function getProvisoire()
    {
        return $this->provisoire;
    }

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return NumeroInsee
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;

        return $this;
    }

    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }
}
