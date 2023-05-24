<?php

namespace Contrat\Entity\Db;


use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\TypeMission;
use OffreFormation\Entity\Db\ElementPedagogique;
use Referentiel\Entity\Db\FonctionReferentiel;
use Service\Entity\Db\TypeService;

/**
 * Description of ContratServiceListe
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ContratServiceListe
{
    private ?Contrat $contrat;

    private ?Intervenant $intervenant;

    private ?string $serviceCode;

    private ?string $serviceLibelle;

    private float $serviceComposante;

    private ?string $cm;

    private ?string $td;

    private ?string $tp;

    private ?string $autres;

    private ?Structure $structure;

    private ?TypeService $typeService;

    private ?float $heuresTotales;

    private ?int $id;

    private ?ElementPedagogique $elementPedagogique;

    private ?FonctionReferentiel $fonctionReferentiel;

    private ?TypeMission $typeMission;

    private ?Mission $mission;



    /**
     * @return mixed
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }



    /**
     * @return mixed
     */
    public function getFonctionReferentiel()
    {
        return $this->fonctionReferentiel;
    }



    /**
     * @return mixed
     */
    public function getTypeMission()
    {
        return $this->typeMission;
    }



    /**
     * @return mixed
     */
    public function getStructure()
    {
        return $this->structure;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return Contrat|null
     */
    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }



    /**
     * @return mixed
     */
    public function getServiceCode()
    {
        return $this->serviceCode;
    }



    /**
     * @return mixed
     */
    public function getServiceLibelle()
    {
        return $this->serviceLibelle;
    }



    /**
     * @return mixed
     */
    public function getCm()
    {
        return $this->cm;
    }



    /**
     * @return mixed
     */
    public function getTd()
    {
        return $this->td;
    }



    /**
     * @return mixed
     */
    public function getTp()
    {
        return $this->tp;
    }



    /**
     * @return mixed
     */
    public function getAutres()
    {
        return $this->autres;
    }



    /**
     * @return mixed
     */
    public function getHeuresTotales()
    {
        return $this->heuresTotales;
    }



    /**
     * @return mixed
     */
    public function getTypeService()
    {
        return $this->typeService;
    }



    /**
     * @return mixed
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }



    /**
     * @return mixed
     */
    public function getServiceComposante()
    {
        return $this->serviceComposante;
    }



    /**
     * @return Mission|null
     */
    public function getMission(): ?Mission
    {
        return $this->mission;
    }



    /**
     * @param Mission|null $mission
     */
    public function setMission(?Mission $mission): void
    {
        $this->mission = $mission;
    }
}

