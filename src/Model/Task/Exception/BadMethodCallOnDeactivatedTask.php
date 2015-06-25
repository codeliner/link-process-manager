<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 6/25/15 - 9:17 PM
 */
namespace Prooph\Link\ProcessManager\Model\Task\Exception;
use Prooph\Link\ProcessManager\Model\Task\TaskId;

/**
 * Exception BadMethodCallOnDeactivatedTask
 *
 * @package Prooph\Link\ProcessManager\Model\Task\Exception
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class BadMethodCallOnDeactivatedTask extends \BadMethodCallException
{
    /**
     * @param TaskId $taskId
     * @return BadMethodCallOnDeactivatedTask
     */
    public static function withId(TaskId $taskId) {
        return new self('Bad method call on deactivated task with id: ' . $taskId->toString());
    }
} 