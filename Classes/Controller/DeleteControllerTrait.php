<?php
namespace Milly\CrudUI\Controller;

trait DeleteControllerTrait
{
    protected function initializeDeleteAction()
    {
        $this->registerObjectArgument();
    }

    public function deleteAction()
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
