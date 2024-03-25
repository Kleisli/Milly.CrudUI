# Domain Models and Repositories
## Sorting
*Note: this will probably be replaced with ``Gedmo\Sortable``*

If you want a Model to be manually sortable, there are two traits that should be used.

*SortableModelTrait*
```
use \Milly\Sortable\Domain\Model\SortableModelTrait;

/**
  * @Flow\Inject
  * @var TheCorrespondingRepository
*/
protected $repository;
```

in case the Model should only be sorted within certain subsets, you can define the function ``getSortingCondition()``
```
/**
* @param QueryInterface $query
* @return object
*/
public function getSortingCondition(QueryInterface $query): ?object
{
return  $this->category ? $query->equals('category', $this->category) : null;
}
```

*SortingRepositoryTrait*
```
use Milly\Sortable\Domain\Repository\SortingRepositoryTrait;
protected $defaultOrderings = ['sorting' => QueryInterface::ORDER_ASCENDING];
```
