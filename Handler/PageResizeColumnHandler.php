<?php

declare(strict_types=1);

namespace RevisionTen\CMS\Handler;

use RevisionTen\CMS\Event\PageResizeColumnEvent;
use RevisionTen\CMS\Model\Page;
use RevisionTen\CQRS\Exception\CommandValidationException;
use RevisionTen\CQRS\Interfaces\AggregateInterface;
use RevisionTen\CQRS\Interfaces\CommandInterface;
use RevisionTen\CQRS\Interfaces\EventInterface;
use RevisionTen\CQRS\Interfaces\HandlerInterface;
use function in_array;
use function is_string;

final class PageResizeColumnHandler extends PageBaseHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     *
     * @var Page $aggregate
     */
    public function execute(EventInterface $event, AggregateInterface $aggregate): AggregateInterface
    {
        $payload = $event->getPayload();
        $uuid = $payload['uuid'];
        $size = (int) $payload['size'];
        $breakpoint = $payload['breakpoint'];

        // A function that resizes the column.
        $resizeColumnFunction = static function (&$element, &$collection) use ($size, $breakpoint) {
            $element['data']['width'.strtoupper($breakpoint)] = $size;
        };
        self::onElement($aggregate, $uuid, $resizeColumnFunction);

        $aggregate->state = Page::STATE_DRAFT;

        return $aggregate;
    }

    /**
     * {@inheritdoc}
     */
    public function createEvent(CommandInterface $command): EventInterface
    {
        return new PageResizeColumnEvent(
            $command->getAggregateUuid(),
            $command->getUuid(),
            $command->getOnVersion() + 1,
            $command->getUser(),
            $command->getPayload()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @var Page $aggregate
     */
    public function validateCommand(CommandInterface $command, AggregateInterface $aggregate): bool
    {
        $payload = $command->getPayload();
        $uuid = $payload['uuid'] ?? null;
        $element = is_string($uuid) ? self::getElement($aggregate, $uuid) : null;
        $size = (int) $payload['size'];
        $breakpoint = $payload['breakpoint'];

        // Check if breakpoint and size are valid.
        if ($size < 1 || $size > 12 || !in_array($breakpoint, ['xs', 'sm', 'md', 'lg', 'xl'])) {
            throw new CommandValidationException(
                'Size or breakpoint is invalid',
                CODE_BAD_REQUEST,
                NULL,
                $command
            );
        }

        if (null === $uuid) {
            throw new CommandValidationException(
                'No column uuid to resize is set',
                CODE_BAD_REQUEST,
                NULL,
                $command
            );
        }

        if (!$element) {
            throw new CommandValidationException(
                'Column with this uuid was not found'.$uuid,
                CODE_CONFLICT,
                NULL,
                $command
            );
        }

        return true;
    }
}
