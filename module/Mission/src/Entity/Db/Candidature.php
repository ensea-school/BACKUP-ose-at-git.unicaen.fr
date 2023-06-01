<?php

namespace Mission\Entity\Db;


use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Validation;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenVue\Axios\AxiosExtractorInterface;

class Candidature implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;
    use StructureAwareTrait;

    protected ?int        $id         = null;

    protected Intervenant $intervenant;

    protected OffreEmploi $offre;

    protected ?Validation $validation = null;

    protected ?string     $motif      = null;



    public function getResourceId()
    {
        return 'Candidature';
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return 'Candidature ' . $this->getId();
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * @return Intervenant
     */
    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }



    /**
     * @param Intervenant $intervenant
     */
    public function setIntervenant(Intervenant $intervenant): self
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     * @return OffreEmploi
     */
    public function getOffre(): OffreEmploi
    {
        return $this->offre;
    }



    /**
     * @param OffreEmploi $offre
     */
    public function setOffre(OffreEmploi $offre): self
    {
        $this->offre = $offre;

        return $this;
    }



    /**
     * @return ?Validation
     */
    public function getValidation(): ?Validation
    {
        return $this->validation;
    }



    /**
     * @param ?Validation $validation
     */
    public function setValidation(?Validation $validation): self
    {
        $this->validation = $validation;

        return $this;
    }



    public function isValide(): bool
    {

        if ($validation = $this->getValidation()) {
            if ($validation->estNonHistorise()) return true;
        }

        return false;
    }



    /**
     * @return string|null
     */
    public function getMotif(): ?string
    {
        return $this->motif;
    }



    /**
     * @param string|null $motif
     */
    public function setMotif(?string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

}
