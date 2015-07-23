<?php

namespace Application\Entity\Db;
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
            $f    = new \Zend\Filter\Word\CamelCaseToUnderscore();
            $code = strtoupper($f->filter($code));
            if (in_array($code, static::$codes)) {
                return $this->getCode() === $code;
            }
        }

        throw new \BadMethodCallException("Méthode inconnue : $name");
    }
}
