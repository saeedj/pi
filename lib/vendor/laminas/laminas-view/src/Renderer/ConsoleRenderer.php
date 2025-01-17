<?php

/**
 * @see       https://github.com/laminas/laminas-view for the canonical source repository
 * @copyright https://github.com/laminas/laminas-view/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-view/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\View\Renderer;

use Laminas\Filter\FilterChain;
use Laminas\View\Model\ModelInterface;
use Laminas\View\Resolver\ResolverInterface;

/**
 * Class for Laminas\View\Model\ConsoleModel to help enforce private constructs.
 *
 * Note: all private variables in this class are prefixed with "__". This is to
 * mark them as part of the internal implementation, and thus prevent conflict
 * with variables injected into the renderer.
 */
class ConsoleRenderer implements RendererInterface, TreeRendererInterface
{
    /**
     * @var FilterChain
     */
    protected $__filterChain;

    /**
     * Constructor.
     *
     *
     * @todo handle passing helper manager, options
     * @todo handle passing filter chain, options
     * @todo handle passing variables object, options
     * @todo handle passing resolver object, options
     * @param array $config Configuration key-value pairs.
     */
    public function __construct($config = [])
    {
        $this->init();
    }

    public function setResolver(ResolverInterface $resolver)
    {
        return $this;
    }

    /**
     * Return the template engine object
     *
     * Returns the object instance, as it is its own template engine
     *
     * @return ConsoleRenderer
     */
    public function getEngine()
    {
        return $this;
    }

    /**
     * Allow custom object initialization when extending ConsoleRenderer
     *
     * Triggered by {@link __construct() the constructor} as its final action.
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * Set filter chain
     *
     * @param  FilterChain $filters
     * @return ConsoleRenderer
     */
    public function setFilterChain(FilterChain $filters)
    {
        $this->__filterChain = $filters;
        return $this;
    }

    /**
     * Retrieve filter chain for post-filtering script content
     *
     * @return FilterChain
     */
    public function getFilterChain()
    {
        if (null === $this->__filterChain) {
            $this->setFilterChain(new FilterChain());
        }
        return $this->__filterChain;
    }

    /**
     * Recursively processes all ViewModels and returns output.
     *
     * @param  string|ModelInterface   $model        A ViewModel instance.
     * @param  null|array|\Traversable $values       Values to use when rendering. If none
     *                                               provided, uses those in the composed
     *                                               variables container.
     * @return string Console output.
     */
    public function render($model, $values = null)
    {
        if (!$model instanceof ModelInterface) {
            return '';
        }

        $result = '';
        $options = $model->getOptions();
        foreach ($options as $setting => $value) {
            $method = 'set' . $setting;
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
            unset($method, $setting, $value);
        }
        unset($options);

        $values = $model->getVariables();

        if (isset($values['result'])) {
            // filter and append the result
            $result .= $this->getFilterChain()->filter($values['result']);
        }

        if ($model->hasChildren()) {
            // recursively render all children
            foreach ($model->getChildren() as $child) {
                $result .= $this->render($child, $values);
            }
        }

        return $result;
    }

    /**
     * @see Laminas\View\Renderer\TreeRendererInterface
     * @return bool
     */
    public function canRenderTrees()
    {
        return true;
    }
}
