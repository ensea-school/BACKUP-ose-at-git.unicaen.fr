<?php

namespace Application\Controller\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

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

}