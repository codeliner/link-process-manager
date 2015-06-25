<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 6/23/15 - 1:39 PM
 */
namespace Prooph\Link\ProcessManager\Model\Task\Exception;

use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\Task\TaskId;

/**
 * Exception DisconnectUnknownMessageHandlerNotPossible
 *
 * @package Prooph\Link\ProcessManager\Model\Task\Exception
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class DisconnectUnknownMessageHandlerNotPossible extends \InvalidArgumentException
{
    /**
     * @param TaskId $taskId
     * @param MessageHandlerId $messageHandlerId
     * @return DisconnectUnknownMessageHandlerNotPossible
     */
    public static function forTask(TaskId $taskId, MessageHandlerId $messageHandlerId)
    {
        return new self(sprintf(
            'Failed to disconnect message handler (%s) from task (%s). Message handler not connected with task.',
            $messageHandlerId->toString(),
            $taskId->toString()
        ));
    }
} 