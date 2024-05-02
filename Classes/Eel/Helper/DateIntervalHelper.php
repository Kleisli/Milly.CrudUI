<?php
namespace Milly\CrudUI\Eel\Helper;

use Milly\Tools\Service\ReflectionService;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Utility\ObjectAccess;

class DateIntervalHelper implements ProtectedContextAwareInterface
{

    public function format(\DateInterval $dateInterval, string $format)
    {
        return $dateInterval->format($format);
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
