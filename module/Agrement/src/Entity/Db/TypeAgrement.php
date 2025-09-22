<?php

namespace Agrement\Entity\Db;

use Application\Provider\Privilege\Privileges;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * TypeAgrement
 */
class TypeAgrement implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    const CODE_CONSEIL_RESTREINT  = 'CONSEIL_RESTREINT';
    const CODE_CONSEIL_ACADEMIQUE = 'CONSEIL_ACADEMIQUE';

    const CONSEIL_RESTREINT_ID  = 1;
    const CONSEIL_ACADEMIQUE_ID = 2;

    static public $codes = [
        self::CODE_CONSEIL_RESTREINT,
        self::CODE_CONSEIL_ACADEMIQUE,
    ];

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var integer
     */
    private $id;



    /**
     * Set code
     *
     * @param string $code
     *
     * @return TypeAgrement
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
     * @return TypeAgrement
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



    /**
     * Libellé de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }



    /**
     * Libellé de cet objet.
     *
     * @param $avecArticle boolean Inclure l'article défini (utile pour inclure le libellé dans une phrase)
     * @param $deLe        boolean Activer la formulation "du"/"de l'" ou non
     *
     * @return string
     * @todo Gérer le masculin/féminin...
     */
    public function toString($avecArticle = false, $deLe = false)
    {
        $template = ($avecArticle ? ($deLe ? "du %s" : "le %s") : "%s");

        return sprintf($template, $this->getLibelle());
    }



    /**
     * Retourne le privilège de visualisation
     *
     * @return string
     */
    public function getPrivilegeVisualisation()
    {
        switch ($this->getCode()) {
            case TypeAgrement::CODE_CONSEIL_ACADEMIQUE:
                return Privileges::AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION;

            case TypeAgrement::CODE_CONSEIL_RESTREINT:
                return Privileges::AGREMENT_CONSEIL_RESTREINT_VISUALISATION;
        }
        throw new \LogicException('Aucun privilège n\'est reconnu pour ce type d\'agrément');
    }



    /**
     * Retourne le privilège d'édition
     *
     *
     * @return string
     */
    public function getPrivilegeEdition()
    {
        switch ($this->getCode()) {
            case TypeAgrement::CODE_CONSEIL_ACADEMIQUE:
                return Privileges::AGREMENT_CONSEIL_ACADEMIQUE_EDITION;

            case TypeAgrement::CODE_CONSEIL_RESTREINT:
                return Privileges::AGREMENT_CONSEIL_RESTREINT_EDITION;
        }
        throw new \LogicException('Aucun privilège n\'est reconnu pour ce type d\'agrément');
    }



    /**
     * Retourne le privilège de suppression
     *
     *
     * @return string
     */
    public function getPrivilegeSuppression()
    {
        switch ($this->getCode()) {
            case TypeAgrement::CODE_CONSEIL_ACADEMIQUE:
                return Privileges::AGREMENT_CONSEIL_ACADEMIQUE_SUPPRESSION;

            case TypeAgrement::CODE_CONSEIL_RESTREINT:
                return Privileges::AGREMENT_CONSEIL_RESTREINT_SUPPRESSION;
        }
        throw new \LogicException('Aucun privilège n\'est reconnu pour ce type d\'agrément');
    }



    public function getWorkflowEtapeCode(): string
    {
        return strtolower($this->getCode());
    }



    /**
     * Intercepte les appels de méthodes de la forme "isXxxxxx" où Xxxxxx est un
     * code de type d'agrément.
     *
     * @param string $name Ex: isConseilRestreint, isConseilAcademique
     * @param araay  $arguments
     *
     * @throws \BadMethodCallException
     */
    public function __call($name, $arguments)
    {
        if (substr($name, 0, $len = 2) === 'is') {
            $code = substr($name, $len);
            $f    = new \Laminas\Filter\Word\CamelCaseToUnderscore();
            $code = strtoupper($f->filter($code));
            if (in_array($code, static::$codes)) {
                return $this->getCode() === $code;
            }
        }

        throw new \BadMethodCallException("Méthode inconnue : $name");
    }
}
