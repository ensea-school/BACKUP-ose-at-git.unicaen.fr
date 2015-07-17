<?php

namespace Application\Entity\Db;

/**
 * TypeIntervenant
 */
class TypeIntervenant implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    public $classToCode = [
        'Application\Entity\Db\IntervenantPermanent' => 'P',
        'Application\Entity\Db\IntervenantExterieur' => 'E',
    ];

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var integer
     */
    protected $id;



    /**
     * Set code
     *
     * @param string $code
     *
     * @return TypeIntervenant
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }



    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return TypeIntervenant
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
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



    CONST CODE_PERMANENT = 'P';
    CONST CODE_EXTERIEUR = 'E';
    CONST TYPE_PERMANENT = 1;
    CONST TYPE_EXTERIEUR = 2;



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }
}
