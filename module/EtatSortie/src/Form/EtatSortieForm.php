<?php

namespace EtatSortie\Form;


use EtatSortie\Hydrator\EtatSortieHydrator;
use Signature\Service\SignatureFlowServiceAwareTrait;
use UnicaenApp\Util;
use UnicaenSignature\Service\SignatureConfigurationServiceAwareTrait;


/**
 * Description of EtatSortieForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class EtatSortieForm extends \Application\Form\AbstractForm
{
    use SignatureFlowServiceAwareTrait;
    use SignatureConfigurationServiceAwareTrait;


    public function init()
    {
        $hydrator = new EtatSortieHydrator();
        $this->setHydrator($hydrator);


        $this->setAttributes([
                                 'action'  => $this->getCurrentUrl(),
                                 'class'   => 'etat-sortie-saisir',
                                 'enctype' => 'multipart/form-data',
                             ]);

        $this->add([
                       'type'    => 'Text',
                       'name'    => 'code',
                       'options' => [
                           'label' => "Code",
                       ],
                   ]);

        $this->add([
                       'type'    => 'Text',
                       'name'    => 'libelle',
                       'options' => [
                           'label' => "Libellé",
                       ],
                   ]);

        $this->add([
                       'type'    => 'Text',
                       'name'    => 'cle',
                       'options' => [
                           'label' => "Champ clé",
                       ],
                   ]);

        $this->add([
                       'type'       => 'Textarea',
                       'name'       => 'csv-params',
                       'options'    => [
                           'label' => "Paramètres d'export CSV (format JSON)",
                       ],
                       'attributes' => [
                           'id'   => 'csv-params',
                           'rows' => '25',
                       ],
                   ]);

        $this->add([
                       'type'       => 'Textarea',
                       'name'       => 'pdf-traitement',
                       'options'    => [
                           'label' => "Traitement des données (code PHP)",
                       ],
                       'attributes' => [
                           'id'   => 'pdf-traitement',
                           'rows' => '25',
                       ],
                   ]);

        $this->add([
                       'type'       => 'Textarea',
                       'name'       => 'csv-traitement',
                       'options'    => [
                           'label' => "Traitement des données (code PHP)",
                       ],
                       'attributes' => [
                           'id'   => 'csv-traitement',
                           'rows' => '25',
                       ],
                   ]);

        $this->add([
                       'type'    => 'Checkbox',
                       'name'    => 'auto-break',
                       'options' => [
                           'label'              => 'Saut de page automatique : en cas de publipostage, chaque copie démarre en haut de page plutôt qu\'à la suite',
                           'use_hidden_element' => true,
                           'checked_value'      => 'true',
                           'unchecked_value'    => 'false',
                       ],
                   ]);

        $this->add([
                       'type'       => 'File',
                       'name'       => 'fichier',
                       'options'    => [
                           'label'         => "Modèle au format OpenDocument Texte (ODT) <small>(à fournir seulement si changement)</small>",
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

        $this->add([
                       'type'    => 'Select',
                       'name'    => "signatureActivation",
                       'options' => [
                           'label'         => "Activer la signature électronique pour cet état de sortie",
                           'value_options' => [
                               1 => 'Oui',
                               0 => 'Non',
                           ],
                       ],
                   ]);

        $this->add([
                       'type'    => 'Select',
                       'name'    => "signatureCircuit",
                       'options' => [
                           'label'         => "Circuit à utiliser pour la signature électronique",
                           'value_options' => ['' => '(Sélectionnez un circuit)'] + Util::collectionAsOptions($this->getServiceSignatureFlow()->getList()),
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
                               'style' => 'width:30%',
                           ],
                       ]);

            $this->add([
                           'type'       => 'Select',
                           'name'       => "bloc-$i-zone",
                           'options'    => [
                               'label'         => "",
                               'value_options' => [
                                   'table:table-row' => 'Tableau',
                               ],
                           ],
                           'attributes' => [
                               'class' => 'form-control bloc-zone',
                               'style' => 'width:30%',
                           ],
                       ]);

            $this->add([
                           'type'       => 'Textarea',
                           'name'       => "bloc-$i-requete",
                           'options'    => [
                               'label' => "Requête générale",
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
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $filters = [
            "signatureActivation" => [
                'required' => false,
            ],
            "signatureCircuit"    => [
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

