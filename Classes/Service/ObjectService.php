<?php
namespace Milly\CrudUI\Service;

use Doctrine\ORM\ORMException;
use Milly\Tools\Service\ReflectionService;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Doctrine\PersistenceManager;
use Neos\Utility\Exception\InvalidTypeException;
use Neos\Utility\Exception\PropertyNotAccessibleException;
use Neos\Utility\ObjectAccess;

/**
 * @Flow\Scope("singleton")
 */
class ObjectService
{
    #[Flow\Inject]
    protected ReflectionService $millyReflectionService;

    #[Flow\Inject]
    protected PersistenceManager $persistenceManager;


    /**
     * @throws PropertyNotAccessibleException
     */
    public function getLabel(object $object): ?string
    {
        if (ObjectAccess::isPropertyGettable($object, 'label')) {
            return ObjectAccess::getProperty($object, 'label');
        } else {
            if (ObjectAccess::isPropertyGettable($object, 'title')) {
                return ObjectAccess::getProperty($object, 'title');
            } else {
                if (ObjectAccess::isPropertyGettable($object, 'name')) {
                    return ObjectAccess::getProperty($object, 'name');
                }
            }
        }
        return null;
    }

    /**
     * @throws PropertyNotAccessibleException
     * @throws ORMException
     * @throws InvalidTypeException
     */
    public function updateCollectionElements(object &$object, array $addElements = [], array $removeElements = []){
        foreach ($addElements as $propertyName => $elementIdentifiers){
            if(is_array($elementIdentifiers)) {
                $type = $this->millyReflectionService->getTypeOfRelation($object::class, $propertyName);
                foreach ($elementIdentifiers as $elementIdentifier) {
                    if ($elementIdentifier != '') {
                        $element = $this->persistenceManager->getObjectByIdentifier($elementIdentifier, $type);
                        $addMethod = 'add' . ucfirst($propertyName);
                        if (is_callable([$object, $addMethod])) {
                            $object->$addMethod([$element]);
                        } else {
                            $collection = ObjectAccess::getProperty($object, $propertyName);
                            $collection->add($element);
                            ObjectAccess::setProperty($object, $propertyName, $collection);
                        }
                    }
                }
            }
        }
        foreach ($removeElements as $propertyName => $elementIdentifiers){
            if(is_array($elementIdentifiers)) {
                $type = $this->millyReflectionService->getTypeOfRelation($object::class, $propertyName);
                foreach ($elementIdentifiers as $elementIdentifier) {
                    if ($elementIdentifier != '') {
                        $element = $this->persistenceManager->getObjectByIdentifier($elementIdentifier, $type);
                        $removeMethod = 'remove' . ucfirst($propertyName);
                        if (is_callable([$object, $removeMethod])) {
                            $object->$removeMethod([$element]);
                        } else {
                            $collection = ObjectAccess::getProperty($object, $propertyName);
                            $collection->removeElement($element);
                            ObjectAccess::setProperty($object, $propertyName, $collection);
                        }
                    }
                }
            }
        }
    }

}

