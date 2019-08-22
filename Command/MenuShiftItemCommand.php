<?php

declare(strict_types=1);

namespace RevisionTen\CMS\Command;

use RevisionTen\CMS\Handler\MenuShiftItemHandler;
use RevisionTen\CMS\Model\Menu;
use RevisionTen\CQRS\Command\Command;
use RevisionTen\CQRS\Interfaces\CommandInterface;

final class MenuShiftItemCommand extends Command implements CommandInterface
{
    public const HANDLER = MenuShiftItemHandler::class;
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
