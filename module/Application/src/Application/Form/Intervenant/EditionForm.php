<?php

namespace Application\Form\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Traits\GradeAwareTrait;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Form\AbstractForm;
use Application\Service\Traits\CiviliteServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use UnicaenApp\Util;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;
use Zend\Form\FormInterface;
use Zend\Hydrator\HydratorInterface;

/**
 * Description of EditionForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EditionForm extends AbstractForm
{
    use SourceServiceAwareTrait;
    use SchemaServiceAwareTrait;
    use CiviliteServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ContextServiceAwareTrait;



    public function init()
    {/*





, SOURCE_ID NUMBER(*, 0) NOT null
, SOURCE_CODE VARCHAR2(100 CHAR)
, MONTANT_INDEMNITE_FC FLOAT(126)
, ANNEE_ID NUMBER(*, 0) DEFAULT null NOT null ==> année en cours
, GRADE_ID NUMBER(*, 0)
, CRITERE_RECHERCHE VARCHAR2(255 CHAR) ==> automatique
, CODE VARCHAR2(60 CHAR)
, UTILISATEUR_CODE VARCHAR2(60 CHAR) ==> ? ?

, SYNC_STATUT NUMBER(1, 0) DEFAULT 1 NOT null ==> ??
, SYNC_STRUCTURE NUMBER(1, 0) DEFAULT 1 NOT null ==> ??
*/


        $hydrator = new IntervenantFormHydrator;
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());


        $this->add([
            'name'    => 'civilite',
            'type'    => 'Select',
            'options' => [
                'label'         => 'Civilité',
                'value_options' => Util::collectionAsOptions($this->getServiceCivilite()->getList()),
            ],
        ]);

        $this->add([
            'name'    => 'nomUsuel',
            'options' => [
                'label' => 'Nom usuel',
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'nomPatronymique',
            'type'    => 'Text',
            'options' => [
                'label' => 'Nom de naissance',
            ],

        ]);

        $this->add([
            'name'    => 'prenom',
            'type'    => 'Text',
            'options' => [
                'label' => 'Prénom',
            ],

        ]);

        $this->add([
            'name'       => 'dateNaissance',
            'type'       => 'UnicaenApp\Form\Element\Date',
            'options'    => [
                'label'         => 'Date de naissance',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'placeholder' => "jj/mm/aaaa",
            ],
        ]);

        $this->add([
            'name'    => 'statut',
            'type'    => 'Select',
            'options' => [
                'label'         => 'Statut',
                'value_options' => Util::collectionAsOptions($this->getServiceStatutIntervenant()->getList($this->getServiceStatutIntervenant()->finderByHistorique())),
            ],
        ]);

        $this->add([
            'name'    => 'structure',
            'type'    => 'Select',
            'options' => [
                'label' => 'Structure',
            ],
        ]);

        $this->add([
            'name'       => 'discipline',
            'type'       => 'Select',
            'options'    => [
                'label' => 'Discipline',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add([
            'name'       => 'grade',
            'type'       => 'Select',
            'options'    => [
                'label' => 'Grade',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add([
            'name'       => 'montantIndemniteFc',
            'options'    => [
                'label' => "Montant annuel de la rémunération FC D714-60 (€) :",
            ],
            'attributes' => [
                'value' => '0',
                'title' => "Nombre d'heures",
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'    => 'code',
            'type'    => 'Text',
            'options' => [
                'label' => 'Code',
            ],
        ]);

        $this->add([
            'name'    => 'utilisateurCode',
            'type'    => 'Text',
            'options' => [
                'label' => 'Identifiant LDAP éventuel (' . \AppConfig::get('ldap', 'utilisateurCode', 'supannEmpId') . ')',
            ],
        ]);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);

        $role             = $this->getServiceContext()->getSelectedIdentityRole();
        $serviceStructure = $this->getServiceStructure();
        $qb               = $serviceStructure->finderByEnseignement();
        if ($structure = ($role ? $role->getStructure() : null)) {
            $serviceStructure->finderById($role->getStructure()->getId(), $qb); // Filtre
        }
        $this->get('structure')
            ->setValueOptions(Util::collectionAsOptions($serviceStructure->getList($qb)));
    }



    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        /* @var $object Intervenant */
        parent::bind($object, $flags);

        if ($object->getSource() && $object->getSource()->getImportable()) {
            foreach ($this->getElements() as $element) {
                if ($this->getServiceSchema()->isImportedProperty($object, $element->getName())) {
                    $element->setAttribute('readonly', true);
                }
            }
        }

        return $this;
    }



    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'montantIndemniteFc' => [
                'required' => false,
                'filters'  => [
                    ['name' => FloatFromString::class],
                ],
            ],
        ];
    }
}





class IntervenantFormHydrator implements HydratorInterface
{

    /**
     * @param array       $data
     * @param Intervenant $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setMontantIndemniteFc($data['montantIndemniteFc']);

        return $object;
    }



    /**
     * @param Intervenant $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'                 => $object->getId(),
            'montantIndemniteFc' => StringFromFloat::run($object->getMontantIndemniteFc()),
        ];

        return $data;
    }
}