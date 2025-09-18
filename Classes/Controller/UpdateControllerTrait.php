<?php
namespace Milly\CrudUI\Controller;
trait UpdateControllerTrait
{

    protected function initializeEditAction()
    {
        $this->registerObjectArgument();
    }

    public function editAction(bool $editInline = false, ?string $editInlineLayout = null)
    {
        $this->view->assign('editInline', $editInline);
        $this->view->assign('editInlineLayout', $editInlineLayout);
        $this->view->assign('object', $this->arguments['object']->getValue());
    }

    protected function initializeUpdateAction()
    {
        $this->registerObjectArgument();
    }

    public function updateAction(array $addElements = [], array $removeElements = [], bool $showInline = false, ?string $showInlineLayout = null)
    {
        $object = $this->arguments['object']->getValue();
        $this->objectService->updateCollectionElements($object, $addElements, $removeElements);
        $this->getRepository()->update($object);

        if (method_exists($this, 'afterUpdateAction')) {
            $this->afterUpdateAction($object);
        }

        $this->showObject($object, $showInline, $showInlineLayout);
    }
}
