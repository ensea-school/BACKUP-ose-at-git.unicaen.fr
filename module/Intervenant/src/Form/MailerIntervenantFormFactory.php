<?php

namespace Intervenant\Form;

use Psr\Container\ContainerInterface;


/**
 * Description of MailerIntervenantForm
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class MailerIntervenantFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     *
     * @return NoteSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MailerIntervenantForm
    {
        $form = new MailerIntervenantForm();


        return $form;
    }
}