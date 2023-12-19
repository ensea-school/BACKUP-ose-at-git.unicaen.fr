<?php

namespace Intervenant\Service;

/**
 * Description of StatutServiceFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
trait MailServiceAwareTrait
{
    protected ?MailService $serviceMail = null;



    /**
     * @param MailService $serviceMail
     *
     * @return self
     */
    public function setServiceMail(?MailService $serviceMail)
    {
        $this->serviceMail = $serviceMail;

        return $this;
    }



    public function getServiceMail(): ?MailService
    {
        if (empty($this->serviceMail)) {
            $this->serviceMail = \OseAdmin::instance()->container()->get(MailService::class);
        }

        return $this->serviceMail;
    }
}