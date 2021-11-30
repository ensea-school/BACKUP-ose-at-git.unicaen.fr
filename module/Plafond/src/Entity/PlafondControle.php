<?php

namespace Plafond\Entity;

/**
 * Description of PlafondControle
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PlafondControle
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $perimetre;

    /**
     * @var string
     */
    private $etat;

    /**
     * @var bool
     */
    private $bloquant = false;

    /**
     * @var bool
     */
    private $depassement = false;

    /**
     * @var float
     */
    private $heures;

    /**
     * @var float
     */
    private $plafond;

    /**
     * @var float
     */
    private $derogation;



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @param int $id
     *
     * @return PlafondControle
     */
    public function setId(int $id): PlafondControle
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }



    /**
     * @param string $code
     *
     * @return PlafondControle
     */
    public function setCode(string $code): PlafondControle
    {
        $this->code = $code;

        return $this;
    }



    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return PlafondControle
     */
    public function setLibelle(string $libelle): PlafondControle
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }



    /**
     * @param string $message
     *
     * @return PlafondControle
     */
    public function setMessage(string $message): PlafondControle
    {
        $this->message = $message;

        return $this;
    }



    /**
     * @return string
     */
    public function getPerimetre(): string
    {
        return $this->perimetre;
    }



    /**
     * @param string $perimetre
     *
     * @return PlafondControle
     */
    public function setPerimetre(string $perimetre): PlafondControle
    {
        $this->perimetre = $perimetre;

        return $this;
    }



    /**
     * @return string
     */
    public function getEtat(): string
    {
        return $this->etat;
    }



    /**
     * @param string $etat
     *
     * @return PlafondControle
     */
    public function setEtat(string $etat): PlafondControle
    {
        $this->etat = $etat;

        return $this;
    }



    /**
     * @return bool
     */
    public function isBloquant(): bool
    {
        return $this->bloquant;
    }



    /**
     * @param bool $bloquant
     *
     * @return PlafondControle
     */
    public function setBloquant(bool $bloquant): PlafondControle
    {
        $this->bloquant = $bloquant;

        return $this;
    }



    /**
     * @return bool
     */
    public function isDepassement(): bool
    {
        return $this->depassement;
    }



    /**
     * @param bool $depassement
     *
     * @return PlafondControle
     */
    public function setDepassement(bool $depassement): PlafondControle
    {
        $this->depassement = $depassement;

        return $this;
    }



    /**
     * @return float
     */
    public function getHeures(): float
    {
        return $this->heures;
    }



    /**
     * @param float $heures
     *
     * @return PlafondControle
     */
    public function setHeures(float $heures): PlafondControle
    {
        $this->heures = $heures;

        return $this;
    }



    /**
     * @return float
     */
    public function getPlafond(): float
    {
        return $this->plafond;
    }



    /**
     * @param float $plafond
     *
     * @return PlafondControle
     */
    public function setPlafond(float $plafond): PlafondControle
    {
        $this->plafond = $plafond;

        return $this;
    }



    /**
     * @return float
     */
    public function getDerogation(): float
    {
        return $this->derogation;
    }



    /**
     * @param float $derogation
     *
     * @return PlafondControle
     */
    public function setDerogation(float $derogation): PlafondControle
    {
        $this->derogation = $derogation;

        return $this;
    }



    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        $errStr = 'Le plafond "%s" a été dépassé. Il est en effet de %s heures pour %s heures saisies.';

        return sprintf(
            $errStr,
            $this->getMessage(),
            floatToString($this->getPlafond()),
            floatToString($this->getHeures())
        );
    }



    public static function fromArray(array $a): PlafondControle
    {
        $pc = new PlafondControle();
        if (isset($a['ID'])) {
            $pc->setId((int)$a['ID']);
        }
        if (isset($a['CODE'])) {
            $pc->setCode($a['CODE']);
        }
        if (isset($a['LIBELLE'])) {
            $pc->setLibelle($a['LIBELLE']);
        }
        if (isset($a['MESSAGE'])) {
            $pc->setMessage($a['MESSAGE']);
        }
        if (isset($a['PERIMETRE'])) {
            $pc->setPerimetre($a['PERIMETRE']);
        }
        if (isset($a['ETAT'])) {
            $pc->setEtat($a['ETAT']);
        }
        if (isset($a['BLOQUANT'])) {
            $pc->setBloquant($a['BLOQUANT'] == '1');
        }
        if (isset($a['DEPASSEMENT'])) {
            $pc->setDepassement($a['DEPASSEMENT'] == '1');
        }
        if (isset($a['HEURES'])) {
            $pc->setHeures((float)$a['HEURES']);
        }
        if (isset($a['PLAFOND'])) {
            $pc->setPlafond((float)$a['PLAFOND']);
        }
        if (isset($a['DEROGATION'])) {
            $pc->setDerogation((float)$a['DEROGATION']);
        }

        return $pc;
    }
}