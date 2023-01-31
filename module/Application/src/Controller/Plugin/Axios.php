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



    public function send($data): JsonModel
    {
        if ($data instanceof Query) {
            $data = $data->getResult();
        }
        $data = self::extract($data);

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



    public static function extract($object, array $properties = []): ?array
    {
        if (!$object) {
            return null;
        }

        if (is_array($object)) {
            $result = [];
            foreach ($object as $index => $sobj) {
                $sObjData = self::extract($sobj, $properties);
                if (isset($sObjData['id'])) {
                    $index = $sObjData['id'];
                }
                $result[$index] = $sObjData;
            }

            return $result;
        }

        $result = [];

        if (is_object($object) && method_exists($object, 'getId')) {
            $result['id'] = $object->getId();
        }
        if (!empty($properties)) {
            foreach ($properties as $property) {
                $subProperties = [];
                if (is_array($property)) {
                    [$property, $subProperties] = $property;
                }

                if ($property == 'id') continue;

                $foundValue = false;

                $prefixes = ['get', 'is', 'has'];
                foreach ($prefixes as $prefix) {
                    if (method_exists($object, $property)) {
                        $value      = $object->$property();
                        $foundValue = true;
                    } else {
                        $method = $prefix . ucfirst($property);
                        if (method_exists($object, $method)) {
                            $value      = $object->$method();
                            $foundValue = true;
                            break;
                        }
                    }
                }

                if ($foundValue) {
                    if (is_object($value)) {
                        if ($value instanceof \DateTime) {
                            $value = $value->format(Util::DATE_FORMAT);
                        } elseif ($value instanceof Collection) {
                            $oriVals = $value;
                            $value   = [];
                            foreach ($oriVals as $oriVal) {
                                if (method_exists($oriVal, 'getId')) {
                                    $id         = $oriVal->getId();
                                    $value[$id] = self::extract($oriVal, $subProperties);
                                } else {
                                    $value[] = self::extract($oriVal, $subProperties);
                                }
                            }
                        } else {
                            $value = self::extract($value, $subProperties);
                        }
                    }
                    $result[$property] = $value;
                }
            }
        } elseif ($object instanceof AxiosExtractor) {
            $result = self::extract($object, $object->axiosDefinition());
        } else {
            $result['libelle'] = (string)$object;
        }

        return $result;
    }
}