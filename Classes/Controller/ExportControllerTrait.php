<?php
namespace Milly\CrudForms\Controller;

use Neos\Flow\Persistence\QueryInterface;

trait ExportControllerTrait
{
    public function exportAction(array $filter = [], string $orderBy = '', string $orderDirection = QueryInterface::ORDER_ASCENDING) : void
    {
        $query = $this->getRepository()->createQuery();
        if($orderBy != ''){
            $query->setOrderings([$orderBy => $orderDirection]);
        }
        $configuration = $this->getCrudFormsConfiguration('export');
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
                                if($this->millyReflectionService->isToOneRelation(self::ENTITY_CLASSNAME, $property)){
                                    $conditions[] = $query->equals($property, $value);
                                }
                                if($this->millyReflectionService->isToManyRelation(self::ENTITY_CLASSNAME, $property)){
                                    $conditions[] = $query->contains($property, $value);
                                }
                            }
                    }
                }
            }
        }

        if(count($conditions)>0){
            $query = $query->matching($query->logicalAnd($conditions));
        }

        $this->view->assign('objects', $query->execute()->toArray());
        $this->view->assign('crudFormsModelClass', $this->getModelClass());
    }

}
