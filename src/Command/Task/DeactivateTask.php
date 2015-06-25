<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 6/22/15 - 10:49 PM
 */
namespace Prooph\Link\ProcessManager\Command\Task;

use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Link\ProcessManager\Model\Task\TaskId;

/**
 * Command DeactivateTask
 *
 * @package Prooph\Link\ProcessManager\Command\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class DeactivateTask extends Command
{
    /**
     * @param string $taskId
     * @return DeactivateTask
     */
    public static function withId($taskId)
    {
        Assertion::uuid($taskId);

        return new self(__CLASS__, ['task_id' => $taskId]);
    }

    /**
     * @return TaskId
     */
    public function taskId()
    {
        return TaskId::fromString($this->payload['task_id']);
    }
} 