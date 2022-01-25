<?php

namespace Plafond\View\Helper;

use Application\Entity\Db\FonctionReferentiel;
use Intervenant\Entity\Db\Statut;
use Application\Entity\Db\Structure;
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
    public function __invoke(?PlafondConfigInterface $plafondConfig = null)
    {
        if ($plafondConfig) {
            $this->plafondConfig = $plafondConfig;
        }

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



    public function script($entity = null)
    {
        $urls = [
            '*'                        => 'plafond/config-application',
            FonctionReferentiel::class => 'plafond/config-referentiel',
            Structure::class           => 'plafond/config-structure',
            Statut::class              => 'plafond/config-statut',
        ];
        if (is_object($entity)) {
            $url      = $this->getView()->url($urls[get_class($entity)]);
            $entityId = $entity->getId();
        } else {
            $url      = $this->getView()->url($urls['*']);
            $entityId = null;
        }

        ?>
        <script> $(function () {

                WidgetInitializer.add('plafonds-config', {

                    change: function (el)
                    {
                        $.ajax({
                            url: '<?= $url ?>',
                            type: 'POST',
                            data: {
                                plafond: el.data('plafond-id'),
                                name: el.data('name'),
                                value: el.val(),
                                entityId: <?= $entityId ?: 'null' ?>
                            },
                            success: function () {
                                alertFlash('Votre modification a bien été prise en compte', 'success', 3000);
                            },
                            error: function () {
                                alertFlash(jqXHR.responseText, 'danger', 3000);
                            }
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

            }); </script><?php
    }
}