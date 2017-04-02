<?php
/**
 * Created by PhpStorm.
 * Date: 3/30/17
 * Time: 5:34 PM
 */

namespace Apitis\Spec;


use Broadway\Domain\DomainEventStream;
use Broadway\Domain\DomainMessage;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

class EventsMatcher extends BasicMatcher
{
    /**
     * @var Presenter
     */
    private $presenter;

    /**
     * @param Presenter $presenter
     */
    public function __construct(Presenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($name, $subject, array $arguments)
    {
        return 'recordEvents' === $name
        && $subject instanceof DomainEventStream;
    }

    /**
     * {@inheritdoc}
     */
    protected function matches($subject, array $arguments)
    {
        /** @var DomainEventStream $subject */
        /** @var DomainMessage $event */
        $k = 0;

        if($subject->getIterator()->count() != count($arguments)) {
            return false;
        }

        foreach($subject->getIterator() as $event)
        {
            if($event->getPayload() != $arguments[$k]) {
                return false;
            }

            ++$k;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFailureException($name, $subject, array $arguments)
    {

        $subjectEvents = "";
        $argumentEvents = "";

        foreach($subject->getIterator() as $event) {
            $subjectEvents .= $this->presenter->presentValue($event->getPayload());
        }

        foreach($arguments as $event) {
            $argumentEvents .= $this->presenter->presentValue($event);
        }

        return new FailureException(sprintf(
            'Events occured do not match expectations. Should be: %s, but is: %s',
            $argumentEvents,
            $subjectEvents
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function getNegativeFailureException($name, $subject, array $arguments)
    {
        return new FailureException(sprintf(
            'Expected %s not to record events %s, but it does.',
            $this->presenter->presentValue($subject),
            $this->presenter->presentValue($arguments)
        ));
    }

}