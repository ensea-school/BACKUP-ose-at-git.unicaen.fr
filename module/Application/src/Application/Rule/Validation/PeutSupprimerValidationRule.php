<?php

namespace Application\Rule\Validation;

use Application\Entity\Db\Validation;

/**
 * Description of PeutSupprimerValidationRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSupprimerValidationRule extends \Application\Rule\AbstractRule
{
    const MESSAGE_VH_AVEC_CONTRAT = 'messageVhAvecContrat';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::MESSAGE_VH_AVEC_CONTRAT => "Un volume horaire au moins est rattaché à un contrat/avenant.",
    ];

    /**
     * @var Validation
     */
    private $validation;

    /**
     *
     * @param \Application\Entity\Db\Validation $validation
     */
    public function __construct(Validation $validation)
    {
        parent::__construct();
        $this->validation = $validation;
    }

    /**
     *
     * @return boolean
     */
    public function execute()
    {
        foreach ($this->validation->getVolumeHoraire() as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraire */
            if (count($vh->getContrat())) {
                $this->message(self::MESSAGE_VH_AVEC_CONTRAT);
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