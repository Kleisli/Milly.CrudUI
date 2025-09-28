<?php
namespace Milly\CrudUI\Controller;
use Neos\Flow\Exception;
use Neos\Flow\Mvc\Exception\NoSuchArgumentException;
use Neos\Flow\Security\Exception\InvalidArgumentForHashGenerationException;
use Neos\Flow\Security\Exception\InvalidHashException;
use Neos\Flow\Validation\Exception\InvalidValidationConfigurationException;
use Neos\Flow\Validation\Exception\InvalidValidationOptionsException;
use Neos\Flow\Validation\Exception\NoSuchValidatorException;

trait UpdateControllerTrait
{

    /**
     * @throws InvalidValidationConfigurationException
     * @throws InvalidValidationOptionsException
     * @throws Exception
     * @throws InvalidArgumentForHashGenerationException
     * @throws NoSuchArgumentException
     * @throws InvalidHashException
     * @throws NoSuchValidatorException
     */
    protected function initializeEditAction(): void
    {
        $this->registerObjectArgument();
    }

    public function editAction(): void
    {
        $this->view->assign('object', $this->arguments['object']->getValue());
    }

    /**
     * @throws InvalidValidationConfigurationException
     * @throws InvalidValidationOptionsException
     * @throws Exception
     * @throws NoSuchArgumentException
     * @throws InvalidArgumentForHashGenerationException
     * @throws InvalidHashException
     * @throws NoSuchValidatorException
     */
    protected function initializeUpdateAction(): void
    {
        $this->registerObjectArgument();
    }

    public function updateAction(array $addElements = [], array $removeElements = [])
    {
        $object = $this->arguments['object']->getValue();
        $this->objectService->updateCollectionElements($object, $addElements, $removeElements);
        $this->getRepository()->update($object);

        if (method_exists($this, 'afterUpdateAction')) {
            $this->afterUpdateAction($object);
        }

        $this->showObject($object);

    }
}
