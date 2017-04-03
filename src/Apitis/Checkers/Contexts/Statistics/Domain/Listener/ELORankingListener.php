<?php
/**
 * Created by PhpStorm.
 * Date: 4/2/17
 * Time: 10:19 PM
 */

namespace Apitis\Checkers\Domain\Contexts\Statistics\Listener;


use Apitis\Checkers\Contexts\Game\Domain\Event\GameFinished;
use Broadway\Domain\DomainMessage;
use Broadway\EventHandling\EventListener;

class ELORankingListener implements EventListener
{
    public function handle(DomainMessage $domainMessage)
    {
        /** @var GameFinished $event */
        $event = $domainMessage->getPayload();
        //TODO ELO rating handling..
    }


}