<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 6/23/15 - 1:46 PM
 */
namespace Prooph\Link\ProcessManager\Model\Task;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;

/**
 * Domain Event MessageHandlerWasDisconnected
 *
 * @package Prooph\Link\ProcessManager\Model\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class MessageHandlerWasDisconnected extends AggregateChanged
{
    private $taskId;

    private $messageHandlerId;

    /**
     * @param TaskId $taskId
     * @param MessageHandlerId $messageHandlerId
     *
     * @return MessageHandlerWasDisconnected
     */
    public static function fromTask(TaskId $taskId, MessageHandlerId $messageHandlerId)
    {
        $event = self::occur($taskId->toString(), ['message_handler_id' => $messageHandlerId->toString()]);

        $event->taskId = $taskId;
        $event->messageHandlerId = $messageHandlerId;

        return $event;
    }

    /**
     * @return TaskId
     */
    public function taskId()
    {
        if (is_null($this->taskId)) {
            $this->taskId = TaskId::fromString($this->aggregateId());
        }
        return $this->taskId;
    }

    /**
     * @return MessageHandlerId
     */
    public function messageHandlerId()
    {
        if (is_null($this->messageHandlerId)) {
            $this->messageHandlerId = MessageHandlerId::fromString($this->payload['message_handler_id']);
        }
        return $this->messageHandlerId;
    }
} 