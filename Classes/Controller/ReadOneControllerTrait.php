<?php
namespace Milly\CrudUI\Controller;

use Neos\Flow\Exception;
use Neos\Flow\Mvc\Exception\NoSuchArgumentException;
use Neos\Flow\Security\Exception\InvalidArgumentForHashGenerationException;
use Neos\Flow\Security\Exception\InvalidHashException;
use Neos\Flow\Validation\Exception\InvalidValidationConfigurationException;
use Neos\Flow\Validation\Exception\InvalidValidationOptionsException;
use Neos\Flow\Validation\Exception\NoSuchValidatorException;

trait ReadOneControllerTrait
{

    /**
     * @throws InvalidValidationConfigurationException
     * @throws InvalidValidationOptionsException
     * @throws Exception
     * @throws NoSuchArgumentException
     * @throws InvalidArgumentForHashGenerationException
     * @throws InvalidHashException
     * @throws NoSuchValidatorException
     */
    protected function initializeShowAction(): void
    {
        $this->registerObjectArgument();
    }

    public function showAction(): void
    {
        $this->view->assign('object', $this->arguments['object']->getValue());

        $this->view->assign('hasCreateActions', method_exists($this, 'newAction') && method_exists($this, 'createAction'));
        $this->view->assign('hasUpdateActions', method_exists($this, 'editAction') && method_exists($this, 'updateAction'));
        $this->view->assign('hasDeleteAction', !method_exists($this, 'deleteAction'));
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
    protected function initializeShowInlineAction(): void
    {
        $this->registerObjectArgument();
    }

    public function showInlineAction(?string $showInlineLayout = null): void
    {
        $this->view->assign('showInlineLayout', $showInlineLayout);
        $this->view->assign('object', $this->arguments['object']->getValue());

        $this->view->assign('hasCreateActions', method_exists($this, 'newAction') && method_exists($this, 'createAction'));
        $this->view->assign('hasUpdateActions', method_exists($this, 'editAction') && method_exists($this, 'updateAction'));
        $this->view->assign('hasDeleteAction', !method_exists($this, 'deleteAction'));
    }
}
