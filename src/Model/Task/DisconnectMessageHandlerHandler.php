<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 6/23/15 - 1:23 PM
 */
namespace Prooph\Link\ProcessManager\Model\Task;

use Prooph\Link\ProcessManager\Command\Task\DisconnectMessageHandler;
use Prooph\Link\ProcessManager\Model\MessageHandler\Exception\MessageHandlerNotFound;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerCollection;
use Prooph\Link\ProcessManager\Model\Task\Exception\TaskNotFound;

/**
 * CommandHandler DisconnectMessageHandlerHandler
 *
 * @package Prooph\Link\ProcessManager\Model\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class DisconnectMessageHandlerHandler 
{
    /**
     * @var MessageHandlerCollection
     */
    private $messageHandlerCollection;

    /**
     * @var TaskCollection
     */
    private $taskCollection;

    /**
     * @param MessageHandlerCollection $messageHandlerCollection
     * @param TaskCollection $taskCollection
     */
    public function __construct(MessageHandlerCollection $messageHandlerCollection, TaskCollection $taskCollection)
    {
        $this->messageHandlerCollection = $messageHandlerCollection;
        $this->taskCollection = $taskCollection;
    }

    /**
     * @param DisconnectMessageHandler $command
     * @throws MessageHandlerNotFound
     * @throws TaskNotFound
     */
    public function handle(DisconnectMessageHandler $command)
    {
        $messageHandler = $this->messageHandlerCollection->get($command->messageHandlerId());

        if (!$messageHandler) {
            throw MessageHandlerNotFound::withId($command->messageHandlerId());
        }

        $task = $this->taskCollection->get($command->taskId());

        if (!$task) {
            throw TaskNotFound::withId($command->taskId());
        }

        $task->disconnectMessageHandler($messageHandler);
    }
} 