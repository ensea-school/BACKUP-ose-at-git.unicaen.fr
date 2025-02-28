<?php

namespace Mission\Entity\Db;

use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\StructureAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use Workflow\Entity\Db\Validation;

class Candidature implements HistoriqueAwareInterface, ResourceInterface
{
    const MODELE_MAIL_ACCEPTATION = 'candidature_modele_acceptation_mail';

    const MODELE_MAIL_REFUS = 'candidature_modele_refus_mail';

    const OBJET_MAIL_ACCEPTATION = 'candidature_modele_acceptation_mail_objet';

    const OBJET_MAIL_REFUS = 'candidature_modele_refus_mail_objet';

    use HistoriqueAwareTrait;
    use StructureAwareTrait;

    protected ?int        $id         = null;

    protected Intervenant $intervenant;

    protected OffreEmploi $offre;

    protected ?Validation $validation = null;

    protected ?string     $motif      = null;

    protected ?\DateTime $dateCommission = null;


    public function getResourceId ()
    {
        return 'Candidature';
    }


    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString ()
    {
        return 'Candidature ' . $this->getId();
    }


    public function getId (): ?int
    {
        return $this->id;
    }


    /**
     * @return Intervenant
     */
    public function getIntervenant (): Intervenant
    {
        return $this->intervenant;
    }


    /**
     * @param Intervenant $intervenant
     */
    public function setIntervenant (Intervenant $intervenant): self
    {
        $this->intervenant = $intervenant;

        return $this;
    }


    /**
     * @return OffreEmploi
     */
    public function getOffre (): OffreEmploi
    {
        return $this->offre;
    }


    /**
     * @param OffreEmploi $offre
     */
    public function setOffre (OffreEmploi $offre): self
    {
        $this->offre = $offre;

        return $this;
    }


    public function isValide (): bool
    {
        $validation = $this->getValidation();
        if ($validation) {
            if ($validation->estNonHistorise()) return true;
        }

        return false;
    }


    /**
     * @return ?Validation
     */
    public function getValidation (): ?Validation
    {
        return $this->validation;
    }


    /**
     * @param ?Validation $validation
     */
    public function setValidation (?Validation $validation): self
    {
        $this->validation = $validation;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getMotif (): ?string
    {
        return $this->motif;
    }


    /**
     * @param string|null $motif
     */
    public function setMotif (?string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getDateCommission (): ?\DateTime
    {
        return $this->dateCommission;
    }

    public function setDateCommission (?\DateTime $dateCommission): self
    {
        $this->dateCommission = $dateCommission;

        return $this;
    }

}
