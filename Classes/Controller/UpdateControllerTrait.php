<?php
namespace Milly\CrudUI\Controller;

trait UpdateControllerTrait
{

    protected function initializeEditAction(): void
    {
        $this->registerObjectArgument();
    }

    public function editAction(): void
    {
        $this->view->assign('object', $this->arguments['object']->getValue());
    }

    protected function initializeEditInlineAction(): void
    {
        $this->registerObjectArgument();
    }

    public function editInlineAction(?string $editInlineLayout = null): void
    {
        $this->view->assign('editInlineLayout', $editInlineLayout);
        $this->view->assign('object', $this->arguments['object']->getValue());
    }

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

    protected function initializeUpdateInlineAction(): void
    {
        $this->registerObjectArgument();
    }

    public function updateInlineAction(?string $showInlineLayout = null)
    {
        $object = $this->arguments['object']->getValue();
        $this->getRepository()->update($object);

        if (method_exists($this, 'afterUpdateAction')) {
            $this->afterUpdateAction($object);
        }

        $this->showObjectInline($object, $showInlineLayout);
    }
}
