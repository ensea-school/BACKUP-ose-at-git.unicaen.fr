<?php
namespace Application\Form\Periode;

use Psr\Container\ContainerInterface;

/**
 * Description of PeriodeSaisieFormFactory
 *
 * @author Joriot Florian
 */
class PeriodeSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PeriodeSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new PeriodeSaisieForm();

        return $form;
    }
}