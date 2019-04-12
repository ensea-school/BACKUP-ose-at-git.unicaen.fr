<?php

namespace Application\Validator;

use Application\Service\Traits\CiviliteServiceAwareTrait;
use Application\Service\Traits\DepartementServiceAwareTrait;
use Application\Service\Traits\PaysServiceAwareTrait;
use LogicException;
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

        if (!isset($options['serviceDepartement'])) {
            throw new LogicException("Service Département non fourni.");
        }

        if (!isset($options['serviceCivilite'])) {
            throw new LogicException("Service Civilité non fourni.");
        }

        $this->setServiceDepartement($options['serviceDepartement']);
        $this->setServiceCivilite($options['serviceCivilite']);

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
        list(, , $annee) = explode('/', $dateNaissance);

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
        list(, $mois,) = explode('/', $dateNaissance);

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
        }
        if ($estNeEnAlgerie) {
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
        $departementNaissance = $this->getServiceDepartement()->get($context['departementNaissance'], true);
        /* @var $departementNaissance DepartementEntity */

        // Si on trouve un code de département en métropole ou outre-mer valide,
        // on vérifie qu'il est cohérent avec le code du département de naissance saisi
        if (
            ($d = $this->getDepartementEnMetropoleValide($value))
            ||
            ($d = $this->getDepartementOutreMerValide($value))
        ) {
            if ($d !== $departementNaissance->getCode()) {
                $this->error(self::MSG_DEPT);

                return false;
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
            if (in_array($d, [91,92,93,94,99])) {
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