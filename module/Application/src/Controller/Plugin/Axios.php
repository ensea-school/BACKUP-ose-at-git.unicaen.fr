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

        $jsonData = [
            'data'     => $data,
            'messages' => [
                $flashMessenger::NAMESPACE_ERROR   => $flashMessenger->getCurrentErrorMessages(),
                $flashMessenger::NAMESPACE_SUCCESS => $flashMessenger->getCurrentSuccessMessages(),
                $flashMessenger::NAMESPACE_WARNING => $flashMessenger->getCurrentWarningMessages(),
                $flashMessenger::NAMESPACE_INFO    => $flashMessenger->getCurrentInfoMessages(),
            ],
        ];
        $flashMessenger->clearCurrentMessages($flashMessenger::NAMESPACE_ERROR);
        $flashMessenger->clearCurrentMessages($flashMessenger::NAMESPACE_SUCCESS);
        $flashMessenger->clearCurrentMessages($flashMessenger::NAMESPACE_WARNING);
        $flashMessenger->clearCurrentMessages($flashMessenger::NAMESPACE_INFO);

        $model = new JsonModel($jsonData);

        return $model;
    }
}