# Controller
## Mismatching Model Namespace
If the Controller namespace path matches the model namespace path, MIlly.CrudUI knows how to 
fetch the correct Controller to handle a model, but if they don't match, the ENTITY_CLASSNAME 
constant has to be defined.

```
const ENTITY_CLASSNAME = MyModel::class;
```

This commonly happens, when the Controllers to administrate entities are grouped in a namespace like
``...\Backend\MyModelController`` or ``...\Admin\MyModelController``

Matching:
* ``Vendor\Package\Domain\Model\MyModel`` and ``Vendor\Package\Controller\MyModelController``
* ``Vendor\Package\Domain\Model\Boo\MyModel`` and ``Vendor\Package\Controller\Boo\MyModelController``

Not matching:
* ``Vendor\Package\Domain\Model\MyModel`` and ``Vendor\Package\Controller\BackendModule\MyModelController``
* ``Vendor\Package\Domain\Model\MyModel`` and ``Vendor\Package\Controller\BackendController``
* ``Vendor\Package\Domain\Model\MyModel`` and ``Vendor\Package\Controller\MyController``


## Select the functionalities to add
The ``CrudControllerTrait`` includes all the crud features. 
To only use a subset of functionalities, the ``BaseControllerTrait`` plus a selection of the following
traits can be added.

* ``CreateControllerTrait``
* ``DeleteControllerTrait``
* ``ReadAllControllerTrait``
* ``ReadOneControllerTrait``
* ``UpdateControllerTrait``
* ``SortingControllerTrait``

