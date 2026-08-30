<?php declare(strict_types=1);
namespace Milly\CrudUI\Controller;

trait CrudControllerTrait
{
    use BaseControllerTrait;
    use SortingControllerTrait;

    use ReadAllControllerTrait;
    use ReadOneControllerTrait;
    use CreateControllerTrait;
    use UpdateControllerTrait;
    use DeleteControllerTrait;
}
