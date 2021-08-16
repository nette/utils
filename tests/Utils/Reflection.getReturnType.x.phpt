<?php

/**
 * Test: Nette\Utils\Reflection::getReturnType
 * @phpversion 7
 */

require __DIR__ . '/../bootstrap.php';

class FunctionReflection
{
}

interface FunctionReflectionFactory
{

    public function create(
        \ReflectionFunction $reflection,
        array $phpDocParameterTypes
    ): FunctionReflection|string;

}

dump(Nette\Utils\Reflection::getReturnType(new \ReflectionMethod(FunctionReflectionFactory::class, 'create')));
