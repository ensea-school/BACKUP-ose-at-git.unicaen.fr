<?php

namespace Application\Controller\Plugin;

use Application\Constants;
use Application\Interfaces\AxiosExtractor;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\JsonModel;
use UnicaenApp\Util;

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



    public function send($data, array $properties = []): JsonModel
    {
        $data = self::extract($data, $properties);

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



    public static function extract($data, array $properties = [])
    {
        if ($data instanceof Query) {
            return self::extract($data->getResult(), $properties);
        } elseif (self::isList($data)) {
            return self::extractList($data, $properties);
        } elseif (is_array($data)) {
            return self::extractArray($data, $properties);
        } elseif ($data instanceof \DateTime) {
            return $data->format(Util::DATE_FORMAT);
        } elseif (is_object($data)) {
            return self::extractObject($data, $properties);
        } else {
            return $data;
        }
    }



    protected static function extractObject($data, array $properties): array
    {
        $result = [];

        $props = ['id'];
        if (empty($properties)) {
            if ($data instanceof AxiosExtractor) {
                $ad = $data->axiosDefinition();
                foreach ($ad as $prop) {
                    if ($prop !== 'id') {
                        $props[] = $prop;
                    }
                }
            } else {
                if (method_exists($data, '__toString')) {
                    $props[] = '__toString';
                }
            }
        } else {
            foreach ($properties as $prop) {
                if ($prop !== 'id') {
                    $props[] = $prop;
                }
            }
        }

        foreach ($props as $property) {
            if (is_array($property)) {
                $subProperties = $property[1];
                $property      = $property[0];
            } else {
                $subProperties = [];
            }

            $methods = [
                $property,
                'get' . ucfirst($property),
                'is' . ucfirst($property),
                'has' . ucfirst($property),
            ];
            foreach ($methods as $method) {
                if (method_exists($data, $method)) {
                    $value             = $data->$method();
                    $result[$property] = self::extract($value, $subProperties);
                    break;
                }
            }
        }

        if (array_key_exists('__toString', $result)) {
            $result['libelle'] = $result['__toString'];
            unset($result['__toString']);
        }

        return $result;
    }



    protected static function extractArray(array $data, array $properties): array
    {
        $result = [];


        $props = ['id'];
        if (empty($properties)) {
            $properties = array_keys($data);
        }
        foreach ($properties as $prop) {
            if ($prop !== 'id') {
                $props[] = $prop;
            }
        }

        foreach ($props as $property) {
            if (is_array($property)) {
                $subProperties = $property[1];
                $property      = $property[0];
            } else {
                $subProperties = [];
            }

            if (array_key_exists($property, $data)) {
                $result[$property] = self::extract($data[$property], $subProperties);
            }
        }

        return $result;
    }



    protected static function extractList($list, array $properties = []): array
    {
        $result = [];
        foreach ($list as $sobj) {
            $result[] = self::extract($sobj, $properties);
        }

        return $result;
    }



    protected static function isList($data): bool
    {
        if ($data instanceof Collection) {
            return true;
        }
        if (!is_array($data)) {
            return false;
        }
        foreach ($data as $k => $v) {
            if (!is_numeric($k)) {
                // une clé non numérique est rejetée
                return false;
            }
            if (!(is_array($v) || is_object($v))) {
                // une liste doit être une liste d'objets ou bien une liste de tableaux
                return false;
            }
        }

        return true;
    }
}