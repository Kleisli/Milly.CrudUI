<?php
namespace Milly\CrudUI\Controller;

use Milly\CrudUI\Service\ConfigurationService;
use Milly\CrudUI\Service\ObjectService;
use Milly\CrudUI\View\FallbackFusionView;
use Milly\Tools\Service\ClassMappingService;
use Milly\Tools\Service\ReflectionService;
use Neos\Flow\Exception;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Mvc\ActionResponse;
use Neos\Flow\Mvc\View\ViewInterface;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Persistence\RepositoryInterface;
use Neos\Utility\Exception\PropertyNotAccessibleException;
use Neos\Utility\ObjectAccess;
use Neos\Flow\Annotations as Flow;

trait BaseControllerTrait
{

    //const ENTITY_CLASSNAME = MyClass::class;
    //protected string $theme = 'tailwind';

    /**
     * @Flow\Inject
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    #[Flow\Inject]
    protected ReflectionService $millyReflectionService;

    #[Flow\Inject]
    protected ClassMappingService $classMappingService;

    #[Flow\Inject]
    protected ConfigurationService $configurationService;

    #[Flow\Inject]
    protected ObjectService $objectService;

    protected function initializeController(ActionRequest $request, ActionResponse $response): void
    {
        $this->defaultViewObjectName = FallbackFusionView::class;
        parent::initializeController($request, $response);
    }

    public function initializeView(ViewInterface $view): void
    {
        $view->setOption('fusionPathPatterns', ['resource://Milly.CrudUI/Private/Views', 'resource://@package/Private/Views']);
        if($view instanceof FallbackFusionView) {
            $view->setOption('fallbackFusionPath', 'Milly/CrudUI/Default/' . $view->getControllerActionName());
        }
        $view->assign('millyCrudTheme', $this->theme ?? null);
        parent::initializeView($view);
    }

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
    protected function registerObjectArgument(): void
    {
        $modelClass = $this->getModelClass();
        $this->arguments->addNewArgument('object', $this->getModelClass());
        $this->arguments['object']->setValidator($this->validatorResolver->getBaseValidatorConjunction($modelClass, array('Default', 'Controller')));
        $this->mvcPropertyMappingConfigurationService->initializePropertyMappingConfigurationFromRequest($this->request, $this->arguments);
    }

    /**
     * @throws Exception
     */
    protected function getCrudUIConfiguration(string $view = ''): array
    {
        return $this->configurationService->getCrudUIConfiguration($this->getModelClass(), null, $view);
    }

    /**
     * @throws Exception
     */
    protected function getRepository(): RepositoryInterface
    {
        $repository = $this->objectManager->get($this->classMappingService->getRepositoryClassByModel($this->getModelClass()));
        if(!($repository instanceof RepositoryInterface)){
            throw new Exception("Could not instantiate " .$this->classMappingService->getRepositoryClassByModel($this->getModelClass()). " as Repository for Model ".$this->getModelClass());
        }
        return $repository;
    }

    /**
     * @throws Exception
     */
    protected function getModelClass(): string
    {
        if(defined('static::ENTITY_CLASSNAME')){
            return static::ENTITY_CLASSNAME;
        }else{
            return $this->classMappingService->convertClass(get_class($this), ClassMappingService::TYPE_MODEL);
        }
    }

    /**
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     * @throws Exception
     */
    public function redirectAfterAction($object): void
    {

        $config = $this->getCrudUIConfiguration();
        if(isset($config['parent'])){
            $this->redirectToParent($object);
        }

        $this->redirect('index');
    }

    /**
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     * @throws Exception
     * @throws PropertyNotAccessibleException
     */
    public function redirectToParent($object): void
    {
        $config = $this->getCrudUIConfiguration();
        $parentClass = $this->millyReflectionService->getTypeOfProperty($this->getModelClass(), $config['parent']);
        $controllerClass = $this->classMappingService->getControllerClassByModel($parentClass);
        $this->redirect(
            'show',
            $this->classMappingService->getControllerNameByModel($parentClass),
            $this->classMappingService->getPackageName($controllerClass),
            ['object' => ObjectAccess::getProperty($object, $config['parent'])]
        );
    }

    /**
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     * @throws Exception
     */
    public function showObject($object): void
    {
        $controllerClass = $this->classMappingService->getControllerClassByModel($object);
        $this->redirect(
            'show',
            $this->classMappingService->getControllerNameByModel($object),
            $this->classMappingService->getPackageName($controllerClass),
            ['object' => $object]
        );
    }

}
