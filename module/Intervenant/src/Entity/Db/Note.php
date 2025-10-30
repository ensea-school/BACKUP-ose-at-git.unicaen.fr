<?php

namespace Intervenant\Entity\Db;


use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Description of Statut
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class Note implements ResourceInterface, HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $contenu;

    /**
     * @var Intervenant
     */

    private $intervenant;

    /**
     * @var TypeNote
     */
    private $type;



    public function __construct()
    {

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
     *
     * @return Note
     */
    public function setId(int $id): Note
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return string
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return Note
     */
    public function setLibelle(string $libelle): Note
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return string
     */
    public function getContenu(): ?string
    {
        return $this->contenu;
    }



    /**
     * @param string $contenu
     *
     * @return Note
     */
    public function setContenu(string $contenu): Note
    {
        $this->contenu = $contenu;

        return $this;
    }



    /**
     * @return Intervenant
     */
    public function getIntervenant(): ?Intervenant
    {
        return $this->intervenant;
    }



    /**
     * @param Intervenant $intervenant
     *
     * @return Note
     */
    public function setIntervenant(Intervenant $intervenant): Note
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     * @return TypeNote
     */
    public function getType(): ?TypeNote
    {
        return $this->type;
    }



    /**
     * @param TypeNote $type
     *
     * @return Note
     */
    public function setType(TypeNote $type): Note
    {
        $this->type = $type;

        return $this;
    }



    public function __toString(): string
    {
        return $this->getLibelle();
    }



    public function getResourceId(): string
    {
        return self::class;
    }
}
