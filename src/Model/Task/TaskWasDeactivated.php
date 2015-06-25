<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 6/25/15 - 9:20 PM
 */
namespace Prooph\Link\ProcessManager\Model\Task;

use Prooph\EventSourcing\AggregateChanged;

/**
 * Class TaskWasDeactivated
 *
 * @package Prooph\Link\ProcessManager\Model\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class TaskWasDeactivated extends AggregateChanged
{
    /**
     * @param TaskId $taskId
     * @return TaskWasDeactivated
     */
    public static function withId(TaskId $taskId)
    {
        return self::occur($taskId->toString(), []);
    }

    /**
     * @return TaskId
     */
    public function taskId()
    {
        return TaskId::fromString($this->aggregateId());
    }
} 