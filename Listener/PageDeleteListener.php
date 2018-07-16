<?php

declare(strict_types=1);

namespace RevisionTen\CMS\Listener;

use RevisionTen\CQRS\Interfaces\EventInterface;
use RevisionTen\CQRS\Interfaces\ListenerInterface;
use RevisionTen\CQRS\Services\CommandBus;

class PageDeleteListener extends PageBaseListener implements ListenerInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(CommandBus $commandBus, EventInterface $event): void
    {
        // Update the PageStreamRead Model.
        $pageUuid = $event->getCommand()->getAggregateUuid();
        // Unpublish the Page and update the PageStreamRead Model.
        $this->pageService->unpublishPage($pageUuid);
    }
}
