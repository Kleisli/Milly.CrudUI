<?php
namespace Milly\CrudUI\Controller;
trait UpdateControllerTrait
{

    protected function initializeEditAction()
    {
        $this->registerObjectArgument();
    }

    public function editAction()
    {
        $this->view->assign('object', $this->arguments['object']->getValue());
    }

    protected function initializeUpdateAction()
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
