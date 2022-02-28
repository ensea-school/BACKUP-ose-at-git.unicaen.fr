<?php

namespace Application\View\Helper\OffreFormation;

use Application\Form\OffreFormation\EtapeModulateursSaisie;
use Laminas\View\Helper\AbstractHelper;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Laminas\Form\Element\Select;

/**
 * Description of EtapeModulateursSaisieForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementModulateurSaisieForm extends AbstractHelper
{
    use ElementPedagogiqueServiceAwareTrait;

    /**
     * @var Saisie
     */
    protected $form;



    /**
     *
     * @param EtapeModulateursSaisie $form
     *
     * @return self|string
     */
    public function __invoke(ElementModulateurSaisie $form = null)
    {
        if (null === $form) {
            return $this;
        }

        return $this->render($form);
    }



    /**
     * Rendu du formulaire
     *
     * @param Saisie $form
     *
     * @return string
     */
    public function render(ElementModulateursSaisie $form)
    {
        $elements         = $form->getElement();
        $typesModulateurs = $form->getTypesModulateurs();
        $res              = '';

        /* if (empty($elements)) {
             return 'Aucun enseignement n\'est associé à cette formation. Il est donc impossible d\'y associer des modulateurs';
         }
         if (0 == $form->countModulateurs()) {
             return 'Aucun modulateur ne correspond aux enseignements de cette formation';
         }
         if (empty($typesModulateurs)) {
             return 'Aucun modulateur ne peut être associé à cette formation car ils ne sont pas activés pour la structure d\'enseignement correspondante.';
         }

         $displayTypesModulateurs = [];
         foreach ($typesModulateurs as $typeModulateur) {
             if (0 < $form->countModulateurs($typeModulateur->getCode())) {
                 $displayTypesModulateurs[] = $typeModulateur;
             }
         }

         $form->prepare();
         $res = $this->getView()->form()->openTag($form);
         $res .= '<table class="table table-bordered table-extra-condensed">';
         $res .= '<tr>';
         $res .= '<th rowspan="2">Elément</th>';
         foreach ($displayTypesModulateurs as $typeModulateur) {
             $res .= '<th>';
             $res .= $typeModulateur->getLibelle();
             $res .= '</th>';
         }
         $res .= '</tr>';
         $res .= '<tr>';
         foreach ($displayTypesModulateurs as $typeModulateur) {
             $typeModulateurElement = new Select($typeModulateur->getCode());
             //$typeModulateurElement->setLabel($typeModulateur->getLibelle());
             $values = ['' => ''];
             foreach ($typeModulateur->getModulateur() as $modulateur) {
                 $values[$modulateur->getId()] = (string)$modulateur;
             }
             $typeModulateurElement->setValueOptions(\UnicaenApp\Util::collectionAsOptions($values));
             $typeModulateurElement->setAttribute('class', 'form-control');
             $res .= '<th>';
             $res .= $this->getView()->formSelect($typeModulateurElement);
 //href="javascript:return false;" onclick="Modulateur.setFormValues($(this).data(\'code\'), $($(this).data(\'code\')).val())
             $res .= ' <button type="button" class="btn btn-default btn-sm form-set-value" data-code="' . $typeModulateur->getCode() . '" title="Appliquer à tous"><i class="fa-solid fa-arrow-down"></i></button>';
             $res .= '</th>';
         }
         $res .= '</tr>';
         foreach ($elements as $element) {
             $res         .= '<tr>';
             $res         .= '<th>' . $element . '</th>';
             $formElement = $form->get('EL' . $element->getId());
             $res         .= $this->getView()->elementModulateursSaisieFieldset()->render($formElement, $displayTypesModulateurs, true);
             $res         .= '</tr>';
         }
         $res .= '</table>';
         $res .= $this->getView()->formHidden($form->get('id'));
         $res .= $this->getView()->formRow($form->get('submit'));
         $res .= $this->getView()->form()->closeTag();*/

        return $res;
    }
}