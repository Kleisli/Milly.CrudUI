<?php
namespace Milly\CrudForms\Controller;

trait UpdateControllerTrait
{
    protected function initializeEditAction()
    {
        $this->registerObjectArgument();
    }

    public function editAction()
    {
        $this->view->assign('object', $this->arguments['object']->getValue());
        $this->view->assign('configuration', $this->getCrudFormsConfiguration('edit'));
        $this->view->assign('crudFormsModelClass', $this->getModelClass());
    }

    protected function initializeUpdateAction()
    {
        $this->registerObjectArgument();
    }

    public function updateAction()
    {
        $object = $this->arguments['object']->getValue();
        $this->getRepository()->update($object);

        if (method_exists($this, 'afterUpdateAction')) {
            $this->afterUpdateAction($object);
        }

        $this->showObject($object);

    }
}
