<?php

/**
 * @see       https://github.com/laminas/laminas-test for the canonical source repository
 * @copyright https://github.com/laminas/laminas-test/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-test/blob/master/LICENSE.md New BSD License
 */
namespace Laminas\Test\PHPUnit\Controller;

use Laminas\Console\Console;
use Laminas\EventManager\StaticEventManager;
use Laminas\Http\Request as HttpRequest;
use Laminas\Mvc\Application;
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\Exception\LogicException;
use Laminas\Stdlib\Parameters;
use Laminas\Stdlib\ResponseInterface;
use Laminas\Uri\Http as HttpUri;
use PHPUnit_Framework_ExpectationFailedException;
use PHPUnit_Framework_TestCase;

abstract class AbstractControllerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Laminas\Mvc\ApplicationInterface
     */
    protected $application;

    /**
     * @var array
     */
    protected $applicationConfig;

    /**
     * Flag to use console router or not
     * @var bool
     */
    protected $useConsoleRequest = false;

    /**
     * Flag console used before tests
     * @var bool
     */
    protected $usedConsoleBackup;

    /**
     * Trace error when exception is throwed in application
     * @var bool
     */
    protected $traceError = true;

    /**
     * Reset the application for isolation
     */
    protected function setUp()
    {
        $this->usedConsoleBackup = Console::isConsole();
        $this->reset();
    }

    /**
     * Restore params
     */
    protected function tearDown()
    {
        Console::overrideIsConsole($this->usedConsoleBackup);

        if (true !== $this->traceError) {
            return;
        }

        $exception = $this->getApplication()->getMvcEvent()->getParam('exception');
        if ($exception instanceof \Exception) {
            throw $exception;
        }
    }

    /**
     * Get the trace error flag
     * @return bool
     */
    public function getTraceError()
    {
        return $this->traceError;
    }

    /**
     * Set the trace error flag
     * @param  bool                       $traceError
     * @return AbstractControllerTestCase
     */
    public function setTraceError($traceError)
    {
        $this->traceError = $traceError;

        return $this;
    }

    /**
     * Get the usage of the console router or not
     * @return bool $boolean
     */
    public function getUseConsoleRequest()
    {
        return $this->useConsoleRequest;
    }

    /**
     * Set the usage of the console router or not
     * @param  bool                       $boolean
     * @return AbstractControllerTestCase
     */
    public function setUseConsoleRequest($boolean)
    {
        $this->useConsoleRequest = (bool) $boolean;

        return $this;
    }

    /**
     * Get the application config
     * @return array the application config
     */
    public function getApplicationConfig()
    {
        return $this->applicationConfig;
    }

    /**
     * Set the application config
     * @param  array                      $applicationConfig
     * @return AbstractControllerTestCase
     * @throws LogicException
     */
    public function setApplicationConfig($applicationConfig)
    {
        if (null !== $this->application && null !== $this->applicationConfig) {
            throw new LogicException(
                'Application config can not be set, the application is already built'
            );
        }

        // do not cache module config on testing environment
        if (isset($applicationConfig['module_listener_options']['config_cache_enabled'])) {
            $applicationConfig['module_listener_options']['config_cache_enabled'] = false;
        }
        $this->applicationConfig = $applicationConfig;

        return $this;
    }

    /**
     * Get the application object
     * @return \Laminas\Mvc\ApplicationInterface
     */
    public function getApplication()
    {
        if ($this->application) {
            return $this->application;
        }
        $appConfig = $this->applicationConfig;
        Console::overrideIsConsole($this->getUseConsoleRequest());
        $this->application = Application::init($appConfig);

        $events = $this->application->getEventManager();
        $events->detach($this->application->getServiceManager()->get('SendResponseListener'));

        return $this->application;
    }

    /**
     * Get the service manager of the application object
     * @return \Laminas\ServiceManager\ServiceManager
     */
    public function getApplicationServiceLocator()
    {
        return $this->getApplication()->getServiceManager();
    }

    /**
     * Get the application request object
     * @return \Laminas\Stdlib\RequestInterface
     */
    public function getRequest()
    {
        return $this->getApplication()->getRequest();
    }

    /**
     * Get the application response object
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->getApplication()->getMvcEvent()->getResponse();
    }

    /**
     * Set the request URL
     *
     * @param  string                     $url
     * @param  string|null                $method
     * @param  array|null                 $params
     * @return AbstractControllerTestCase
     */
    public function url($url, $method = HttpRequest::METHOD_GET, $params = [])
    {
        $request = $this->getRequest();
        if ($this->useConsoleRequest) {
            preg_match_all('/(--\S+[= ]"[^\s"]*\s*[^\s"]*")|(--\S+=\S+|--\S+\s\S+|\S+)/', $url, $matches);
            $params = str_replace([' "', '"'], ['=', ''], $matches[0]);
            $request->params()->exchangeArray($params);

            return $this;
        }

        $query       = $request->getQuery()->toArray();
        $post        = $request->getPost()->toArray();
        $uri         = new HttpUri($url);
        $queryString = $uri->getQuery();

        if ($queryString) {
            parse_str($queryString, $query);
        }

        if ($method == HttpRequest::METHOD_POST) {
            if (count($params) != 0) {
                $post = $params;
            }
        } elseif ($method == HttpRequest::METHOD_GET) {
            $query = array_merge($query, $params);
        } elseif ($method == HttpRequest::METHOD_PUT || $method == HttpRequest::METHOD_PATCH) {
            if (count($params) != 0) {
                $content = http_build_query($params);
                $request->setContent($content);
            }
        } elseif ($params) {
            trigger_error(
                'Additional params is only supported by GET, POST, PUT and PATCH HTTP method',
                E_USER_NOTICE
            );
        }

        $request->setMethod($method);
        $request->setQuery(new Parameters($query));
        $request->setPost(new Parameters($post));
        $request->setUri($uri);
        $request->setRequestUri($uri->getPath());

        return $this;
    }

    /**
     * Dispatch the MVC with a URL
     * Accept a HTTP (simulate a customer action) or console route.
     *
     * The URL provided set the request URI in the request object.
     *
     * @param  string      $url
     * @param  string|null $method
     * @param  array|null  $params
     * @throws \Exception
     */
    public function dispatch($url, $method = null, $params = [], $isXmlHttpRequest = false)
    {
        if (!isset($method)
            && $this->getRequest() instanceof HttpRequest
            && $requestMethod = $this->getRequest()->getMethod()
        ) {
            $method = $requestMethod;
        } elseif (!isset($method)) {
            $method = HttpRequest::METHOD_GET;
        }

        if ($isXmlHttpRequest) {
            $headers = $this->getRequest()->getHeaders();
            $headers->addHeaderLine('X_REQUESTED_WITH', 'XMLHttpRequest');
        }

        $this->url($url, $method, $params);
        $this->getApplication()->run();
    }

    /**
     * Reset the request
     *
     * @return AbstractControllerTestCase
     */
    public function reset($keepPersistence = false)
    {
        // force to re-create all components
        $this->application = null;

        // reset server data
        if (!$keepPersistence) {
            // Do not create a global session variable if it doesn't already
            // exist. Otherwise calling this function could mark tests risky,
            // as it changes global state.
            if (array_key_exists('_SESSION', $GLOBALS)) {
                $_SESSION = [];
            }
            $_COOKIE  = [];
        }

        $_GET     = [];
        $_POST    = [];

        // reset singleton
        StaticEventManager::resetInstance();

        return $this;
    }

    /**
     * Trigger an application event
     *
     * @param  string                                $eventName
     * @return \Laminas\EventManager\ResponseCollection
     */
    public function triggerApplicationEvent($eventName)
    {
        $events = $this->getApplication()->getEventManager();
        $event  = $this->getApplication()->getMvcEvent();

        if ($eventName != MvcEvent::EVENT_ROUTE && $eventName != MvcEvent::EVENT_DISPATCH) {
            return $events->trigger($eventName, $event);
        }

        $shortCircuit = function ($r) use ($event) {
            if ($r instanceof ResponseInterface) {
                return true;
            }

            if ($event->getError()) {
                return true;
            }

            return false;
        };

        return $events->trigger($eventName, $event, $shortCircuit);
    }

    /**
     * Assert modules were loaded with the module manager
     *
     * @param array $modules
     */
    public function assertModulesLoaded(array $modules)
    {
        $moduleManager = $this->getApplicationServiceLocator()->get('ModuleManager');
        $modulesLoaded = $moduleManager->getModules();
        $list          = array_diff($modules, $modulesLoaded);
        if ($list) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                sprintf('Several modules are not loaded "%s"', implode(', ', $list))
            );
        }
        $this->assertEquals(count($list), 0);
    }

    /**
     * Assert modules were not loaded with the module manager
     *
     * @param array $modules
     */
    public function assertNotModulesLoaded(array $modules)
    {
        $moduleManager = $this->getApplicationServiceLocator()->get('ModuleManager');
        $modulesLoaded = $moduleManager->getModules();
        $list          = array_intersect($modules, $modulesLoaded);
        if ($list) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                sprintf('Several modules WAS not loaded "%s"', implode(', ', $list))
            );
        }
        $this->assertEquals(count($list), 0);
    }

    /**
     * Retrieve the response status code
     *
     * @return int
     */
    protected function getResponseStatusCode()
    {
        $response = $this->getResponse();
        if (!$this->useConsoleRequest) {
            return $response->getStatusCode();
        }

        $match = $response->getErrorLevel();
        if (null === $match) {
            $match = 0;
        }

        return $match;
    }

    /**
     * Assert response status code
     *
     * @param int $code
     */
    public function assertResponseStatusCode($code)
    {
        if ($this->useConsoleRequest) {
            if (!in_array($code, [0, 1])) {
                throw new PHPUnit_Framework_ExpectationFailedException(
                    'Console status code assert value must be O (valid) or 1 (error)'
                );
            }
        }
        $match = $this->getResponseStatusCode();
        if ($code != $match) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                sprintf('Failed asserting response code "%s", actual status code is "%s"', $code, $match)
            );
        }
        $this->assertEquals($code, $match);
    }

    /**
     * Assert not response status code
     *
     * @param int $code
     */
    public function assertNotResponseStatusCode($code)
    {
        if ($this->useConsoleRequest) {
            if (!in_array($code, [0, 1])) {
                throw new PHPUnit_Framework_ExpectationFailedException(
                    'Console status code assert value must be O (valid) or 1 (error)'
                );
            }
        }
        $match = $this->getResponseStatusCode();
        if ($code == $match) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                sprintf('Failed asserting response code was NOT "%s"', $code)
            );
        }
        $this->assertNotEquals($code, $match);
    }

    /**
     * Assert the application exception and message
     *
     * @param $type application exception type
     * @param $message application exception message
     */
    public function assertApplicationException($type, $message = null)
    {
        $exception = $this->getApplication()->getMvcEvent()->getParam('exception');
        if (!$exception) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                'Failed asserting application exception, exception not exist'
            );
        }
        if (true === $this->traceError) {
            // set exception as null because we know and have assert the exception
            $this->getApplication()->getMvcEvent()->setParam('exception', null);
        }
        $this->setExpectedException($type, $message);
        throw $exception;
    }

    /**
     * Get the full current controller class name
     *
     * @return string
     */
    protected function getControllerFullClassName()
    {
        $routeMatch           = $this->getApplication()->getMvcEvent()->getRouteMatch();
        if (!$routeMatch) {
            throw new PHPUnit_Framework_ExpectationFailedException('No route matched');
        }
        $controllerIdentifier = $routeMatch->getParam('controller');
        $controllerManager    = $this->getApplicationServiceLocator()->get('ControllerManager');
        $controllerClass      = $controllerManager->get($controllerIdentifier);

        return get_class($controllerClass);
    }

    /**
     * Assert that the application route match used the given module
     *
     * @param string $module
     */
    public function assertModuleName($module)
    {
        $controllerClass = $this->getControllerFullClassName();
        $match           = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        $match           = strtolower($match);
        $module          = strtolower($module);
        if ($module != $match) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                sprintf('Failed asserting module name "%s", actual module name is "%s"', $module, $match)
            );
        }
        $this->assertEquals($module, $match);
    }

    /**
     * Assert that the application route match used NOT the given module
     *
     * @param string $module
     */
    public function assertNotModuleName($module)
    {
        $controllerClass = $this->getControllerFullClassName();
        $match           = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        $match           = strtolower($match);
        $module          = strtolower($module);
        if ($module == $match) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                sprintf('Failed asserting module was NOT "%s"', $module)
            );
        }
        $this->assertNotEquals($module, $match);
    }

    /**
     * Assert that the application route match used the given controller class
     *
     * @param string $controller
     */
    public function assertControllerClass($controller)
    {
        $controllerClass = $this->getControllerFullClassName();
        $match           = substr($controllerClass, strrpos($controllerClass, '\\')+1);
        $match           = strtolower($match);
        $controller      = strtolower($controller);
        if ($controller != $match) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                sprintf('Failed asserting controller class "%s", actual controller class is "%s"', $controller, $match)
            );
        }
        $this->assertEquals($controller, $match);
    }

    /**
     * Assert that the application route match used NOT the given controller class
     *
     * @param string $controller
     */
    public function assertNotControllerClass($controller)
    {
        $controllerClass = $this->getControllerFullClassName();
        $match           = substr($controllerClass, strrpos($controllerClass, '\\')+1);
        $match           = strtolower($match);
        $controller      = strtolower($controller);
        if ($controller == $match) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                sprintf('Failed asserting controller class was NOT "%s"', $controller)
            );
        }
        $this->assertNotEquals($controller, $match);
    }

    /**
     * Assert that the application route match used the given controller name
     *
     * @param string $controller
     */
    public function assertControllerName($controller)
    {
        $routeMatch = $this->getApplication()->getMvcEvent()->getRouteMatch();
        if (!$routeMatch) {
            throw new PHPUnit_Framework_ExpectationFailedException('No route matched');
        }
        $match      = $routeMatch->getParam('controller');
        $match      = strtolower($match);
        $controller = strtolower($controller);
        if ($controller != $match) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                sprintf('Failed asserting controller name "%s", actual controller name is "%s"', $controller, $match)
            );
        }
        $this->assertEquals($controller, $match);
    }

    /**
     * Assert that the application route match used NOT the given controller name
     *
     * @param string $controller
     */
    public function assertNotControllerName($controller)
    {
        $routeMatch = $this->getApplication()->getMvcEvent()->getRouteMatch();
        if (!$routeMatch) {
            throw new PHPUnit_Framework_ExpectationFailedException('No route matched');
        }
        $match      = $routeMatch->getParam('controller');
        $match      = strtolower($match);
        $controller = strtolower($controller);
        if ($controller == $match) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                sprintf('Failed asserting controller name was NOT "%s"', $controller)
            );
        }
        $this->assertNotEquals($controller, $match);
    }

    /**
     * Assert that the application route match used the given action
     *
     * @param string $action
     */
    public function assertActionName($action)
    {
        $routeMatch = $this->getApplication()->getMvcEvent()->getRouteMatch();
        if (!$routeMatch) {
            throw new PHPUnit_Framework_ExpectationFailedException('No route matched');
        }
        $match      = $routeMatch->getParam('action');
        $match      = strtolower($match);
        $action     = strtolower($action);
        if ($action != $match) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                sprintf('Failed asserting action name "%s", actual action name is "%s"', $action, $match)
            );
        }
        $this->assertEquals($action, $match);
    }

    /**
     * Assert that the application route match used NOT the given action
     *
     * @param string $action
     */
    public function assertNotActionName($action)
    {
        $routeMatch = $this->getApplication()->getMvcEvent()->getRouteMatch();
        if (!$routeMatch) {
            throw new PHPUnit_Framework_ExpectationFailedException('No route matched');
        }
        $match      = $routeMatch->getParam('action');
        $match      = strtolower($match);
        $action     = strtolower($action);
        if ($action == $match) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                sprintf('Failed asserting action name was NOT "%s"', $action)
            );
        }
        $this->assertNotEquals($action, $match);
    }

    /**
     * Assert that the application route match used the given route name
     *
     * @param string $route
     */
    public function assertMatchedRouteName($route)
    {
        $routeMatch = $this->getApplication()->getMvcEvent()->getRouteMatch();
        if (!$routeMatch) {
            throw new PHPUnit_Framework_ExpectationFailedException('No route matched');
        }
        $match      = $routeMatch->getMatchedRouteName();
        $match      = strtolower($match);
        $route      = strtolower($route);
        if ($route != $match) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                sprintf('Failed asserting matched route name was "%s", actual matched route name is "%s"', $route, $match)
            );
        }
        $this->assertEquals($route, $match);
    }

    /**
     * Assert that the application route match used NOT the given route name
     *
     * @param string $route
     */
    public function assertNotMatchedRouteName($route)
    {
        $routeMatch = $this->getApplication()->getMvcEvent()->getRouteMatch();
        if (!$routeMatch) {
            throw new PHPUnit_Framework_ExpectationFailedException('No route matched');
        }
        $match      = $routeMatch->getMatchedRouteName();
        $match      = strtolower($match);
        $route      = strtolower($route);
        if ($route == $match) {
            throw new PHPUnit_Framework_ExpectationFailedException(
                sprintf('Failed asserting route matched was NOT "%s"', $route)
            );
        }
        $this->assertNotEquals($route, $match);
    }

    /**
     * Assert that the application did not match any route
     */
    public function assertNoMatchedRoute()
    {
        $routeMatch = $this->getApplication()->getMvcEvent()->getRouteMatch();
        if ($routeMatch) {
            $match      = $routeMatch->getMatchedRouteName();
            $match      = strtolower($match);
            throw new PHPUnit_Framework_ExpectationFailedException(sprintf(
                'Failed asserting that no route matched, actual matched route name is "%s"',
                $match
            ));
        }
        $this->assertNull($routeMatch);
    }

    /**
     * Assert template name
     * Assert that a template was used somewhere in the view model tree
     *
     * @param string $templateName
     */
    public function assertTemplateName($templateName)
    {
        $viewModel = $this->getApplication()->getMvcEvent()->getViewModel();
        $this->assertTrue($this->searchTemplates($viewModel, $templateName));
    }

    /**
     * Assert not template name
     * Assert that a template was not used somewhere in the view model tree
     *
     * @param string $templateName
     */
    public function assertNotTemplateName($templateName)
    {
        $viewModel = $this->getApplication()->getMvcEvent()->getViewModel();
        $this->assertFalse($this->searchTemplates($viewModel, $templateName));
    }

    /**
     * Recursively search a view model and it's children for the given templateName
     *
     * @param  \Laminas\View\Model\ModelInterface $viewModel
     * @param  string    $templateName
     * @return boolean
     */
    protected function searchTemplates($viewModel, $templateName)
    {
        if ($viewModel->getTemplate($templateName) == $templateName) {
            return true;
        }
        foreach ($viewModel->getChildren() as $child) {
            return $this->searchTemplates($child, $templateName);
        }

        return false;
    }
}
