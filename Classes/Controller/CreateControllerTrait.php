<?php
namespace Milly\CrudUI\Controller;
trait CreateControllerTrait
{

    /**
     * @throws \Neos\Flow\Validation\Exception\InvalidValidationConfigurationException
     * @throws \Neos\Flow\Validation\Exception\InvalidValidationOptionsException
     * @throws \Neos\Flow\Exception
     * @throws \Neos\Flow\Security\Exception\InvalidArgumentForHashGenerationException
     * @throws \Neos\Flow\Mvc\Exception\NoSuchArgumentException
     * @throws \Neos\Flow\Security\Exception\InvalidHashException
     * @throws \Neos\Flow\Validation\Exception\NoSuchValidatorException
     */
    protected function initializeNewAction(): void
    {
        $config = $this->getCrudUIConfiguration();
        if(isset($config['parent'])){
            $parentClass = $this->millyReflectionService->getTypeOfProperty($this->getModelClass(), $config['parent']);
            $this->arguments->addNewArgument('parent', $parentClass, false);
            $this->arguments['parent']->setValidator($this->validatorResolver->getBaseValidatorConjunction($parentClass, array('Default', 'Controller')));
            $this->mvcPropertyMappingConfigurationService->initializePropertyMappingConfigurationFromRequest($this->request, $this->arguments);
        }
    }

    public function newAction(array $preset = []): void
    {
        if(isset($this->arguments['parent'])) {
            $this->view->assign('parent', $this->arguments['parent']->getValue());
        }
        $this->view->assign('preset', $preset);
        $this->view->assign('CrudUIModelClass', $this->getModelClass());
    }

    protected function initializeCreateAction(): void
    {
        $this->registerObjectArgument();
    }

    public function createAction(): void
    {
        $object = $this->arguments['object']->getValue();
        $this->getRepository()->add($object);
        if (method_exists($this, 'afterCreateAction')) {
            $this->afterCreateAction($object);
        }
        $this->redirectAfterAction($object);
    }
}
