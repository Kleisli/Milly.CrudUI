<?php
namespace Milly\CrudForms\Controller;

trait ReadAllControllerTrait
{

    /**
     * @param array $filter
     * @return void
     * @throws \Neos\Flow\Exception
     */
    public function indexAction(array $filter = [])
    {
        $query = $this->getRepository()->createQuery();
        $configuration = $this->getCrudFormsConfiguration('index');
        $conditions = [];
        if(count($filter)>0) {
            foreach ($filter as $property => $value) {
                if ($value != '') {
                    $filterConfiguration = $configuration['views']['index']['filter'][$property];
                    switch ($filterConfiguration['type']) {
                        case 'select':
                            if($value == '-'){
                                $conditions[] = $query->equals($property, null);
                            }else {
                                $conditions[] = $query->equals($property, $value);
                            }
                            break;
                        case 'fulltext':
                            $textConditions = [];
                            foreach ($filterConfiguration['fields'] as $field) {
                                $textConditions[] = $query->like($field, '%' . $value . '%');
                            }
                            $conditions[] = $query->logicalOr($textConditions);
                            break;
                    }
                }
            }
        }
        if(count($conditions)>0){
            $objects = $query->matching($query->logicalAnd($conditions))->execute();
        }else{
            $objects = $this->getRepository()->findAll();
        }

        $this->view->assign('filterValues', $filter);
        $this->view->assign('objects', $objects->toArray());
        $this->view->assign('crudFormsModelClass', $this->getModelClass());
    }

}
