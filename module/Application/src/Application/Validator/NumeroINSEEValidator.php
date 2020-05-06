<?php

namespace Application\Validator;

use Application\Constants;
use Application\Entity\Db\Civilite;
use Application\Entity\Db\Departement;
use Application\Entity\Db\Pays;
use Application\Service\Traits\CiviliteServiceAwareTrait;
use Application\Service\Traits\DepartementServiceAwareTrait;
use Application\Service\Traits\PaysServiceAwareTrait;
use UnicaenApp\Validator\NumeroINSEE;

/**
 * Validateur de numéro INSEE avec contrôles supplémentaires de cohérence avec
 * les éventuelles informations de contexte suivantes :
 * - civilité,
 * - date de naissance : année et mois,
 * - pays de naissance : France ou pas,
 * - département de naissance (dispo ssi pays de naissance = France).
 *
 * @see http://fr.wikipedia.org/wiki/Num%C3%A9ro_de_s%C3%A9curit%C3%A9_sociale_en_France
 */
class NumeroINSEEValidator extends NumeroINSEE
{
    use DepartementServiceAwareTrait;
    use CiviliteServiceAwareTrait;
    use PaysServiceAwareTrait;

    const MSG_CIVILITE = 'msgCivilite';
    const MSG_ANNEE    = 'msgAnnee';
    const MSG_MOIS     = 'msgMois';
    const MSG_DEPT     = 'msgDepartement';

    /**
     * @var string
     */
    protected $value;

    /**
     * @var bool
     */
    protected $provisoire = false;

    /**
     * @var Civilite|null
     */
    protected $civilite;

    /**
     * @var \DateTime|null
     */
    protected $dateNaissance;

    /**
     * @var Pays|null
     */
    protected $pays;

    /**
     * @var Departement|null
     */
    protected $departement;



    public function __construct($options = null)
    {
        $this->messageTemplates = array_merge($this->messageTemplates, [
            self::MSG_CIVILITE => "Le numéro n'est pas cohérent avec la civilité saisie",
            self::MSG_ANNEE    => "Le numéro n'est pas cohérent avec l'année de naissance saisi",
            self::MSG_MOIS     => "Le numéro n'est pas cohérent avec le mois de naissance saisi",
            self::MSG_DEPT     => "Le numéro n'est pas cohérent avec le pays et l'éventuel département de naissance saisi",
        ]);

        parent::__construct($options);
    }



    public function isValid($value, $context = null)
    {
        if (!parent::isValid($value)) {
            return false;
        }

        $this->value = $value;

        $this->provisoire = $this->getProvisoire();

        $this->civilite = (!empty($context['civilite'])) ?
            $this->getServiceCivilite()->get((int)$context['civilite']) : null;

        if ($this->civilite && !$this->isValidCivilite()) return false;

        $this->dateNaissance = (!empty($context['dateNaissance'])) ?
            \DateTime::createFromFormat(Constants::DATE_FORMAT, $context['dateNaissance']) : null;

        if ($this->dateNaissance && !$this->isValidDateNaissance()) return false;

        $this->pays = (!empty($context['paysNaissance'])) ?
            $this->getServicePays()->get((int)$context['paysNaissance']) : null;

        $this->departement = (!empty($context['departementNaissance'])) ?
            $this->getServiceDepartement()->get((int)$context['departementNaissance']) : null;

        if ($this->departement && !$this->isValidLieuNaissance()) return false;

        return true;
    }



    private function isValidCivilite(): bool
    {
        if ($this->civilite->estUneFemme()) {
            $sexes = [2, 4, 8]; // femme, personne étrangère de sexe féminin ou en cours d'immatriculation en France
        } else {
            $sexes = [1, 3, 7]; // homme, personne étrangère de sexe masculin ou en cours d'immatriculation en France
        }

        $sexe = (int)substr($this->value, 0, 1);

        if (!in_array($sexe, $sexes)) {
            $this->error(self::MSG_CIVILITE);

            return false;
        }

        return true;
    }



