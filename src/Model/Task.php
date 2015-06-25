<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/6/15 - 4:57 PM
 */
namespace Prooph\Link\ProcessManager\Model;

use Prooph\EventSourcing\AggregateRoot;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\Task\Exception\BadMethodCallOnDeactivatedTask;
use Prooph\Link\ProcessManager\Model\Task\Exception\DisconnectUnknownMessageHandlerNotPossible;
use Prooph\Link\ProcessManager\Model\Task\MessageHandlerWasDisconnected;
use Prooph\Link\ProcessManager\Model\Task\TaskId;
use Prooph\Link\ProcessManager\Model\Task\TaskMetadataWasUpdated;
use Prooph\Link\ProcessManager\Model\Task\TaskType;
use Prooph\Link\ProcessManager\Model\Task\TaskWasDeactivated;
use Prooph\Link\ProcessManager\Model\Task\TaskWasSetUp;
use Prooph\Link\ProcessManager\Model\Workflow\Message;
use Prooph\Link\ProcessManager\Model\Workflow\MessageType;
use Prooph\Processing\Type\Prototype;

/**
 * Class Task
 *
 * In the process manager domain a task is an own aggregate because it needs to be modified by the client independent of
 * its process. But the task keeps a reference to the process so that it can be assigned to it when the whole workflow
 * is ported to the processing configuration.
 *
 * @package Prooph\Link\ProcessManager\Model
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class Task extends AggregateRoot
{
    /**
     * @var TaskId
     */
    private $id;

    /**
     * @var TaskType
     */
    private $taskType;

    /**
     * @var MessageHandlerId
     */
    private $messageHandlerId;

    /**
     * @var Prototype
     */
    private $processingType;

    /**
     * @var ProcessingMetadata
     */
    private $processingMetadata;

    /**
     * @var bool
     */
    private $active;

    /**
     * @param MessageHandler $messageHandler
     * @param TaskType $taskType
     * @param Prototype $processingType
     * @param ProcessingMetadata $metadata
     * @return Task
     */
    public static function setUp(MessageHandler $messageHandler, TaskType $taskType, Prototype $processingType, ProcessingMetadata $metadata)
    {
        $instance = new self();

        $instance->recordThat(TaskWasSetUp::with(TaskId::generate(), $taskType, $messageHandler, $processingType, $metadata));

        return $instance;
    }

    /**
     * Simply override existing metadata with the new one
     *
     * @param ProcessingMetadata $metadata
     * @throws Task\Exception\BadMethodCallOnDeactivatedTask
     */
    public function updateMetadata(ProcessingMetadata $metadata)
    {
        if (! $this->active) {
            throw BadMethodCallOnDeactivatedTask::withId($this->id());
        }
        $this->recordThat(TaskMetadataWasUpdated::record($this->id(), $metadata));
    }

    /**
     * @throws \RuntimeException
     * @throws Task\Exception\BadMethodCallOnDeactivatedTask
     * @return Message
     */
    public function emulateWorkflowMessage()
    {
        if (! $this->active) {
            throw BadMethodCallOnDeactivatedTask::withId($this->id());
        }

        if ($this->type()->isCollectData()) {
            $messageType = MessageType::collectData();
        } elseif ($this->type()->isProcessData()) {
            $messageType = MessageType::processData();
        } else {
            throw new \RuntimeException(sprintf(
                "Can't create a message type for task type %s",
                $this->type()->toString()
            ));
        }

        return Message::emulateProcessingWorkflowMessage($messageType, $this->processingType(), $this->metadata());
    }

    /**
     * @param MessageHandler $messageHandler
     * @throws Task\Exception\DisconnectUnknownMessageHandlerNotPossible
     */
    public function disconnectMessageHandler(MessageHandler $messageHandler)
    {
        if (! $this->messageHandlerId()->equals($messageHandler->messageHandlerId())) {
            throw DisconnectUnknownMessageHandlerNotPossible::forTask($this->id(), $messageHandler->messageHandlerId());
        }

        $this->recordThat(MessageHandlerWasDisconnected::fromTask($this->id(), $messageHandler->messageHandlerId()));
    }

    /**
     * Deactivates the task meaning methods like updateMetadata and emulateWorkflowMessage will throw exceptions
     * from now on!
     */
    public function deactivate()
    {
        $this->recordThat(TaskWasDeactivated::withId($this->id()));
    }

    /**
     * @return TaskId
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return TaskType
     */
    public function type()
    {
        return $this->taskType;
    }

    /**
     * @return MessageHandlerId
     */
    public function messageHandlerId()
    {
        return $this->messageHandlerId;
    }

    /**
     * @return ProcessingMetadata
     */
    public function metadata()
    {
        return $this->processingMetadata;
    }

    /**
     * @return Prototype
     */
    public function processingType()
    {
        return $this->processingType;
    }

    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        return $this->id->toString();
    }

    /**
     * @param TaskWasSetUp $event
     */
    protected function whenTaskWasSetUp(TaskWasSetUp $event)
    {
        $this->id = $event->taskId();
        $this->taskType = $event->taskType();
        $this->messageHandlerId = $event->messageHandlerId();
        $this->processingType = $event->processingType();
        $this->processingMetadata = $event->taskMetadata();
        $this->active = true;
    }

    /**
     * @param TaskMetadataWasUpdated $event
     */
    protected function whenTaskMetadataWasUpdated(TaskMetadataWasUpdated $event)
    {
        $this->processingMetadata = $event->taskMetadata();
    }

    /**
     * @param MessageHandlerWasDisconnected $event
     */
    protected function whenMessageHandlerWasDisconnected(MessageHandlerWasDisconnected $event)
    {
        $this->messageHandlerId = null;
    }

    /**
     * @param TaskWasDeactivated $event
     */
    protected function whenTaskWasDeactivated(TaskWasDeactivated $event)
    {
        $this->active = false;
    }
}