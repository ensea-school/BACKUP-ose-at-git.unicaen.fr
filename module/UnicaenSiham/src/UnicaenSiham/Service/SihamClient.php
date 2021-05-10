<?php

namespace UnicaenSiham\Service;


class SihamClient
{
    const SOAP_VERSION = SOAP_1_1;

    protected $wsdl;

    protected $params;

    protected $client;



    public function __construct(array $wsdl, array $params = [])
    {
        if ($params !== null) {
            $this->setParams($params);
        }

        if ($wsdl !== null) {
            $this->setWsdl($wsdl);
        }
    }



    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }



    public function getParams(): array
    {
        return $this->params;
    }



    public function setWsdl(array $wsdl): self
    {
        $this->wsdl = $wsdl;

        return $this;
    }



    public function getWsdl(): array
    {
        return $this->wsdl;
    }



    public function getClient($webserviceName): \SoapClient
    {


        $params = [
            'login'        => $this->params['login'] ?? null,
            'password'     => $this->params['password'] ?? null,
            'soap_version' => $this->params['version'] ?? self::SOAP_VERSION,
            'cache_wsdl'   => $this->params['cache_wsdl'] ?? 0,
            'trace'        => $this->params['trace'] ?? 0,
            'proxy_host'   => $this->params['proxy_host'] ?? null,
            'proxy_port'   => $this->params['proxy_port'] ?? null,
        ];


        if (array_key_exists($webserviceName, $this->wsdl)) {
            $this->client = new \SoapClient($this->wsdl[$webserviceName], $params);
        }

        return $this->client;
    }



    public function getLastRequest()
    {
        $lastRequest = $this->client->__getLastRequest();

        if ($this->client instanceof \SoapClient) {
            return $lastRequest;
        }

        return false;
    }

}