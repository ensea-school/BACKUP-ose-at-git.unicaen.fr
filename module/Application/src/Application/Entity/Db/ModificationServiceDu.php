<?php

namespace Application\Entity\Db;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * ModificationServiceDu
 */
class ModificationServiceDu implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        $heures = \UnicaenApp\Util::formattedFloat($this->getHeures(), \NumberFormatter::DECIMAL, -1);

        return sprintf("%s (%sh)%s",
            $this->getMotif(),
            $heures,
            ($com = $this->getCommentaires()) ? " - $com" : null);
    }



    /**
     * @var float
     */
    protected $heures;

    /**
     * @var string
     */
    protected $commentaires;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\MotifModificationServiceDu
     */
    protected $motif;

    /**
     * @var \Application\Entity\Db\IntervenantPermanent
     */
    protected $intervenant;



    /**
     * Set heures
     *
     * @param float $heures
     *
     * @return ModificationServiceDu
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }



    /**
     * Get heures
     *
     * @return float
     */
    public function getHeures()
    {
        return $this->heures;
    }



    /**
     * Set commentaires
     *
     * @param string $commentaires
     *
     * @return ModificationServiceDu
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;

        return $this;
    }



    /**
     * Get commentaires
     *
     * @return string
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set motif
     *
     * @param \Application\Entity\Db\MotifModificationServiceDu $motif
     *
     * @return ModificationServiceDu
     */
    public function setMotif(\Application\Entity\Db\MotifModificationServiceDu $motif = null)
    {
        $this->motif = $motif;

        return $this;
    }



    /**
     * Get motif
     *
     * @return \Application\Entity\Db\MotifModificationServiceDu
     */
    public function getMotif()
    {
        return $this->motif;
    }



    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\IntervenantPermanent $intervenant
     *
     * @return ModificationServiceDu
     */
    public function setIntervenant(\Application\Entity\Db\IntervenantPermanent $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\IntervenantPermanent
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }
}
