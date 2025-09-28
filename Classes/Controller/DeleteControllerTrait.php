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

trait DeleteControllerTrait
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
    protected function initializeDeleteAction(): void
    {
        $this->registerObjectArgument();
    }

    /**
     * @throws StopActionException
     * @throws Exception
     */
    public function deleteAction(): void
    {
        $object = $this->arguments['object']->getValue();
        $this->getRepository()->remove($object);

        if (method_exists($this, 'afterDeleteAction')) {
            $this->afterDeleteAction($object);
        }

        if (method_exists($this, 'redirectAfterAction')) {
            $this->redirectAfterAction($object);
        }else{
            $this->redirect('index');
        }
    }

}
