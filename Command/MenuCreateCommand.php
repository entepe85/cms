<?php

declare(strict_types=1);

namespace RevisionTen\CMS\Command;

use RevisionTen\CMS\Handler\MenuCreateHandler;
use RevisionTen\CMS\Model\Menu;
use RevisionTen\CQRS\Command\Command;
use RevisionTen\CQRS\Interfaces\CommandInterface;

final class MenuCreateCommand extends Command implements CommandInterface
{
    public const HANDLER = MenuCreateHandler::class;
    public const AGGREGATE = Menu::class;

    /**
     * {@inheritdoc}
     */
    public static function getHandlerClass(): string
    {
        return self::HANDLER;
    }

    /**
     * {@inheritdoc}
     */
    public static function getAggregateClass(): string
    {
        return self::AGGREGATE;
    }
}
