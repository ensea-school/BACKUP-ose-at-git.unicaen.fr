<?php

namespace OffreFormation\Form;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Traits\ElementPedagogiqueAwareTrait;
use Application\Form\AbstractFieldset;
use Laminas\Form\Element\Select;
use Laminas\Hydrator\HydratorInterface;
use OffreFormation\Service\Traits\ElementModulateurServiceAwareTrait;
use Application\Service\Traits\ModulateurServiceAwareTrait;
use Application\Service\Traits\TypeModulateurServiceAwareTrait;

/**
 * Description of ElementModulateursFieldset
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementModulateursFieldset extends AbstractFieldset
{
    use ElementPedagogiqueAwareTrait;
    use TypeModulateurServiceAwareTrait;
    use ElementModulateurServiceAwareTrait;
    use ModulateurServiceAwareTrait;

    /**
     * nombre de modulateurs total
     *
     * @var integer
     */
    protected $countModulateurs = 0;

    /**
     * nombre de modulateurs par code de type
     *
     * @var integer[]
     */
    protected $countModulateursByType = [];



    /**
     * Retourne le nombre total de modulateurs que l'on peut renseigner
     *
     * @param string|null $typeCode
     *
     * @return integer
     */
    public function countModulateurs($typeCode = null)
    {
        if (empty($typeCode)) {
            return $this->countModulateurs;
        } elseif (isset($this->countModulateursByType[$typeCode])) {
            return $this->countModulateursByType[$typeCode];
        } else {
            return 0;
        }
    }



    /**
     * Retourne la liste des types de modulateurs
     *
     * @return \Application\Entity\Db\Modulateur[]
     */
    public function getTypesModulateurs()
    {
        $element = $this->getElementPedagogique();
        if (!$element) {
            throw new \RuntimeException('Elément pédagogique non spécifié');
        }
        $serviceTypeModulateur = $this->getServiceTypeModulateur();

        return $serviceTypeModulateur->getList($serviceTypeModulateur->finderByElementPedagogique($element));
    }



    public function init()
    {
        $hydrator = new ElementModulateursHydrator;
        $hydrator->setServiceModulateur($this->getServiceModulateur());
        $hydrator->setServiceElementModulateur($this->getServiceElementModulateur());
        $this->setHydrator($hydrator);
        $this->setAllowedObjectBindingClass(ElementPedagogique::class);
    }



    public function build()
    {
        $typesModulateurs = $this->getTypesModulateurs();
        foreach ($typesModulateurs as $typeModulateur) {
            $element = new Select($typeModulateur->getCode());
            $element->setLabel($typeModulateur->getLibelle());
            $values = ['' => ''];
            foreach ($typeModulateur->getModulateur() as $modulateur) {
                $values[$modulateur->getId()] = (string)$modulateur;
            }

            $element->setValueOptions(\UnicaenApp\Util::collectionAsOptions($values));
            $this->add($element);
            $this->countModulateurs++;
            if (!isset($this->countModulateursByType[$typeModulateur->getCode()])) {
                $this->countModulateursByType[$typeModulateur->getCode()] = 0;
            }
            $this->countModulateursByType[$typeModulateur->getCode()]++;
        }
    }



    /**
     *
     * @param ElementPedagogique $object
     *
     * @return self
     */
    public function setObject($object)
    {
        if ($object instanceof ElementPedagogique) {
            $this->setElementPedagogique($object);
            $this->build();
        }

        return parent::setObject($object);
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $typesModulateurs = $this->getTypesModulateurs();
        $filters          = [];
        foreach ($typesModulateurs as $typeModulateur) {
            $filters[$typeModulateur->getCode()] = [
                'required' => false,
            ];
        }

        return $filters;
    }

}





/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementModulateursHydrator implements HydratorInterface
{
    use ElementModulateurServiceAwareTrait;
    use ModulateurServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array              $data
     * @param ElementPedagogique $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $old = [];
        $new = [];

        /* Détection des anciennes valeurs */
        $oldEm = [];
        foreach ($object->getElementModulateur() as $elementModulateur) {
            $modulateur                       = $elementModulateur->getModulateur();
            $old[]                            = (int)$modulateur->getId();
            $oldEm[(int)$modulateur->getId()] = $elementModulateur;
        }
        /* Détection des nouvelles valeurs */
        foreach ($data as $typeModulateurCode => $modulateurId) {
            if ($modulateurId) {
                $new[] = (int)$modulateurId;
            }
        }

        /* Détermination des changements à effectuer */
        sort($old);
        sort($new);
        $insert = array_diff($new, $old);
        $delete = array_diff($old, $new);

        /* Suppressions */
        foreach ($delete as $mid) {
            $oldEm[$mid]->setRemove(true); // Flag de suppression pour les anciens à retirer
            $object->setHasChanged(true);
        }
        /* Créations */
        foreach ($insert as $mid) {
            $elementModulateur = $this->getServiceElementModulateur()->newEntity();
            $elementModulateur->setElement($object);
            $elementModulateur->setModulateur($this->getServiceModulateur()->get($mid));
            $object->addElementModulateur($elementModulateur);
            $object->setHasChanged(true);
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param ElementPedagogique $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $sm = $this->getServiceModulateur();

        $data        = [];
        $qb          = $sm->finderByElementPedagogique($object);
        $modulateurs = $sm->getList($qb);
        foreach ($modulateurs as $modulateur) {
            $data[$modulateur->getTypeModulateur()->getCode()] = $modulateur->getId();
        }

        return $data;
    }

}