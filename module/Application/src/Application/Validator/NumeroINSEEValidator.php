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
        $iDepartement = $this->getDepartement();

        if ($iDepartement === 99) {
            $this->error(self::MSG_DEPT);

            return false; // département étranger
        }

        $iCodeDepartement = str_pad((string)$iDepartement, 3, '0', STR_PAD_LEFT);
        if ($this->departement->getCode() == $iCodeDepartement) return true;

        $ileDeFrance    = [78, 91, 92, 93, 94, 95];
        $anneeNaissance = (int)$this->dateNaissance->format('Y');

        if ($anneeNaissance <= 1968 && in_array($iDepartement, $ileDeFrance) && 75 === (int)$this->departement->getCode()) {
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



    private function isValidDepartementHorsFrance($value)
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





class NumeroINSEEValidatorOld extends NumeroINSEE
{
    use DepartementServiceAwareTrait;
    use CiviliteServiceAwareTrait;
    use PaysServiceAwareTrait;

    const MSG_CIVILITE = 'msgCivilite';
    const MSG_ANNEE    = 'msgAnnee';
    const MSG_MOIS     = 'msgMois';
    const MSG_DEPT     = 'msgDepartement';

    /**
     * @var ?int
     */
    protected $algerieId;

    /**
     * @var ?int
     */
    protected $franceId;



    public function __construct($options = null)
    {
        $this->messageTemplates = array_merge($this->messageTemplates, [
            self::MSG_CIVILITE => "Le numéro n'est pas cohérent avec la civilité saisie",
            self::MSG_ANNEE    => "Le numéro n'est pas cohérent avec l'année de naissance saisi",
            self::MSG_MOIS     => "Le numéro n'est pas cohérent avec le mois de naissance saisi",
            self::MSG_DEPT     => "Le numéro n'est pas cohérent avec le pays et l'éventuel département de naissance saisi",
        ]);

        $this->franceId  = $this->getServicePays()->getIdByLibelle('FRANCE');
        $this->algerieId = $this->getServicePays()->getIdByLibelle('ALGERIE');

        parent::__construct($options);
    }



    public function isValid($value, $context = null)
    {
        if (!parent::isValid($value)) {
            return false;
        }

        if (!$this->isValidSexe($value, $context)) {
            return false;
        }
        if (!$this->isValidAnnee($value, $context)) {
            return false;
        }
        if (!$this->isValidMois($value, $context)) {
            return false;
        }
        if (!$this->isValidDepartement($value, $context)) {
            return false;
        }

        return true;
    }



    private function isValidSexe($value, $context)
    {
        if (empty($context['civilite'])) {
            return true;
        }

        $civilite = $this->getServiceCivilite()->get((int)$context['civilite']);

        if ($civilite->estUneFemme()) {
            $sexes = [2, 4]; // femme, personne étrangère de sexe féminin en cours d'immatriculation en France
        } else {
            $sexes = [1, 3]; // homme, personne étrangère de sexe masculin en cours d'immatriculation en France
        }

        $sexe = (int)substr($value, 0, 1);

        if (!in_array($sexe, $sexes)) {
            $this->error(self::MSG_CIVILITE);

            return false;
        }

        return true;
    }



    private function isValidAnnee($value, $context)
    {
        if (empty($context['dateNaissance'])) {
            return true;
        }

        $dateNaissance = $context['dateNaissance'];
        [, , $annee] = explode('/', $dateNaissance);

        if (substr($annee, -2) !== substr($value, 1, 2)) {
            $this->error(self::MSG_ANNEE);

            return false;
        }

        return true;
    }



    private function isValidMois($value, $context)
    {
        if (empty($context['dateNaissance'])) {
            return true;
        }

        $dateNaissance = $context['dateNaissance'];
        [, $mois,] = explode('/', $dateNaissance);

        $moisInsee = (int)substr($value, 3, 2);

        if ($this->hasCodeDepartementEtranger($value)) {
            if ($moisInsee == 20 || $moisInsee == 99 || ($moisInsee > 30 && $moisInsee < 42) || ($moisInsee > 50 && $moisInsee < 99)) {
                return true;
            }
        }

        if ((int)$mois !== $moisInsee) {
            $this->error(self::MSG_MOIS);

            return false;
        }

        return true;
    }



    private function isValidDepartement($value, $context)
    {
        if (empty($context['paysNaissance'])) {
            return true;
        }

        $paysNaissance  = (int)$context['paysNaissance'];
        $estNeEnFrance  = $paysNaissance === $this->franceId;
        $estNeEnAlgerie = $paysNaissance === $this->algerieId;

        if ($estNeEnFrance) {
            // on doit avoir un code département français valide
            if (!$this->isValidDepartementFrance($value, $context)) {
                return false;
            }
        } elseif ($estNeEnAlgerie) {
            // on doit avoir un code département français valide
            if (!$this->isValidDepartementAlgerie($value, $context)) {
                return false;
            }
        } else {
            // on doit avoir un code pays étranger valide
            if (!$this->isValidDepartementHorsFrance($value)) {
                return false;
            }
        }

        return true;
    }



    private function isValidDepartementHorsFrance($value)
    {
        if (!$this->hasCodeDepartementEtranger($value)) {
            $this->error(self::MSG_DEPT);

            return false;
        }

        return true;
    }



    private function isValidDepartementFrance($value, $context)
    {
        if (empty($context['departementNaissance'])) {
            return true;
        }

        // Si on trouve un code de département en métropole ou outre-mer valide,
        // on vérifie qu'il est cohérent avec le code du département de naissance saisi
        if (
            ($d = $this->getDepartementEnMetropoleValide($value))
            ||
            ($d = $this->getDepartementOutreMerValide($value))
        ) {
            /* @var $departementNaissance Departement */
            $departementNaissance = $this->getServiceDepartement()->get($context['departementNaissance'], true);
            if ($d !== $departementNaissance->getCode()) {
                $return        = false;
                $dateNaissance = \DateTime::createFromFormat(Constants::DATE_FORMAT, $context['dateNaissance']);
                if ($dateNaissance) {
                    $anneeNaissance = (int)$dateNaissance->format('Y');
                    if ($anneeNaissance <= 1968) {
                        if ($departementNaissance->inIleDeFrance() && $d === '075') {
                            $return = true;
                        }
                    }
                }
                if (!$return) {
                    $this->error(self::MSG_DEPT);

                    return false;
                }
            }
        } // Sinon, le code département n'est pas valide
        else {
            $this->error(self::MSG_DEPT);

            return false;
        }

        return true;
    }



    /**
     *
     * @param string $value
     *
     * @return int|string|null
     */
    private function getDepartementEnMetropoleValide($value)
    {
        $departement = substr($value, 5, 2);

        if (is_numeric($departement)) {
            $d = (int)$departement;
            if (1 <= $d && $d <= 95) {
                return '0' . (string)$departement;
            }
        } else {
            if (in_array($departement, ["2A", "2B"])) {
                return '0' . $departement;
            }
        }

        return null;
    }



    /**
     *
     * @param string $value
     *
     * @return int|null
     */
    private function getDepartementOutreMerValide($value)
    {
        $departement = substr($value, 5, 3);

        if (is_numeric($departement)) {
            $d = (int)$departement;
            if (970 <= $d && $d <= 989) {
                return $departement;
            }
        }

        return null;
    }



    private function isValidDepartementAlgerie($value, $context)
    {
        $departement = substr($value, 5, 2);

        if (is_numeric($departement)) {
            $d = (int)$departement;
            if (in_array($d, [91, 92, 93, 94, 99])) {
                return '0' . (string)$departement;
            }
        }

        return null;
    }



    /**
     * Teste si un numéro INSEE possède le code département de naissance associé à un pays étranger.
     *
     * @param string Numéro INSEE à tester
     *
     * @return bool
     */
    static public function hasCodeDepartementEtranger($value)
    {
        $departement = substr($value, 5, 2);

        // le code département doit être "99" pour un pays étranger
        return $departement === '99';
    }
}
