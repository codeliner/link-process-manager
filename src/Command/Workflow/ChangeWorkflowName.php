<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/12/15 - 5:00 PM
 */
namespace Prooph\Link\ProcessManager\Command\Workflow;

use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;

/**
 * Command ChangeWorkflowName
 *
 * @package Prooph\Link\ProcessManager\Command\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ChangeWorkflowName extends Command
{
    /**
     * @param string $newName
     * @param string $workflowId
     * @return ChangeWorkflowName
     */
    public static function to($newName, $workflowId)
    {
        Assertion::string($newName);
        Assertion::notEmpty($newName);
        Assertion::string($workflowId);
        Assertion::notEmpty($workflowId);

        return new self(__CLASS__, [
            'workflow_id' => $workflowId,
            'name' => $newName
        ]);
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return WorkflowId::fromString($this->payload['workflow_id']);
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->payload['name'];
    }
}