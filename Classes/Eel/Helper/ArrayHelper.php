<?php declare(strict_types=1);
namespace Milly\CrudUI\Eel\Helper;

use Neos\Eel\ProtectedContextAwareInterface;

class ArrayHelper implements ProtectedContextAwareInterface
{
    /**
     * @param array<mixed> $array
     */
    public function hasValue(array $array, mixed $value): bool {
        return in_array($value, $array);
    }

    /**
     * All methods are considered safe
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }

}
