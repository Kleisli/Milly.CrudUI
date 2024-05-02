<?php
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
