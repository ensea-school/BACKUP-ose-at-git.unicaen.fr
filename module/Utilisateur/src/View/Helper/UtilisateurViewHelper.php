<?php

namespace Utilisateur\View\Helper;

use Laminas\View\Helper\AbstractHtmlElement;
use Utilisateur\Entity\Db\Utilisateur;

/**
 * Description of UtilisateurViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class UtilisateurViewHelper extends AbstractHtmlElement
{
    /**
     *
     * @param Utilisateur $utilisateur
     * @param string      $title
     * @param string      $subject
     * @param string      $body
     *
     * @return string
     */
    public function __invoke(Utilisateur $utilisateur, $title = null, $subject = null, $body = null)
    {
        return sprintf('<a title="%s" href="mailto:%s?subject=%s&body=%s">%s</a>',
            $title ?: "Cliquez sur ce lien pour rédiger un mail à " . $utilisateur,
            $utilisateur->getEmail(),
            $subject,
            $body,
            (string)$utilisateur);
    }
}