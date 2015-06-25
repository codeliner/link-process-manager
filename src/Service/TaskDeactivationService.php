<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 6/22/15 - 10:40 PM
 */
namespace Prooph\Link\ProcessManager\Service;

use Prooph\Link\ProcessManager\Command\Task\DisconnectMessageHandler;
use Prooph\Link\ProcessManager\Command\Task\DeactivateTask;
use Prooph\Link\ProcessManager\Model\Task\MessageHandlerWasDisconnected;
use Prooph\Link\ProcessManager\Model\Workflow\TaskWasUnlinked;
use Prooph\Link\ProcessManager\Projection\Task\TaskFinder;
use Prooph\ServiceBus\CommandBus;

/**
 * ProcessManager TaskDeactivationService
 *
 * The TaskDeactivationService listens on TaskWasUnlinked domain events and triggers follow up commands to
 * disconnect the linked message handler and deactivate the task.
 *
 * @package Prooph\Link\ProcessManager\Service
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class TaskDeactivationService
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var TaskFinder
     */
    private $taskFinder;

    /**
     * @param CommandBus $commandBus
     * @param TaskFinder $taskFinder
     */
    public function __construct(CommandBus $commandBus, TaskFinder $taskFinder)
    {
        $this->commandBus = $commandBus;
        $this->taskFinder = $taskFinder;
    }

    /**
     * @param TaskWasUnlinked $event
     */
    public function onTaskWasUnlinked(TaskWasUnlinked $event)
    {
        $task = $this->taskFinder->find($event->taskId()->toString());

        if ($task && $task['message_handler_id']) {
            $this->commandBus->dispatch(DisconnectMessageHandler::withId($task['message_handler_id'], $task['id']));
        } else {
            $this->commandBus->dispatch(DeactivateTask::withId($event->taskId()->toString()));
        }
    }

    /**
     * @param MessageHandlerWasDisconnected $event
     */
    public function onMessageHandlerWasDisconnected(MessageHandlerWasDisconnected $event)
    {
        $this->commandBus->dispatch(DeactivateTask::withId($event->taskId()->toString()));
    }
} 