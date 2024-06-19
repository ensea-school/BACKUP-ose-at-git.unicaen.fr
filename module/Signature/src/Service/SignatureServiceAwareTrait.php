<?php

namespace Signature\Service;


use Service\Service\TagService;

/**
 * Description of SignatureServiceAwareTrait
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
trait SignatureServiceAwareTrait
{
    protected ?SignatureService $serviceSignature = null;



    /**
     * @param SignatureService $serviceSignature
     *
     * @return self
     */
    public function setServiceSignature(?SignatureService $serviceSignature)
    {
        $this->serviceSignature = $serviceSignature;

        return $this;
    }



    public function getServiceSignature(): ?SignatureService
    {
        if (empty($this->serviceSignature)) {
            $this->serviceSignature = \OseAdmin::instance()->container()->get(SignatureService::class);
        }

        return $this->serviceSignature;
    }
}