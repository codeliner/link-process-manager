<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 6/23/15 - 2:30 PM
 */
namespace Prooph\Link\ProcessManager\Infrastructure\Factory;

use Prooph\Link\ProcessManager\Model\Task\DisconnectMessageHandlerHandler;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DisconnectMessageHandlerHandlerFactory
 *
 * @package Prooph\Link\ProcessManager\Infrastructure\Factory
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class DisconnectMessageHandlerHandlerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return DisconnectMessageHandlerHandler
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new DisconnectMessageHandlerHandler(
            $serviceLocator->get('prooph.link.pm.message_handler_collection'),
            $serviceLocator->get('prooph.link.pm.task_collection')
        );
    }
}