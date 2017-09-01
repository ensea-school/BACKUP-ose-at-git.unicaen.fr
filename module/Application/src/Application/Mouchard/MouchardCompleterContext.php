<?php

namespace Application\Mouchard;

use Application\Service\Traits\ContextAwareTrait;
use UnicaenApp\Mouchard\MouchardCompleterInterface;
use UnicaenApp\Mouchard\MouchardMessage;

/**
 * class MouchardCompleterContext
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 * @package UnicaenApp\Mouchard
 */
class MouchardCompleterContext implements MouchardCompleterInterface
{
    use ContextAwareTrait;

    /**
     * @return $this
     */
    public function complete(MouchardMessage $message)
    {
        $message->setParam('Année universitaire', $this->getServiceContext()->getAnnee() );
        if ($structure = $this->getServiceContext()->getStructure()){
            $message->setParam('Composante', (string)$structure);
        }

        return $this;
    }

}