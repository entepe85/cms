<?php

declare(strict_types=1);

namespace RevisionTen\CMS\Handler;

use RevisionTen\CMS\Command\MenuEnableItemCommand;
use RevisionTen\CMS\Event\MenuEnableItemEvent;
use RevisionTen\CMS\Model\Menu;
use RevisionTen\CQRS\Interfaces\AggregateInterface;
use RevisionTen\CQRS\Interfaces\CommandInterface;
use RevisionTen\CQRS\Interfaces\EventInterface;
use RevisionTen\CQRS\Interfaces\HandlerInterface;
use RevisionTen\CQRS\Message\Message;

final class MenuEnableItemHandler extends MenuBaseHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     *
     * @var Menu $aggregate
     */
    public function execute(CommandInterface $command, AggregateInterface $aggregate): AggregateInterface
    {
        $payload = $command->getPayload();
        $uuid = $payload['uuid'];

        // A function that enables the item.
        $enableItemFunction = function (&$item, &$collection) {
            $item['enabled'] = true;
        };
        self::onItem($aggregate, $uuid, $enableItemFunction);

        return $aggregate;
    }

    /**
     * {@inheritdoc}
     */
    public static function getCommandClass(): string
    {
        return MenuEnableItemCommand::class;
    }

    /**
     * {@inheritdoc}
     */
    public function createEvent(CommandInterface $command): EventInterface
    {
        return new MenuEnableItemEvent($command);
    }

    /**
     * {@inheritdoc}
     *
     * @var Menu $aggregate
     */
    public function validateCommand(CommandInterface $command, AggregateInterface $aggregate): bool
    {
        $payload = $command->getPayload();
        // The uuid to enable.
        $uuid = $payload['uuid'];
        $item = self::getItem($aggregate, $uuid);

        if (!isset($uuid)) {
            $this->messageBus->dispatch(new Message(
                'No uuid to enable is set',
                CODE_BAD_REQUEST,
                $command->getUuid(),
                $command->getAggregateUuid()
            ));

            return false;
        } elseif (!$item) {
            $this->messageBus->dispatch(new Message(
                'Item with this uuid was not found '.$uuid,
                CODE_CONFLICT,
                $command->getUuid(),
                $command->getAggregateUuid()
            ));

            return false;
        } else {
            return true;
        }
    }
}
