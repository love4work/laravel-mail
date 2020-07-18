<?php

namespace Love4Work\Laravel\Mail;

use Illuminate\Support\Reflector;
use Love4Work\Laravel\Mail\Contracts\MailerExtension as MailerExtensionContract;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;

class MailerExtension implements MailerExtensionContract
{
    /**
     * @var array
     */
    private array $hooks = [];

    /**
     * @param $function
     * @param $extension
     */
    public function extend($function, $extension)
    {
        $this->hooks = array_merge_recursive($this->hooks, [$function => $extension]);
    }

    /**
     * @return array
     */
    public function hooks()
    {
        return $this->hooks;
    }

    /**
     * @param $function
     * @param $object
     */
    public function handle($function, &$object)
    {
        if(!isset($this->hooks[$function]) || empty($this->hooks[$function])) return;

        $hooks = is_array($this->hooks[$function]) ? $this->hooks[$function] : [$this->hooks[$function]];

        foreach ($hooks as $hook) {
            $parameters = $this->resolveClassMethodDependencies(
                $object, $hook
            );
            $object->{$hook}(...array_values($parameters));
        }
    }

    /**
     * @param $instance
     * @param $method
     * @return array
     * @throws \ReflectionException
     */
    protected function resolveClassMethodDependencies($instance, $method)
    {
        if (! method_exists($instance, $method)) {
            return [];
        }

        return $this->resolveMethodDependencies(
            new ReflectionMethod($instance, $method)
        );
    }

    /**
     * Resolve the given method's type-hinted dependencies.
     *
     * @param  \ReflectionFunctionAbstract  $reflector
     * @return array
     */
    protected function resolveMethodDependencies(ReflectionFunctionAbstract $reflector)
    {
        $instanceCount = 0;
        $parameters = [];
        $skippableValue = new \stdClass;

        foreach ($reflector->getParameters() as $key => $parameter) {
            $instance = $this->transformDependency($parameter, $skippableValue);

            if ($instance !== $skippableValue) {
                $instanceCount++;

                $this->spliceIntoParameters($parameters, $key, $instance);
            } elseif (! isset($values[$key - $instanceCount]) &&
                $parameter->isDefaultValueAvailable()) {
                $this->spliceIntoParameters($parameters, $key, $parameter->getDefaultValue());
            }
        }

        return $parameters;
    }

    /**
     * @param ReflectionParameter $parameter
     * @param $skippableValue
     * @return mixed|string|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function transformDependency(ReflectionParameter $parameter, $skippableValue)
    {
        $className = Reflector::getParameterClassName($parameter);

        if ($className) {
            return $parameter->isDefaultValueAvailable() ? null : app()->make($className);
        }

        return $skippableValue;
    }

    /**
     * @param array $parameters
     * @param $offset
     * @param $value
     */
    protected function spliceIntoParameters(array &$parameters, $offset, $value)
    {
        array_splice(
            $parameters, $offset, 0, [$value]
        );
    }

}