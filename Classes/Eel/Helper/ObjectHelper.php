<?php
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

    /**
     * @param object $object
     * @return string
     */
    public function identifier($object)
    {
        return $object ? $this->persistenceManager->getIdentifierByObject($object) : null;
    }

    public function getLabel(object $object){
        return $this->objectService->getLabel($object);
    }

    /**
     * @param object|null $object $object
     * @param string $property
     * @return mixed|null
     * @throws \Neos\Utility\Exception\PropertyNotAccessibleException
     */
    public function getProperty(?object $object, string $property)
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
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }

}
