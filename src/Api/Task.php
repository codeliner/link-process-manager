<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/17/15 - 6:51 PM
 */
namespace Prooph\Link\ProcessManager\Api;

use Assert\Assertion;
use Prooph\Link\Application\Service\AbstractRestController;
use Prooph\Link\Application\Service\ActionController;
use Prooph\Link\ProcessManager\Command\Workflow\UnlinkTask;
use Prooph\Link\ProcessManager\Command\Task\UpdateTaskMetadata;
use Prooph\Link\ProcessManager\Projection\Task\TaskFinder;
use Prooph\ServiceBus\CommandBus;

/**
 * Resource Task
 *
 * @package Prooph\Link\ProcessManager\Api
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class Task extends AbstractRestController implements ActionController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var TaskFinder
     */
    private $taskFinder;

    public function get($id)
    {
        Assertion::uuid($id);

        $task = $this->taskFinder->find($id);

        if (! $task) {
            return $this->notFoundAction();
        }

        return ['task' => $task];
    }

    public function getList()
    {
        $messageHandlerId = $this->getRequest()->getQuery('message_handler_id');

        if ($messageHandlerId) {
            Assertion::uuid($messageHandlerId);

            $taskCollection = $this->taskFinder->findTasksOfMessageHandler($messageHandlerId);
        } else {
            $taskCollection = $this->taskFinder->findAll();
        }

        return ['task_collection' => $taskCollection];
    }

    public function update($id, $data)
    {
        if (! array_key_exists('metadata', $data)) return $this->apiProblem(422, "No metadata given for the task");

        $this->commandBus->dispatch(UpdateTaskMetadata::to($data['metadata'], $id));

        return $this->accepted();
    }

    public function delete($id)
    {
        Assertion::uuid($id);

        $task = $this->taskFinder->find($id);

        //@todo: here we go
        //UnlinkTask command with workflowId and ProcessId which is passed to workflow to find process and invoke unlink task
        //Then dispatch event TaskWasUnlinked and let a process manager listen on the event to trigger a deactivate task command
        if (!$task) {
            return $this->notFoundAction();
        }

        $this->commandBus->dispatch(UnlinkTask::fromConnection($task['workflow_id'], $task['process_id'], $task['id']));

        return $this->accepted();
    }

    /**
     * @param CommandBus $commandBus
     * @return void
     */
    public function setCommandBus(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param TaskFinder $taskFinder
     */
    public function setTaskFinder(TaskFinder $taskFinder)
    {
        $this->taskFinder = $taskFinder;
    }
}