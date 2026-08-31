<?php declare(strict_types=1);
namespace Milly\CrudUI\Eel\Helper;

use Milly\CrudUI\Service\ObjectService;
use Milly\Tools\Service\ReflectionService;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Utility\ObjectAccess;

class ObjectHelper implements ProtectedContextAwareInterface
{
    #[Flow\Inject]
    protected PersistenceManagerInterface $persistenceManager;

    #[Flow\Inject]
    protected ReflectionService $reflectionService;

    #[Flow\Inject]
    protected ObjectService $objectService;

    public function identifier(?object $object): ?string
    {
        return $object ? $this->persistenceManager->getIdentifierByObject($object) : null;
    }

    public function getLabel(object $object): ?string
    {
        return $this->objectService->getLabel($object);
    }

    /**
     * @throws \Neos\Utility\Exception\PropertyNotAccessibleException
     */
    public function getProperty(?object $object, string $property): mixed
    {
        if($object == null){
            return null;
        }
        return ObjectAccess::getPropertyPath($object, $property);
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
