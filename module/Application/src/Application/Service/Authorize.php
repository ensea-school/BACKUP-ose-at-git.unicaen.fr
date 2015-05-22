<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Service;

/**
 * Authorize service
 *
 * @author Ben Youngblood <bx.youngblood@gmail.com>
 */
class Authorize extends \BjyAuthorize\Service\Authorize
{
    use Traits\ContextAwareTrait;

    /**
     * Loading...
     *
     * @var boolean
     */
    protected $loading;


    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @deprecated this method will be removed in BjyAuthorize 1.4.x+,
     *             please retrieve the identity from the
     *             `BjyAuthorize\Provider\Identity\ProviderInterface` service
     *
     * @return string
     */
    public function getIdentity()
    {
        $this->loaded && $this->loaded->__invoke();
        if ($this->loading) return 'bjyauthorize-identity';
        return $this->getServiceContext()->getSelectedIdentityRole();
    }

    /**
     * Initializes the service
     *
     * @internal
     *
     * @return void
     */
    public function load()
    {
        $this->loading = true;
        parent::load();
        $this->loading = false;
    }

}
