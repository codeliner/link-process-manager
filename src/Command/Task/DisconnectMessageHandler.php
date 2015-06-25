<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 6/22/15 - 11:07 PM
 */
namespace Prooph\Link\ProcessManager\Command\Task;

use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\Task\TaskId;

/**
 * Command DisconnectMessageHandler
 *
 * With this command a message handler can be disconnected from its task.
 *
 * @package Prooph\Link\ProcessManager\Command\MessageHandler
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class DisconnectMessageHandler extends Command
{
    /**
     * @param string $messageHandlerId
     * @param string $taskId
     * @return DisconnectMessageHandler
     */
    public static function withId($messageHandlerId, $taskId)
    {
        Assertion::uuid($messageHandlerId);
        Assertion::uuid($taskId);

        return new self(__CLASS__, ['message_handler_id' => $messageHandlerId, 'task_id' => $taskId]);
    }

    /**
     * @return MessageHandlerId
     */
    public function messageHandlerId()
    {
        return MessageHandlerId::fromString($this->payload['message_handler_id']);
    }

    /**
     * @return TaskId
     */
    public function taskId()
    {
        return TaskId::fromString($this->payload['task_id']);
    }
} 