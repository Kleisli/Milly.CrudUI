<?php
namespace Milly\CrudForms\Controller;

use Milly\CrudForms\Service\ConfigurationService;
use Milly\Tools\Service\ClassMappingService;
use Milly\Tools\Service\ReflectionService;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Exception;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Persistence\RepositoryInterface;
use Neos\FluidAdaptor\View\TemplateView;
use Neos\Fusion\View\FusionView;
use Neos\Utility\ObjectAccess;
use Sandstorm\CrudForms\Exception\MissingModelTypeException;
use Sandstorm\CrudForms\View\ExtendedTemplateView;
use Neos\Flow\Annotations as Flow;

trait BaseControllerTrait
{

    //const ENTITY_CLASSNAME;

    /**
     * @Flow\Inject
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @Flow\Inject
     */
    protected ReflectionService $millyReflectionService;

    /**
     * @Flow\Inject
     */
    protected ConfigurationService $configurationService;


    /**
     * @return void
     * @throws \Neos\Flow\Exception
     * @throws \Neos\Flow\Mvc\Exception\NoSuchArgumentException
     * @throws \Neos\Flow\Security\Exception\InvalidArgumentForHashGenerationException
     * @throws \Neos\Flow\Security\Exception\InvalidHashException
     * @throws \Neos\Flow\Validation\Exception\InvalidValidationConfigurationException
     * @throws \Neos\Flow\Validation\Exception\InvalidValidationOptionsException
     * @throws \Neos\Flow\Validation\Exception\NoSuchValidatorException
     */
    protected function registerObjectArgument()
    {
        $modelClass = $this->getModelClass();
        $this->arguments->addNewArgument('object', $this->getModelClass());
        $this->arguments['object']->setValidator($this->validatorResolver->getBaseValidatorConjunction($modelClass, array('Default', 'Controller')));
        $this->mvcPropertyMappingConfigurationService->initializePropertyMappingConfigurationFromRequest($this->request, $this->arguments);
    }

    /**
     * @return array
     * @throws \Neos\Flow\Exception
     */
    protected function getCrudFormsConfiguration($view = ''){
        return $this->configurationService->getCrudFormsConfiguration($this->getModelClass(), null, $view);
    }

    /**
     * @return RepositoryInterface
     * @throws \Neos\Flow\Exception
     */
    protected function getRepository(){
        $repository = $this->objectManager->get($this->millyReflectionService->getRepositoryClassByModel($this->getModelClass()));
        if(!($repository instanceof RepositoryInterface)){
            throw new Exception("Could not instantiate " .$this->millyReflectionService->getRepositoryClassByModel($this->getModelClass()). " as Repository for Model ".$this->getModelClass());
        }
        return $repository;
    }

    /**
     * @return string
     * @throws \Neos\Flow\Exception
     */
    protected function getModelClass(){
        return defined('static::ENTITY_CLASSNAME') ? static::ENTITY_CLASSNAME : ClassMappingService::getModelClass(get_class($this));
    }

    /**
     * @param $object
     * @return void
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     */
    public function redirectAfterAction($object){

        $config = $this->getCrudFormsConfiguration();
        if(isset($config['parent'])){
            $this->redirectToParent($object);
        }

        $this->redirect('index');
    }

    /**
     * @param $object
     * @return void
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     */
    public function redirectToParent($object){
        $config = $this->getCrudFormsConfiguration();
        $parentClass = $this->millyReflectionService->getTypeOfProperty($this->getModelClass(), $config['parent']);
        $this->redirect(
            'show',
            ClassMappingService::getControllerName($this->millyReflectionService->getControllerClassByModel($parentClass)),
            ClassMappingService::getPackageName($parentClass),
            ['object' => ObjectAccess::getProperty($object, $config['parent'])]
        );
    }

    /**
     * @param $object
     * @return void
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     */
    public function showObject($object){
        $this->redirect(
            'show',
            ClassMappingService::getControllerName($this->millyReflectionService->getControllerClassByModel($object::class)),
            ClassMappingService::getPackageName($object::class),
            ['object' => $object]
        );
    }

}
