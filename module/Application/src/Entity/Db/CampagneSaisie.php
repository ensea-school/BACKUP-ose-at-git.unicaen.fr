<?php

namespace Application\Entity\Db;

use Application\Constants;
use Application\Entity\Db\Traits\AnneeAwareTrait;
use Intervenant\Entity\Db\TypeIntervenantAwareTrait;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;

/**
 * CampagneSaisie
 */
class CampagneSaisie
{
    use AnneeAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use TypeIntervenantAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $dateDebut;

    /**
     * @var \DateTime
     */
    protected $dateFin;

    /**
     * @var messageIntervenant
     */
    protected $messageIntervenant;

    /**
     * @var messageAutres
     */
    protected $messageAutres;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @param int $id
     *
     * @return CampagneSaisie
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }



    /**
     * @param \DateTime $dateDebut
     *
     * @return CampagneSaisie
     */
    public function setDateDebut($dateDebut = null)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }



    /**
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }



    /**
     * @param \DateTime $dateFin
     *
     * @return CampagneSaisie
     */
    public function setDateFin($dateFin = null)
    {
        $this->dateFin = $dateFin;

        return $this;
    }



    /**
     * @return messageIntervenant
     */
    public function getMessageIntervenant()
    {
        return $this->messageIntervenant;
    }



    /**
     * @param messageIntervenant $messageIntervenant
     *
     * @return CampagneSaisie
     */
    public function setMessageIntervenant($messageIntervenant)
    {
        $this->messageIntervenant = $messageIntervenant;

        return $this;
    }



    /**
     * @return messageAutres
     */
    public function getMessageAutres()
    {
        return $this->messageAutres;
    }



    /**
     * @param \Application\Acl\Role $role
     *
     * @return string
     */
    public function getMessage(\Application\Acl\Role $role)
    {
        if ($role->getIntervenant()) {
            $message = $this->getMessageIntervenant();
        } else {
            $message = $this->getMessageAutres();
        }

        return $this->formatMessage($message);
    }



    /**
     * @param messageAutres $messageAutres
     *
     * @return CampagneSaisie
     */
    public function setMessageAutres($messageAutres)
    {
        $this->messageAutres = $messageAutres;

        return $this;
    }



    private function formatMessage($message)
    {
        $dateDebut = $this->dateDebut ? $this->dateDebut->format(Constants::DATE_FORMAT) : '[Pas de date définie]';
        $dateFin   = $this->dateFin ? $this->dateFin->format(Constants::DATE_FORMAT) : '[Pas de date définie]';

        return str_replace([':dateDebut', ':dateFin'], [$dateDebut, $dateFin], $message);
    }



    /**
     * Retourne true si la date transmise ou à défaut la date du jour est dans la période, false sinon
     *
     * @return boolean
     */
    public function estOuverte(\DateTime $date = null)
    {
        $f = 'Y-m-d';

        if (empty($date)) $date = new \DateTime();

        $dc = $date->format($f);
        $dd = $this->dateDebut ? $this->dateDebut->format($f) : $dc;
        $df = $this->dateFin ? $this->dateFin->format($f) : $dc;

        if ($dc < $dd) return false;
        if ($dc > $df) return false;

        return true;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getMessage();
    }

}
