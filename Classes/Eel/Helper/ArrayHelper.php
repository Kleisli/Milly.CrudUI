<?php
namespace Milly\CrudUI\Eel\Helper;

use Milly\Tools\Service\ReflectionService;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Utility\ObjectAccess;

class ArrayHelper implements ProtectedContextAwareInterface
{

    /**
     * @return mixed
     */
    public function hasValue(array $array, mixed $value) {
        return in_array($value, $array);
    }

    /**
     * All methods are considered safe
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }

}
