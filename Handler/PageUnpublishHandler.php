<?php

declare(strict_types=1);

namespace RevisionTen\CMS\Handler;

use RevisionTen\CMS\Command\PageUnpublishCommand;
use RevisionTen\CMS\Event\PageUnpublishEvent;
use RevisionTen\CMS\Model\Page;
use RevisionTen\CQRS\Interfaces\AggregateInterface;
use RevisionTen\CQRS\Interfaces\CommandInterface;
use RevisionTen\CQRS\Interfaces\EventInterface;
use RevisionTen\CQRS\Interfaces\HandlerInterface;

final class PageUnpublishHandler extends PageBaseHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     *
     * @var Page $aggregate
     */
    public function execute(CommandInterface $command, AggregateInterface $aggregate): AggregateInterface
    {
        $aggregate->published = false;
        $aggregate->state = Page::STATE_UNPUBLISHED;

        // Check if state needs to be set to scheduled.
        if ($aggregate->schedule) {
            $scheduled = false;
            foreach ($aggregate->schedule as $schedule) {
                $startDate = $schedule['startDate'] ?? false;
                if ($startDate && $startDate > time()) {
                    $scheduled = true;
                }
            }

            if ($scheduled) {
                $aggregate->state = Page::STATE_SCHEDULED;
            }
        }

        return $aggregate;
    }

    /**
     * {@inheritdoc}
     */
    public static function getCommandClass(): string
    {
        return PageUnpublishCommand::class;
    }

    /**
     * {@inheritdoc}
     */
    public function createEvent(CommandInterface $command): EventInterface
    {
        return new PageUnpublishEvent($command);
    }

    /**
     * {@inheritdoc}
     */
    public function validateCommand(CommandInterface $command, AggregateInterface $aggregate): bool
    {
        return true;
    }
}
