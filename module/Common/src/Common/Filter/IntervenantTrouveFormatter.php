<?php

namespace Common\Filter;

use Zend\Filter\AbstractFilter;
use Application\Entity\Db\IntervenantInterface;
use stdClass;
use Common\Constants;

/**
 * Formatte un intervenant pour le transmettre à l'élément de formulaire SearchAndSelect.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see \UnicaenApp\Form\Element\SearchAndSelect
 */
class IntervenantTrouveFormatter extends AbstractFilter
{
    /**
     * @var NomCompletFormatter
     */
    protected $nomCompletFormatter;
    
    /**
     * Constructeur.
     * 
     * @param IntervenantInterface|stdClass|array $intervenant
     */
    public function __construct()
    {
        $this->nomCompletFormatter = new NomCompletFormatter(true, false, true);
    }
    
    /**
     * Returns the result of filtering $value
     *
     * @param IntervenantInterface|stdClass|array $value
     * @return string
     */
    public function filter($value)
    {
        // normalisation
        if ($value instanceof IntervenantInterface) {
            /* @var $value IntervenantInterface */
            $id        = $value->getSourceCode();
            $label     = $this->nomCompletFormatter->filter($value);
            $dateNaiss = $value->getDateNaissanceToString();
            $feminin   = $value->estUneFemme();
            $affectat  = $value->getAffectationsToString();
        }
        else if ($value instanceof \stdClass) {
            foreach (array('sourceCode', 'dateNaissance', 'estUneFemme', 'affectation') as $prop) {
                if (!isset($value->$prop)) {
                    throw new \Common\Exception\LogicException("L'objet à formatter doit posséder l'attribut public '$prop'.");
                }
            }
            $id        = $value->sourceCode;
            $label     = $this->nomCompletFormatter->filter($value);
            $dateNaiss = $value->dateNaissance;
            $feminin   = $value->estUneFemme();
            $affectat  = $value->affectation;
        }
        else if (is_array($value)) {
            foreach (array('SOURCE_CODE', 'DATE_NAISSANCE', 'EST_UNE_FEMME', 'AFFECTATION') as $prop) {
                if (!array_key_exists($prop, $value)) {
                    throw new \Common\Exception\LogicException("Le tableau à formatter doit posséder la clé '$prop'.");
                }
            }
            $id        = $value['SOURCE_CODE'];
            $label     = $this->nomCompletFormatter->filter($value);
            $dateNaiss = $value['DATE_NAISSANCE'];
            $feminin   = $value['EST_UNE_FEMME'];
            $affectat  = $value['AFFECTATION'];
        }
        else {
            throw new \Common\Exception\LogicException("L'objet à formatter n'est pas d'un type supporté.");
        }
        
        $extra  = sprintf("(né%s le %s, n°%s, %s)",
                $feminin ? 'e' : '',
                $dateNaiss instanceof \DateTime ? $dateNaiss->format(Constants::DATE_FORMAT) : $dateNaiss,
                $id ?: "Inconnu",
                $affectat ?: "Affectation introuvable");
        
        $result = array(
            'id'    => $id,
            'label' => $label,
            'extra' => $extra,
        );
        
	return $result;
    }
}