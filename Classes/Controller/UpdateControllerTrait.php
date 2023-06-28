<?php
namespace Milly\CrudForms\Controller;

use Neos\Utility\ObjectAccess;

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

    public function updateAction(array $addElements = [], array $removeElements = [])
    {
        $object = $this->arguments['object']->getValue();
        foreach ($addElements as $propertyName => $elementIdentifiers){
            if(is_array($elementIdentifiers)) {
                $type = $this->millyReflectionService->getTypeOfRelation($this->getModelClass(), $propertyName);
                foreach ($elementIdentifiers as $elementIdentifier) {
                    if ($elementIdentifier != '') {
                        $element = $this->persistenceManager->getObjectByIdentifier($elementIdentifier, $type);
                        $collection = ObjectAccess::getProperty($object, $propertyName);
                        $collection->add($element);
                        ObjectAccess::setProperty($object, $propertyName, $collection);
                    }
                }
            }
        }
        foreach ($removeElements as $propertyName => $elementIdentifiers){
            if(is_array($elementIdentifiers)) {
                $type = $this->millyReflectionService->getTypeOfRelation($this->getModelClass(), $propertyName);
                foreach ($elementIdentifiers as $elementIdentifier) {
                    if ($elementIdentifier != '') {
                        $element = $this->persistenceManager->getObjectByIdentifier($elementIdentifier, $type);
                        $collection = ObjectAccess::getProperty($object, $propertyName);
                        $collection->removeElement($element);
                        ObjectAccess::setProperty($object, $propertyName, $collection);
                    }
                }
            }
        }
        $this->getRepository()->update($object);

        if (method_exists($this, 'afterUpdateAction')) {
            $this->afterUpdateAction($object);
        }

        $this->showObject($object);

    }
}
