<?php

namespace Application\View\Helper;

use Laminas\View\Helper\AbstractHtmlElement;


/**
 * Description of ViteViewHelper
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class VueViewHelper extends AbstractHtmlElement
{
    protected bool $inVue = false;



    /**
     *
     * @return self|string
     */
    public function __invoke(string $name = null, array $props = [])
    {
        if (!empty($name)) {
            $h = $this->begin();
            $h .= $this->component($name, $props);
            $h .= $this->end();

            return $h;
        }

        return $this;
    }



    /**
     * Démarre une nouvelle zone de Vue
     *
     * @return string
     */
    public function begin(): string
    {
        $this->inVue = true;

        return $this->getView()->tag("div", ['class' => 'vue-app']);
    }



    /**
     * Termine une zone de Vue
     *
     * @return string
     */
    public function end(): string
    {
        $this->inVue = false;

        return "</div>";
    }



    /**
     * Ajoute un composant Vue.JS à l'intérieur d'une zone de Vue.
     *
     * @param string $name
     * @param array  $props
     *
     * @return string
     */
    public function component(string $name, array $props): string
    {
        if (!$this->inVue) {
            return '<div class="alert alert-danger"><strong>Attention</strong> : votre composant doit être positionné à l\'intérieur' .
                'd\'une zone dédiée à Vue.js. Veuillez utiliser $this->begin(); avant et $this->end(); après.</div>';
        }
        $name = str_replace('/', '-', $name);

        $attrs = [];
        foreach ($props as $pn => $pv) {
            $pt = getType($pv);
            switch ($pt) {
                case 'boolean':
                    $pv = $pv ? 'true' : 'false';
                break;
                case 'array':
                    $pv = json_encode($pv);
                default:
                    $pv = (string)$pv;
            }

            $attrs[':' . strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $pn))] = $pv;
        }

        $res = $this->getView()->tag($name, $attrs)->html('');

        return $res;
    }
}