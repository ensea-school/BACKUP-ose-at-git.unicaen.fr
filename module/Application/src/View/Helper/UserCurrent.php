<?php

namespace Application\View\Helper;

use UnicaenAuth\View\Helper\UserAbstract;

/**
 * Aide de vue affichant toutes les infos concernant l'utilisateur courant.
 * C'est à dire :
 *  - "Aucun" + lien de connexion OU BIEN nom de l'utilisateur connecté + lien de déconnexion
 *  - profil de l'utilisateur connecté
 *  - infos administratives sur l'utilisateur
 *
 */
class UserCurrent extends UserAbstract
{

    /**
     * Point d'entrée.
     *
     * @param boolean $affectationFineSiDispo Indique s'il faut prendre en compte l'affectation
     *                                        plus fine (ucbnSousStructure) si elle existe, à la place de l'affectation standard (niveau 2)
     *
     * @return self
     */
    public function __invoke($affectationFineSiDispo = false)
    {

        return $this;
    }



    /**
     * Retourne le code HTML généré par cette aide de vue.
     *
     * @return string
     */
    public function __toString()
    {
        $id               = 'user-current-info';
        $userStatusHelper = $this->getView()->plugin('userStatus');
        /* @var $userStatusHelper \UnicaenAuth\View\Helper\UserStatus */
        $status                = $userStatusHelper(false);
        $userProfileSelectable = true;

        if ($this->getIdentity()) {
            if ($userProfileSelectable) {
                // DS : cas où aucun rôle n'est sélectionné, on affiche le rôle "user"
                $role   = $this->getUserContext()->getSelectedIdentityRole() ?: $this->getUserContext()->getIdentityRole('user');
                $status .= sprintf(", <small>%s</small>", !method_exists($role, '__toString') ? $role->getRoleId() : $role);
            }

            $userProfileHelper = $this->getView()->plugin('userProfile');
            /* @var $userProfileHelper \UnicaenAuth\View\Helper\UserProfile */
            $userProfileHelper->setUserProfileSelectable($userProfileSelectable);

            $content = $userProfileHelper;
        } else {
            $status = "Vous n'êtes pas connecté(e)";
            $content = _("Aucun");
        }

        $content = htmlspecialchars(preg_replace('/\r\n|\n|\r/', '', $content));

        $title = _("Utilisateur connecté à l'application");

        $out = <<<EOS
<a class="navbar-link" 
   id="$id" 
   title="$title" 
   data-bs-placement="bottom" 
   data-bs-toggle="popover" 
   data-bs-html="true" 
   data-bs-content="$content" 
   href="#">$status<span class="caret"></span></a>
EOS;
        $out .= PHP_EOL;

        $js = <<<EOS
$(function() {
    $("#$id").popover({ html: true, sanitize: false, container: '#navbar' });
});
EOS;
        $this->getView()->plugin('inlineScript')->offsetSetScript(1000, $js);

        return $out;
    }
}
