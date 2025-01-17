<?php

/**
 * @see       https://github.com/laminas/laminas-mvc for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Mvc\Controller;

use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\Exception;
use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\ConfigInterface;
use Laminas\ServiceManager\ServiceLocatorAwareInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\Stdlib\DispatchableInterface;

/**
 * Manager for loading controllers
 *
 * Does not define any controllers by default, but does add a validator.
 */
class ControllerManager extends AbstractPluginManager
{
    /**
     * We do not want arbitrary classes instantiated as controllers.
     *
     * @var bool
     */
    protected $autoAddInvokableClass = false;

    /**
     * Constructor
     *
     * After invoking parent constructor, add an initializer to inject the
     * service manager, event manager, and plugin manager
     *
     * @param  null|ConfigInterface $configuration
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        parent::__construct($configuration);
        // Pushing to bottom of stack to ensure this is done last
        $this->addInitializer([$this, 'injectControllerDependencies'], false);
    }

    /**
     * Inject required dependencies into the controller.
     *
     * @param  DispatchableInterface $controller
     * @param  ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function injectControllerDependencies($controller, ServiceLocatorInterface $serviceLocator)
    {
        if (!$controller instanceof DispatchableInterface) {
            return;
        }

        $parentLocator = $serviceLocator->getServiceLocator();

        if ($controller instanceof ServiceLocatorAwareInterface) {
            $controller->setServiceLocator($parentLocator->get('Laminas\ServiceManager\ServiceLocatorInterface'));
        }

        if ($controller instanceof EventManagerAwareInterface) {
            // If we have an event manager composed already, make sure it gets
            // injected with the shared event manager.
            // The AbstractController lazy-instantiates an EM instance, which
            // is why the shared EM injection needs to happen; the conditional
            // will always pass.
            $events = $controller->getEventManager();
            if (!$events instanceof EventManagerInterface) {
                $controller->setEventManager($parentLocator->get('EventManager'));
            } else {
                $events->setSharedManager($parentLocator->get('SharedEventManager'));
            }
        }

        if ($controller instanceof AbstractConsoleController) {
            $controller->setConsole($parentLocator->get('Console'));
        }

        if (method_exists($controller, 'setPluginManager')) {
            $controller->setPluginManager($parentLocator->get('ControllerPluginManager'));
        }
    }

    /**
     * Validate the plugin
     *
     * Ensure we have a dispatchable.
     *
     * @param  mixed $plugin
     * @return true
     * @throws Exception\InvalidControllerException
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof DispatchableInterface) {
            // we're okay
            return;
        }

        throw new Exception\InvalidControllerException(sprintf(
            'Controller of type %s is invalid; must implement Laminas\Stdlib\DispatchableInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }

    /**
     * Override: do not use peering service managers
     *
     * @param  string|array $name
     * @param  bool         $checkAbstractFactories
     * @param  bool         $usePeeringServiceManagers
     * @return bool
     */
    public function has($name, $checkAbstractFactories = true, $usePeeringServiceManagers = false)
    {
        return parent::has($name, $checkAbstractFactories, $usePeeringServiceManagers);
    }

    /**
     * Override: do not use peering service managers
     *
     * @param  string $name
     * @param  array $options
     * @param  bool $usePeeringServiceManagers
     * @return mixed
     */
    public function get($name, $options = [], $usePeeringServiceManagers = false)
    {
        return parent::get($name, $options, $usePeeringServiceManagers);
    }
}
