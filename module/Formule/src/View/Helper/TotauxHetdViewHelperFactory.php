<?php

namespace Formule\View\Helper;

use Application\Constants;
use Psr\Container\ContainerInterface;


/**
 * Description of TotauxHetdViewHelperFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class TotauxHetdViewHelperFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): TotauxHetdViewHelper
    {
        $viewHelper = new TotauxHetdViewHelper();
        $viewHelper->setEntityManager($container->get(Constants::BDD));

        return $viewHelper;
    }
}