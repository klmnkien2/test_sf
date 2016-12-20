<?php

namespace Gao\AdminBundle\Biz\User;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use Gao\AdminBundle\Biz\BizException;

/**
 * Class: ListBiz.
 */
class ListBiz
{
    /**
     * Service Container Interface.
     */
    private $container;

    /**
     * __construct.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function main()
    {
        //May be call when we have advance form
    }
}
