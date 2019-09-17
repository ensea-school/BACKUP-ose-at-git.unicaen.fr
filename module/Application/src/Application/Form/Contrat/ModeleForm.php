<?php

namespace Application\Form\Contrat;

use Application\Entity\Db\ModeleContrat;
use Application\Form\AbstractForm;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use UnicaenApp\Util;
use Zend\Hydrator\HydratorInterface;


/**
 * Description of ModeleForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ModeleForm extends AbstractForm
{
    use StatutIntervenantServiceAwareTrait;
    use StructureServiceAwareTrait;



    public function getStructures()
    {
        $qb = $this->getServiceStructure()->finderByHistorique();
        $this->getServiceStructure()->finderByEnseignement($qb);

        return $this->getServiceStructure()->getList($qb);
    }



    public function getStatutsIntervenants()
    {
        $qb = $this->getServiceStatutIntervenant()->finderByHistorique();

        return $this->getServiceStatutIntervenant()->getList($qb);
    }



    public function init()
    {
        $hydrator = new ModeleFormHydrator;
        $hydrator->setServiceStructure($this->getServiceStructure());
        $hydrator->setServiceStatutIntervenant($this->getServiceStatutIntervenant());
        $this->setHydrator($hydrator);


        $this->setAttributes([
            'action'  => $this->getCurrentUrl(),
            'class'   => 'contrat-modeles-editer',
            'enctype' => 'multipart/form-data',
        ]);

        $this->add([
            'type'    => 'Text',
            'name'    => 'libelle',
            'options' => [
                'label' => "Libellé",
            ],
        ]);

        $this->add([
            'type'    => 'Select',
            'name'    => 'statut-intervenant',
            'options' => [
                'label'         => 'Statut intervenant',
                'empty_option'  => "Tous statuts",
                'value_options' => Util::collectionAsOptions($this->getStatutsIntervenants()),
            ],
        ]);

        $this->add([
            'type'    => 'Select',
            'name'    => 'structure',
            'options' => [
                'label'         => 'Composante',
                'empty_option'  => "Toutes composantes",
                'value_options' => Util::collectionAsOptions($this->getStructures()),
            ],
        ]);

        $this->add([
            'type'       => 'File',
            'name'       => 'fichier',
            'options'    => [
                'label' => "Modèle au format OpenDocument Texte (ODT) <small>(à fournir seulement si changement)</small>",
                'label_options' => ['disable_html_escape' => true],
            ],
            'attributes' => [
                'id'       => 'fichier',
                'multiple' => false,
                'accept'   => 'application/vnd.oasis.opendocument.text',
            ],
        ]);

        $this->add([
            'type'       => 'Textarea',
            'name'       => 'requete',
            'options'    => [
                'label' => "Requête SQL",
            ],
            'attributes' => [
                'id'   => 'requete',
                'rows' => '20',
            ],
        ]);

        for ($i = 1; $i <= 10; $i++) {
            $this->add([
                'type'       => 'Text',
                'name'       => "bloc-$i-nom",
                'options'    => [
                    'label' => "Nom",
                ],
                'attributes' => [
                    'class' => 'form-control bloc-nom',
                ],
            ]);

            $this->add([
                'type'       => 'Textarea',
                'name'       => "bloc-$i-requete",
                'options'    => [
                    'label' => "Requête SQL",
                ],
                'attributes' => [
                    'id'   => "bloc-$i-requete",
                    'rows' => '15',
                ],
            ]);
        }

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary btn-save',
            ],
        ]);
    }



    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $filters = [
            'structure'          => [
                'required' => false,
            ],
            'statut-intervenant' => [
                'required' => false,
            ],
        ];

        for ($i = 1; $i <= 10; $i++) {
            $filters["bloc-$i-nom"]     = ['required' => false];
            $filters["bloc-$i-requete"] = ['required' => false];
        }

        return $filters;
    }
}





class ModeleFormHydrator implements HydratorInterface
{
    use StatutIntervenantServiceAwareTrait;
    use StructureServiceAwareTrait;



    /**
     * @param  array        $data
     * @param ModeleContrat $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        /* on peuple l'objet à partir du tableau de données */

        $object->setLibelle($data['libelle']);
        $object->setStructure($this->getServiceStructure()->get($data['structure']));
        $object->setStatutIntervenant($this->getServiceStatutIntervenant()->get($data['statut-intervenant']));
        $object->setRequete($data['requete']);
        if (isset($data['fichier']['tmp_name']) && $data['fichier']['tmp_name']) {
            $object->setFichier(file_get_contents($data['fichier']['tmp_name']));
            unlink($data['fichier']['tmp_name']);
        }

        $blocs = [];

        for ($i = 1; $i <= 10; $i++) {
            if (isset($data["bloc-$i-nom"]) && $data["bloc-$i-nom"]
                && isset($data["bloc-$i-requete"]) && $data["bloc-$i-requete"]) {
                $blocs[$data["bloc-$i-nom"]] = $data["bloc-$i-requete"];
            }
        }
        $object->setBlocs($blocs);

        return $object;
    }



    /**
     * @param ModeleContrat $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle'            => $object->getLibelle(),
            'structure'          => $object->getStructure() ? $object->getStructure()->getId() : null,
            'statut-intervenant' => $object->getStatutIntervenant() ? $object->getStatutIntervenant()->getId() : null,
            'requete'            => $object->getRequete(),
        ];

        $blocs = $object->getBlocs();
        $i     = 1;
        foreach ($blocs as $nom => $requete) {
            $data["bloc-$i-nom"]     = $nom;
            $data["bloc-$i-requete"] = $requete;
            $i++;
        }

        return $data;
    }
}