    private function isValidDateNaissance(): bool
    {
        $iAnnee = (int)substr($this->value, 1, 2);
        $iMois  = (int)substr($this->value, 3, 2);

        $mois  = (int)$this->dateNaissance->format('m');
        $annee = (int)$this->dateNaissance->format('y');

        if ($iAnnee !== $annee) {
            $this->error(self::MSG_ANNEE);

            return false;
        }

        if ($this->getDepartement() === 99) {
            if ($iMois == 20 || $iMois == 99 || ($iMois > 30 && $iMois < 42) || ($iMois > 50 && $iMois < 99)) {
                return true;
            }
        }

        if ($iMois !== $mois) {
            $this->error(self::MSG_MOIS);

            return false;
        }

        return true;
    }



    private function isValidLieuNaissance(): bool
    {
        $isFrance  = $this->getServicePays()->isFrance($this->pays);
        $isAlgerie = false;
        $isMaroc   = false;
        $isTunisie = false;
        if (!$isFrance) {
            $isAlgerie = $this->getServicePays()->isAlgerie($this->pays);
        }
        if (!$isAlgerie) {
            $isMaroc = $this->getServicePays()->isMaroc($this->pays);
        }
        if (!$isMaroc) {
            $isTunisie = $this->getServicePays()->isTunisie($this->pays);
        }

        if ($isFrance) {
            return $this->isValidDepartementFrance();
        } elseif ($isAlgerie) {
            return $this->isValidDepartementAlgerie();
        } elseif ($isMaroc) {
            return $this->isValidDepartementMaroc();
        } elseif ($isTunisie) {
            return $this->isValidDepartementTunisie();
        } else {
            return $this->isValidDepartementHorsFrance();
        }
    }



    private function isValidDepartementFrance()
    {
        /* Département du numéro INSEE */
        $iDepartement = strtoupper(str_pad((string)$this->getDepartement(), 3, '0', STR_PAD_LEFT));

        /* Code du département issu du dossier */
        $dDepartement = strtoupper(str_pad((string)$this->departement->getCode(), 3, '0', STR_PAD_LEFT));

        /* Année de naissance en int */
        $anneeNaissance = (int)$this->dateNaissance->format('Y');

        /* Liste des départements d'Île de France */
        $ileDeFrance = ['075', '078', '091', '092', '093', '094', '095'];


        if ($iDepartement == '099') {
            $this->error(self::MSG_DEPT);

            return false; // département étranger
        }

        if ($dDepartement == $iDepartement) return true; // Impec

        if ($iDepartement == '020') {
            if ($dDepartement == '02A' || $dDepartement == '02B') return true; // Corses nés avant 1976 => ancien département unique
        }

        if ($anneeNaissance <= 1968 && in_array($iDepartement, $ileDeFrance)) {
            return true; // Pour les personnes nées en seine et oise, département disparu
        }

        $this->error(self::MSG_DEPT);

        return false;
    }



    private function isValidDepartementAlgerie()
    {
        $iDepartement = $this->getDepartement();

        $departements = [91, 92, 93, 94, 99];

        if (in_array($iDepartement, $departements)) {
            return true;
        }

        $this->error(self::MSG_DEPT);

        return false;
    }



    private function isValidDepartementMaroc()
    {
        $iDepartement = $this->getDepartement();

        $departements = [95, 99];

        if (in_array($iDepartement, $departements)) {
            return true;
        }

        $this->error(self::MSG_DEPT);

        return false;
    }



    private function isValidDepartementTunisie()
    {
        $iDepartement = $this->getDepartement();

        $departements = [96, 99];

        if (in_array($iDepartement, $departements)) {
            return true;
        }

        $this->error(self::MSG_DEPT);

        return false;
    }



    private function isValidDepartementHorsFrance()
    {
        if (!$this->getDepartement() === 99) {
            $this->error(self::MSG_DEPT);

            return false;
        }

        return true;
    }



    private function getDepartement()
    {
        $iDepartement = substr(strtoupper($this->value), 5, 2);
        if ($iDepartement == '2A' || $iDepartement == '2B') {
            return $iDepartement; // corse
        }
        if ($iDepartement == '99') {
            return 99; // étranger
        }
        if ($iDepartement == '97' || $iDepartement == '98') {
            $iDepartement = substr(strtoupper($this->value), 5, 3);

            return (int)$iDepartement;
        }

        return (int)$iDepartement;
    }
}