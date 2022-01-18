<?php

namespace Plafond\View\Helper;

use Laminas\View\Helper\AbstractHtmlElement;
use Plafond\Form\PlafondConfigFormAwareTrait;
use Plafond\Interfaces\PlafondConfigInterface;


/**
 * Description of PlafondConfigElementViewHelper
 *
 * @author UnicaenCode
 */
class PlafondConfigElementViewHelper extends AbstractHtmlElement
{
    use PlafondConfigFormAwareTrait;

    private PlafondConfigInterface $plafondConfig;



    /**
     *
     * @return self
     */
    public function __invoke(PlafondConfigInterface $plafondConfig)
    {
        $this->plafondConfig = $plafondConfig;

        return $this;
    }



    public function etatPrevu(bool $editable = false): string
    {
        if ($editable) {
            $element = $this->getFormPlafondConfig()->getElement($this->plafondConfig, 'plafondEtatPrevu');

            return $this->getView()->formControlGroup($element);
        } else {
            return $this->plafondConfig->getEtatPrevu();
        }
    }



    public function etatRealise(bool $editable = false): string
    {
        if ($editable) {
            $element = $this->getFormPlafondConfig()->getElement($this->plafondConfig, 'plafondEtatRealise');

            return $this->getView()->formControlGroup($element);
        } else {
            return $this->plafondConfig->getEtatRealise();
        }
    }



    public function heures(bool $editable = false): string
    {
        if ($editable) {
            $element = $this->getFormPlafondConfig()->getElement($this->plafondConfig, 'heures');

            return $this->getView()->formControlGroup($element);
        } else {
            return floatToString($this->plafondConfig->getHeures());
        }
    }



    public function script(): string
    {
        return "<script type=\"text/javascript\">
        $(function () {

            WidgetInitializer.add('plafonds-config', {

            change: function (el)
            {
                var params = {
                plafond: el.data('plafond-id'),
                    entity: null,
                    name: el.data('name'),
                    value: el.val()
                };
                $.post('" . $this->getView()->url('plafond/config-application') . "', params, function (data)
                {
                    alertFlash('Votre modification a bien été prise en compte', 'success', 3000);
                }).fail(function (jqXHR)
                {
                    alertFlash(jqXHR.responseText, 'danger', 3000);
                });
            },

            _create: function ()
            {
                var that = this;
                var elsel = '[data-name=\"plafondEtatPrevu\"],[data-name=\"plafondEtatRealise\"],[data-name=\"heures\"]';

                this.element.find(elsel).each(function () {
                    var thatthat = $(this);
                    thatthat.change(function () { that.change(thatthat) });
                });
            },

            });

        });
        </script>";
    }
}