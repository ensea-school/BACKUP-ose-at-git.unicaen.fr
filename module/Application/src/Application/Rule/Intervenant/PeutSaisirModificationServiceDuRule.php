<?php

namespace Application\Rule\Intervenant;

use Application\Rule\AbstractRule;
use Application\Traits\IntervenantAwareTrait;
use Application\Traits\StructureAwareTrait;

/**
 * Règle métier déterminant si un intervenant peut faire l'objet d'une modification de service dû.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirModificationServiceDuRule extends AbstractRule
{
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    
    const MESSAGE_STATUT      = 'statut';
    const MESSAGE_AFFECTATION = 'affectation';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = array(
        self::MESSAGE_STATUT      => "L'intervenant %value% n'est pas un intervenant permanent.",
        self::MESSAGE_AFFECTATION => "Le statut de l'intervenant ne nécessite pas le type d'agrément '%value%'.",
    );
    
    public function execute()
    {
        if (!$this->getIntervenant()->estPermanent()) {
            $this->message(self::MESSAGE_STATUT, $this->getIntervenant());
            return false;
        }
        
        if ($this->getStructure()) {
            $estAffecte = $this->getServiceLocator()->get('EstAffecteRule')
                    ->setIntervenant($this->getIntervenant())
                    ->setStructure($this->getStructure());
            if (!$estAffecte->execute()) {
                $this->message(self::MESSAGE_AFFECTATION, $estAffecte->getMessage());
                return false;
            }
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
}