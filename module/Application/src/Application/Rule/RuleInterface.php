<?php

namespace Application\Rule;

/**
 * Description of RuleInterface
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface RuleInterface
{
    public function execute();
    public function isRelevant();
    public function getMessage();
}