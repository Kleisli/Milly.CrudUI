<?php
namespace Milly\CrudForms\Controller;

trait SortingControllerTrait
{
    protected function initializeSortUpAction()
    {
        $this->registerObjectArgument();
    }

    /**
     * @return void
     */
    public function sortUpAction()
    {
        $object = $this->arguments['object']->getValue();
        $this->getRepository()->sortUp($object);
        $this->redirectAfterAction($object);
    }

    protected function initializeSortDownAction()
    {
        $this->registerObjectArgument();
    }

    /**
     * @return void
     */
    public function sortDownAction()
    {
        $object = $this->arguments['object']->getValue();
        $this->getRepository()->sortDown($object);
        $this->redirectAfterAction($object);
    }
}
