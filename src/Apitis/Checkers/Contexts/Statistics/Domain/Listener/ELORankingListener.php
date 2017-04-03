<?php
/**
 * Created by PhpStorm.
 * Date: 4/2/17
 * Time: 10:19 PM
 */

namespace Apitis\Checkers\Domain\Contexts\Statistics\Listener;


use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventListener;

class ELORankingListener implements EventListener
{
    public function handle(DomainMessage $domainMessage)
    {
        /** @var  */
        $domainMessage->getPayload();
        // TODO: Implement handle() method.
    }


}