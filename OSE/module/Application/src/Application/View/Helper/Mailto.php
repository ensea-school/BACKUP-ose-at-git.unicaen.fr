<?php

namespace Application\View\Helper;
        
use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\Utilisateur;

/**
 * Description of Mailto
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Mailto extends AbstractHelper
{
    /**
     * 
     * @param Utilisateur $utilisateur
     * @param string $title
     * @param string $subject
     * @param string $body
     * @return string
     */
    public function __invoke(Utilisateur $utilisateur, $title = null, $subject = null, $body = null)
    {
        return sprintf('<a title="%s" href="mailto:%s?subject=%s&body=%s">%s</a>',
                $title ?: "Cliquez sur ce lien pour rédiger un mail à " . $utilisateur,
                $utilisateur->getEmail(),
                $subject, 
                $body,
                (string) $utilisateur);
    }
}