<?php

namespace Administration\Assertion;

use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Unicaen\Framework\Authorize\Authorize;
use Unicaen\Framework\Navigation\Navigation;


/**
 * Description of GestionAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class GestionAssertion extends AbstractAssertion
{
    use ContextServiceAwareTrait;

    public function __construct(
        Authorize $authorize,
        private readonly Navigation $navigation,
    )
    {
        parent::__construct($authorize);
    }



    protected function assertController(string $controller, ?string $action): bool
    {
        if ($this->getServiceContext()->getIntervenant()) {
            return false;
        }

        $adminPage = $this->navigation->home->getPage('gestion');
        $adminSubPages = $adminPage->getPages();
        foreach( $adminSubPages as $adminSubPage ) {
            $subsubPages = $adminSubPage->getPages();
            foreach( $subsubPages as $subsubPage ) {
                if ($subsubPage->isVisible()){
                    return true;
                }
            }
        }
        return false;
    }
}