<?php declare(strict_types=1);
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


    /**
     * @param array<string, array<string>> $addElements propertyNames as keys and an array of identifiers as value
     * @param array<string, array<string>> $removeElements propertyNames as keys and an array of identifiers as value
     * @throws Exception
     * @throws \Doctrine\ORM\ORMException
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     * @throws \Neos\Utility\Exception\InvalidTypeException
     * @throws \Neos\Utility\Exception\PropertyNotAccessibleException
     */
    public function updateAction(array $addElements = [], array $removeElements = []): void
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
