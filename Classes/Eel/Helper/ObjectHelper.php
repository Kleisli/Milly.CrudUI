<?php
namespace Milly\CrudForms\Eel\Helper;

use Milly\Tools\Service\ReflectionService;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Utility\ObjectAccess;

class ObjectHelper implements ProtectedContextAwareInterface
{

    /**
     * @var PersistenceManagerInterface
     * @Flow\Inject
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject
     */
    protected ReflectionService $reflectionService;

    /**
     * @param $object Object
     * @return string
     */
    public function identifier($object) {

        return $object ? $this->persistenceManager->getIdentifierByObject($object) : null;
    }

    /**
     * @param object|null $object $object
     * @param string $property
     * @return mixed|null
     * @throws \Neos\Utility\Exception\PropertyNotAccessibleException
     */
    public function getProperty(?object $object, string $property) {
        if($object == null){
            return null;
        }
        return ObjectAccess::getPropertyPath($object, $property);
    }

    /**
     * @return mixed
     */
    public function getFromArray(array $array, string $key) {
        return $array[$key];
    }

    /**
     * @param $object
     * @return string
     */
    public function getControllerByObject($object){
        $className =  str_replace('Neos\\Flow\\Persistence\\Doctrine\\Proxies\\__CG__\\', '', $object::class);
        return explode('\\Domain\\Model\\', $className)[1];
    }

    /**
     * @param $object
     * @return string
     */
    public function getPackageByObject($object){
        $className =  str_replace('Neos\\Flow\\Persistence\\Doctrine\\Proxies\\__CG__\\', '', $object::class);
        return str_replace('\\', '.', explode('\\Domain\\Model\\', $className)[0]);
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
