<?php

namespace OffreFormation\Entity\Db;

use Administration\Interfaces\ChampsAutresInterface;
use Administration\Traits\ChampsAutresTrait;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\SourceServiceAwareTrait;
use RuntimeException;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * TypeFormation
 */
class TypeFormation extends AbstractEntityService implements HistoriqueAwareInterface, ImportAwareInterface, ChampsAutresInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;
    use SourceServiceAwareTrait;
    use ChampsAutresTrait;

    /**
     * @var string
     */
    protected ?string $libelleCourt = null;

    /**
     * @var string
     */
    protected ?string $libelleLong = null;

    /**
     * @var integer
     */
    protected ?int $id = null;

    /**
     * @var \OffreFormation\Entity\Db\GroupeTypeFormation
     */
    protected ?GroupeTypeFormation $groupe = null;

    protected bool $serviceStatutaire = true;

    protected bool $diplomeNational = false;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypeFormation::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'typeFormation';
    }


    /**
     * @return string
     */
    public function getLibelleCourt(): ?string
    {
        return $this->libelleCourt;
    }



    /**
     * @param string $libelleCourt
     */
    public function setLibelleCourt(?string $libelleCourt): void
    {
        $this->libelleCourt = $libelleCourt;
    }



    /**
     * @return string
     */
    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }



    /**
     * @param string $libelleLong
     */
    public function setLibelleLong(?string $libelleLong): void
    {
        $this->libelleLong = $libelleLong;
    }



    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }



    /**
     * @return GroupeTypeFormation
     */
    public function getGroupe(): ?GroupeTypeFormation
    {
        return $this->groupe;
    }



    /**
     * @param GroupeTypeFormation $groupe
     */
    public function setGroupe(?GroupeTypeFormation $groupe): void
    {
        $this->groupe = $groupe;
    }



    /**
     * @return bool
     */
    public function isServiceStatutaire(): bool
    {
        return $this->serviceStatutaire;
    }



    /**
     * @param bool $serviceStatutaire
     */
    public function setServiceStatutaire(bool $serviceStatutaire): void
    {
        $this->serviceStatutaire = $serviceStatutaire;
    }



    public function isDiplomeNational(): bool
    {
        return $this->diplomeNational;
    }



    public function setDiplomeNational(bool $diplomeNational): TypeFormation
    {
        $this->diplomeNational = $diplomeNational;

        return $this;
    }



    public function __toString(): string
    {
        return $this->getLibelleLong();
    }

    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     *
     * @return TypeFormation
     */
    public function newEntity(): TypeFormation
    {
        $entity = parent::newEntity();
        // toutes les entités créées ont OSE pour source!!
        $entity->setSource($this->getServiceSource()->getOse());

        return $entity;
    }
}
