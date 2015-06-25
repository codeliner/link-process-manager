<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 6/25/15 - 9:13 PM
 */
namespace Prooph\Link\ProcessManager\Model\Task;

use Prooph\Link\ProcessManager\Command\Task\DeactivateTask;
use Prooph\Link\ProcessManager\Model\Task\Exception\TaskNotFound;

/**
 * CommandHandler DeactivateTaskHandler
 *
 * @package Prooph\Link\ProcessManager\Model\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class DeactivateTaskHandler 
{
    /**
     * @var TaskCollection
     */
    private $taskCollection;

    /**
     * @param TaskCollection $taskCollection
     */
    public function __construct(TaskCollection $taskCollection)
    {
        $this->taskCollection = $taskCollection;
    }

    /**
     * @param DeactivateTask $command
     * @throws Exception\TaskNotFound
     */
    public function handle(DeactivateTask $command)
    {
        $task = $this->taskCollection->get($command->taskId());

        if (! $task) {
            throw TaskNotFound::withId($command->taskId());
        }

        $task->deactivate();
    }
} 