<?php

namespace Application\Controller\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\JsonModel;

/**
 * Description of Axios
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Axios extends AbstractPlugin
{

    public function fromPost(?string $param = null, $default = null)
    {
        $post = (array)json_decode(file_get_contents('php://input'));
        if ($param) {
            if (array_key_exists($param, $post)) {
                return $post[$param];
            } else {
                return $default;
            }
        } else {
            return $post;
        }
    }



    public function send(array $data): JsonModel
    {
        /** @var FlashMessenger $flashMessenger */
        $flashMessenger = $this->controller->flashMessenger();

        $namespaces = [
            $flashMessenger::NAMESPACE_SUCCESS,
            $flashMessenger::NAMESPACE_WARNING,
            $flashMessenger::NAMESPACE_ERROR,
            $flashMessenger::NAMESPACE_INFO,
        ];

        $messages = [];
        foreach ($namespaces as $namespace) {
            if ($flashMessenger->hasCurrentMessages($namespace)) {
                $messages[$namespace] = $flashMessenger->getCurrentMessages($namespace);
                $flashMessenger->clearCurrentMessages($namespace);
            }
        }

        $jsonData = [
            'data'     => $data,
            'messages' => $messages,
        ];

        $model = new JsonModel($jsonData);

        return $model;
    }
}