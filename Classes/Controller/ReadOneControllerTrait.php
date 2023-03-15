<?php
namespace Milly\CrudForms\Controller;

trait ReadOneControllerTrait
{

    protected function initializeShowAction()
    {
        $this->registerObjectArgument();
    }

    public function showAction()
    {
        $this->view->assign('object', $this->arguments['object']->getValue());
        $this->view->assign('configuration', $this->getCrudFormsConfiguration('show'));
        $this->view->assign('crudFormsModelClass', $this->getModelClass());
    }
}
