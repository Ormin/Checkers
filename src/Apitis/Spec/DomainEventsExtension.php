<?php
/**
 * Created by PhpStorm.
 * Date: 3/30/17
 * Time: 5:32 PM
 */

namespace Apitis\Spec;

use PhpSpec\Extension;
use PhpSpec\ServiceContainer;

class DomainEventsExtension implements Extension
{
    /**
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container, array $params)
    {
        $container->set('matchers.happened', new EventsMatcher($container->get('formatter.presenter')), ['matchers']);
    }
}