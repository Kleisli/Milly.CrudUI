<?php
namespace Milly\CrudUI\Controller;

trait ReadOneControllerTrait
{
    protected function initializeShowAction()
    {
        $this->registerObjectArgument();
    }

    public function showAction(bool $showInline = false, ?string $showInlineLayout = null)
    {
        $this->view->assign('showInline', $showInline);
        $this->view->assign('showInlineLayout', $showInlineLayout);
        $this->view->assign('object', $this->arguments['object']->getValue());

        $this->view->assign('hasCreateActions', method_exists($this, 'newAction') && method_exists($this, 'createAction'));
        $this->view->assign('hasUpdateActions', method_exists($this, 'editAction') && method_exists($this, 'updateAction'));
        $this->view->assign('hasDeleteAction', !method_exists($this, 'deleteAction'));
    }
}
