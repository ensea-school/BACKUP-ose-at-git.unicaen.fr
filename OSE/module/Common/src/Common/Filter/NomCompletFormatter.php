<?php

namespace Common\Filter;

use Zend\Filter\AbstractFilter;
use UnicaenApp\Entity\Ldap\People;
use Application\Entity\Db\IntervenantInterface;
use Application\Entity\Db\Utilisateur;
use Application\Entity\Db\Personnel;

/**
 * Formatte le nom complet d'un individu (nom usuel, patronymique, etc.)
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NomCompletFormatter extends AbstractFilter
{
    protected $nomEnMajuscule = true;
    protected $avecCivilite   = false;
    protected $avecNomPatro   = false;
    protected $prenomDabord   = false;
    
    /**
     * Constructeur.
     * 
     * @param bool $nomEnMajuscule
     * @param bool $avecCivilite
     * @param bool $avecNomPatro
     * @param bool $prenomDabord
     */
    public function __construct($nomEnMajuscule = true, $avecCivilite = false, $avecNomPatro = false, $prenomDabord = false)
    {
        $this->nomEnMajuscule = $nomEnMajuscule;
        $this->avecCivilite   = $avecCivilite;
        $this->avecNomPatro   = $avecNomPatro;
        $this->prenomDabord   = $prenomDabord;
    }
    
    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
    {
        // normalisation
        if ($value instanceof IntervenantInterface) {
            /* @var $value IntervenantInterface */
            $nomUsuel = $value->getNomUsuel();
            $nomPatro = $value->getNomPatronymique();
            $prenom   = $value->getPrenom();
            $civilite = $value->getCiviliteToString();
        }
        else if ($value instanceof People) {
            /* @var $value People */
            $nomUsuel = current((array) $value->getSn(true));
            $nomPatro = $value->getNomPatronymique();
            $prenom   = $value->getGivenName();
            $civilite = $value->getSupannCivilite();
        }
        else if ($value instanceof Utilisateur) {
            /* @var $value Utilisateur */
            $nomUsuel = $value->getDisplayName();
            $nomPatro = $value->getDisplayName();
            $prenom   = '';
            $civilite = '';
        }
        else if ($value instanceof Personnel) {
            /* @var $value Personnel */
            $nomUsuel = $value->getNomUsuel();
            $nomPatro = $value->getNomPatronymique();
            $prenom   = $value->getPrenom();
            $civilite = $value->getCiviliteToString();
        }
        else if ($value instanceof \stdClass) {
            foreach (array('nomUsuel', 'nomPatronymique', 'prenom', 'civilite') as $prop) {
                if (!isset($value->$prop)) {
                    throw new \Common\Exception\LogicException("L'objet à formatter doit posséder l'attribut public '$prop'.");
                }
            }
            $nomUsuel = $value->nomUsuel;
            $nomPatro = $value->nomPatronymique;
            $prenom   = $value->prenom;
            $civilite = $value->civilite;
        }
        else if (is_array($value)) {
            foreach (array('NOM_USUEL', 'NOM_PATRONYMIQUE', 'PRENOM', 'CIVILITE') as $prop) {
                if (!array_key_exists($prop, $value)) {
                    throw new \Common\Exception\LogicException("Le tableau à formatter doit posséder la clé '$prop'.");
                }
            }
            $nomUsuel = $value['NOM_USUEL'];
            $nomPatro = $value['NOM_PATRONYMIQUE'];
            $prenom   = $value['PRENOM'];
            $civilite = $value['CIVILITE'];
        }
        else {
            throw new \Common\Exception\LogicException("L'objet à formatter n'est pas d'un type supporté.");
        }
        
        $nomUsuel = ucfirst($this->nomEnMajuscule ? strtoupper($nomUsuel) : $nomUsuel);
        $nomPatro = ucfirst($this->nomEnMajuscule ? strtoupper($nomPatro) : $nomPatro);
        $prenom   = ucfirst($prenom);
        $civilite = $this->avecCivilite ? $civilite : null;
        
        $parts = array(
            $this->prenomDabord ? "$prenom $nomUsuel" : "$nomUsuel $prenom",
            $civilite,
            $this->avecNomPatro && $nomPatro != $nomUsuel ? "née $nomPatro" : null,
        );
        
        $result = implode(', ', array_filter($parts));
        
	return $result;
    }
}