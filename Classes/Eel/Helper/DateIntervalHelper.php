<?php declare(strict_types=1);
namespace Milly\CrudUI\Eel\Helper;

use Neos\Eel\ProtectedContextAwareInterface;

class DateIntervalHelper implements ProtectedContextAwareInterface
{

    public function format(\DateInterval $dateInterval, string $format): string
    {
        return $dateInterval->format($format);
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
