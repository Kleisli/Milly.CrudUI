<?php
namespace Milly\CrudForms\Controller;

trait ReadAllControllerTrait
{
    /**
     * @param array $filter
     * @param int $paginationCurrentPage
     * @throws \Neos\Flow\Exception
     */
    public function indexAction(array $filter = [], int $paginationCurrentPage = 0) : void
    {
        $query = $this->getRepository()->createQuery();
        $configuration = $this->getCrudFormsConfiguration('index');
        $conditions = [];
        if(count($filter)>0) {
            foreach ($filter as $property => $value) {
                if ($value != '') {
                    $filterConfiguration = $configuration['views']['index']['filter'][$property] ?? null;
                    switch ($filterConfiguration['type'] ?? null) {
                        case 'fulltext':
                            $textConditions = [];
                            foreach ($filterConfiguration['fields'] as $field) {
                                $textConditions[] = $query->like($field, '%' . $value . '%');
                            }
                            $conditions[] = $query->logicalOr($textConditions);
                            break;
                        case 'select':
                        default:
                            if($value == '-'){
                                $conditions[] = $query->equals($property, null);
                            }else {
                                $conditions[] = $query->equals($property, $value);
                            }
                    }
                }
            }
        }

        $paginationPageSize = $configuration['views']['index']['options']['pagination']['pageSize'] ?? 10;

        if(count($conditions)>0){
            $query = $query->matching($query->logicalAnd($conditions));
        }

        $objectCount = $query->count();

        $query->setLimit($paginationPageSize)->setOffset($paginationCurrentPage*$paginationPageSize);
        $pagination = [
            'currentPage' => $paginationCurrentPage,
            'pageSize' => $paginationPageSize,
            'lastPage' => intval(floor($objectCount / $paginationPageSize))
        ];

        $this->view->assign('filterValues', $filter);
        $this->view->assign('pagination', $pagination);
        $this->view->assign('objectCount', $objectCount);
        $this->view->assign('objects', $query->execute()->toArray());
        $this->view->assign('crudFormsModelClass', $this->getModelClass());

        $this->view->assign('isCreateDisabled', !method_exists($this, 'newAction'));
        $this->view->assign('isUpdateDisabled', !method_exists($this, 'editAction'));
        $this->view->assign('isDeleteDisabled', !method_exists($this, 'deleteAction'));
    }

}
