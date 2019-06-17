<?php

namespace BddAdmin;

use BddAdmin\Data\AbstractInput;
use BddAdmin\Data\AbstractOutput;

class Data
{
    /**
     * @var AbstractInput
     */
    private $input;

    /**
     * @var AbstractOutput
     */
    private $output;



    /**
     * @return AbstractInput
     */
    public function getInput(): ?AbstractInput
    {
        return $this->input;
    }



    /**
     * @param AbstractInput|string $input
     *
     * @return $this
     * @throws \Exception
     */
    public function setInput($input): self
    {
        if (is_array($input)){
            if (!array_key_exists('type', $input)){
                throw new \Exception('La classe d\'input n\'a pas été définie');
            }
            $this->setInput($input['type']);
            $this->input->applyConfig($input);

            return $this;
        }

        if (is_string($input)) {
            /** @var AbstractInput $input */
            $input = new $input;
        }

        if (!$input instanceof AbstractInput) {
            throw new \Exception("L'input fourni doit hériter d'AbstractInput");
        }

        $this->input = $input;

        return $this;
    }



    /**
     * @return AbstractOutput
     */
    public function getOutput(): ?AbstractOutput
    {
        return $this->output;
    }



    /**
     * @param AbstractOutput|string $output
     *
     * @return $this
     * @throws \Exception
     */
    public function setOutput($output): self
    {
        if (is_array($output)){
            if (!array_key_exists('type', $output)){
                throw new \Exception('La classe d\'output n\'a pas été définie');
            }
            $this->setOutput($output['type']);
            $this->output->applyConfig($output);

            return $this;
        }

        if (is_string($output)) {
            /** @var AbstractOuput $output */
            $output = new $output;
        }

        if (!$output instanceof AbstractOutput) {
            throw new \Exception("L'output fourni doit hériter d'AbstractOutput");
        }

        $this->output = $output;

        return $this;
    }



    public function applyConfig(array $config)
    {
        if (isset($config['input'])) {
            $this->setInput($config['input']);
        }
        if (isset($config['output'])) {
            $this->setOutput($config['output']);
        }
    }



    public function transferer(array $config = [])
    {
        if (!empty($config)) {
            $this->applyConfig($config);
        }

        if (!$this->input) {
            throw new \Exception('L\'entrée n\'a pas été définie');
        }

        if (!$this->output) {
            throw new \Exception('La sortie n\'a pas été définie');
        }

        $this->input->lire($this->output);
    }

}