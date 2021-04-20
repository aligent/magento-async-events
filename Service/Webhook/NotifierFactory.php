<?php

namespace Aligent\Webhooks\Service\Webhook;

/**
 * Class NotifierFactory
 *
 * Not really a "factory", because this is a factory that returns an interface (and we don't know what implementations
 * are available yet), we need explicit mapping of a type - implementation from outside (DB, di.xml) etc.
 *
 * And once we have that data, instead of using the object manager to instantiate it directly, we'll inject them as
 * parameters.
 */
class NotifierFactory implements NotifierFactoryInterface
{
    /**
     * @var array
     */
    private array $notifierClasses;

    /**
     * NotifierFactory constructor.
     *
     * @param array $notifierClasses
     */
    public function __construct(array $notifierClasses = [])
    {
        $this->notifierClasses = $notifierClasses;
    }

    /**
     * {@inheritDoc}
     */
    public function create(string $type): NotifierInterface
    {
        $notifier = $this->notifierClasses[$type] ?? null;

        if (!$notifier) {
            // Fallback to default notifier
            $notifier = $this->notifierClasses["default"] ?? null;

            if (!$notifier) {
                throw new \InvalidArgumentException(__("Could not find a notifier to handle type: {$type}"));
            }
        }

        return $notifier;
    }
}
