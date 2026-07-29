<?php
namespace Milly\CrudUI\Controller;

use Neos\Flow\Exception;
use Neos\Flow\Mvc\Exception\NoSuchArgumentException;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Flow\Security\Exception\InvalidArgumentForHashGenerationException;
use Neos\Flow\Security\Exception\InvalidHashException;
use Neos\Flow\Validation\Exception\InvalidValidationConfigurationException;
use Neos\Flow\Validation\Exception\InvalidValidationOptionsException;
use Neos\Flow\Validation\Exception\NoSuchValidatorException;

trait SortingControllerTrait
{
    /**
     * @throws InvalidValidationConfigurationException
     * @throws Exception
     * @throws InvalidValidationOptionsException
     * @throws NoSuchArgumentException
     * @throws InvalidArgumentForHashGenerationException
     * @throws InvalidHashException
     * @throws NoSuchValidatorException
     */
    protected function initializeSortUpAction(): void
    {
        $this->registerObjectArgument();
    }


    /**
     * @throws StopActionException
     * @throws Exception
     */
    public function sortUpAction(): void
    {
        $object = $this->arguments['object']->getValue();
        $this->getRepository()->sortUp($object);
        $this->redirectAfterAction($object);
    }

    /**
     * @throws InvalidValidationConfigurationException
     * @throws Exception
     * @throws InvalidValidationOptionsException
     * @throws NoSuchArgumentException
     * @throws InvalidArgumentForHashGenerationException
     * @throws InvalidHashException
     * @throws NoSuchValidatorException
     */
    protected function initializeSortDownAction(): void
    {
        $this->registerObjectArgument();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function sortDownAction(): void
    {
        $object = $this->arguments['object']->getValue();
        $this->getRepository()->sortDown($object);
        $this->redirectAfterAction($object);
    }
}
