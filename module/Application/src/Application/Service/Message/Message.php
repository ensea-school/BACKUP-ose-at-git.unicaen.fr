<?php
/**
 * Created by PhpStorm.
 * User: gauthierb
 * Date: 16/07/15
 * Time: 11:03
 */

namespace Application\Service\Message;


use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Exception\LogicException;

class Message
{
    const ROLE_ID_DEFAULT = 'DEFAULT';

    private $contentsByRoleId = [];

    /**
     * @param array $contents
     */
    public function __construct(array $contents)
    {
        $this->loadContents($contents);
    }

    /**
     * @param array $contents
     * @return $this
     */
    private function loadContents(array $contents)
    {
        foreach ($contents as $roleId => $content) {
            if (! is_string($roleId)) {
                $roleId = self::ROLE_ID_DEFAULT;
            }
            $this->setContentForRoleId($content, $roleId);
        }

        return $this;
    }

    /**
     * @param string $content
     * @param string $roleId
     * @return $this
     */
    private function setContentForRoleId($content, $roleId)
    {
        if (!is_string($content)) {
            throw new LogicException("Le contenu d'un message ne peut être qu'une chaîne de caractère.");
        }

        $this->contentsByRoleId[$roleId] = $content;

        return $this;
    }

    /**
     * Retourne le contenu du message, éventuellement pour un rôle précis.
     *
     * SI aucun rôle n'est spécifié OU SI il n'existe pas de contenu pour le rôle spécifié,
     * le contenu par défaut est retourné.
     *
     * @param string|null $roleId
     * @return string
     */
    public function getContent($roleId = null)
    {
        if (!$this->contentsByRoleId) {
            throw new RuntimeException("Le message n'a aucun contenu.");
        }

        if (null === $roleId || !isset($this->contentsByRoleId[$roleId])) {
            return $this->getDefaultContent();
        }

        return $this->contentsByRoleId[$roleId];
    }

    /**
     * @return string
     */
    private function getDefaultContent()
    {
        $roleId = self::ROLE_ID_DEFAULT;

        if (isset($this->contentsByRoleId[$roleId])) {
            return $this->contentsByRoleId[$roleId];
        }

        $tmp = $this->contentsByRoleId;
        $defaultContent = array_shift($tmp);

        if (!$defaultContent) {
            throw new RuntimeException("Aucun contenu de message par défaut n'a été trouvé.");
        }

        return $defaultContent;
    }
}