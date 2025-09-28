<?php
namespace Milly\CrudUI\Controller;

use Neos\Flow\Exception;
use Neos\Flow\Persistence\Exception\InvalidQueryException;
use Neos\Flow\Persistence\QueryInterface;

trait ReadAllControllerTrait
{
    /**
     * @throws Exception
     * @throws InvalidQueryException
     */
    public function indexAction(array $filter = [], ?int $paginationPageSize = null, int $paginationCurrentPage = 0, string $orderBy = '', string $orderDirection = QueryInterface::ORDER_ASCENDING) : void
    {
        $query = $this->getRepository()->createQuery();
        if($orderBy != ''){
            $query->setOrderings([$orderBy => $orderDirection]);
        }
        $configuration = $this->getCrudUIConfiguration('index');
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
                        case 'isSet':
                            switch ($value) {
                                case '0':
                                    $conditions[] = $query->equals($property, null);
                                    break;
                                case '1':
                                    $conditions[] = $query->logicalNot($query->equals($property, null));
                            }
                            break;
                        case 'select':
                        default:
                            if($value == '-'){
                                if($this->millyReflectionService->isToManyRelation(self::ENTITY_CLASSNAME, $property)){
                                    $conditions[] = $query->isEmpty($property);
                                } else {
                                    $conditions[] = $query->equals($property, null);
                                }
                            } else {
                                if ($this->millyReflectionService->isToManyRelation(self::ENTITY_CLASSNAME, $property)) {
                                    $conditions[] = $query->contains($property, $value);
                                } else {
                                    $conditions[] = $query->equals($property, $value);
                                }
                            }
                    }
                }
            }
        }

        if($paginationPageSize === null) {
            $paginationPageSize = $configuration['views']['index']['options']['pagination']['pageSize'] ?? 10;
        }

        if(count($conditions)>0){
            $query = $query->matching($query->logicalAnd($conditions));
        }

        $objectCount = $query->count();

        if($paginationPageSize > 0) {
            $query->setLimit($paginationPageSize)->setOffset($paginationCurrentPage * $paginationPageSize);
        }

        $pagination = [
            'currentPage' => $paginationCurrentPage,
            'pageSize' => $paginationPageSize,
            'lastPage' => ($paginationPageSize == 0) ? $paginationCurrentPage : intval(floor($objectCount / $paginationPageSize))
        ];

        if($this->request->getFormat() != 'html'){
            header('Content-Disposition: filename="'.$configuration['label']['many'].'_'.date("Y-m-d_H-i").'.'.$this->request->getFormat().'"');
        }

        $this->view->assign('filterValues', $filter);
        $this->view->assign('pagination', $pagination);
        $this->view->assign('paginationPageSize', $paginationPageSize);
        $this->view->assign('objectCount', $objectCount);
        $this->view->assign('orderBy', $orderBy);
        $this->view->assign('orderDirection', $orderDirection);
        $this->view->assign('objects', $query->execute()->toArray());
        $this->view->assign('CrudUIModelClass', $this->getModelClass());

        $this->view->assign('isCreateDisabled', !method_exists($this, 'newAction'));
        $this->view->assign('isUpdateDisabled', !method_exists($this, 'editAction'));
        $this->view->assign('isDeleteDisabled', !method_exists($this, 'deleteAction'));
    }

}
