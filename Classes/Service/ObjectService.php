<?php
namespace Milly\CrudForms\Service;

use Milly\Tools\Service\ClassMappingService;
use Milly\Tools\Service\ReflectionService;
use Neos\ContentRepository\Domain\Factory\NodeFactory;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeTemplate;
use Neos\ContentRepository\Domain\Service\Context;
use Neos\ContentRepository\Domain\Service\ContextFactoryInterface;
use Neos\ContentRepository\Domain\Service\NodeTypeManager;
use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Repository\NodeDataRepository;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Exception;
use Neos\Flow\Mvc\Controller\ControllerContext;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Persistence\Doctrine\PersistenceManager;
use Neos\Flow\Persistence\RepositoryInterface;
use Neos\Utility\Arrays;
use Neos\Utility\ObjectAccess;
use Sandstorm\CrudForms\Exception\MissingModelTypeException;

/**
 * @Flow\Scope("singleton")
 */
class ObjectService
{
    #[Flow\Inject]
    protected ReflectionService $millyReflectionService;

    #[Flow\Inject]
    protected PersistenceManager $persistenceManager;


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

