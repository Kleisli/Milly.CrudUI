# Controller
## Mismatching Model Namespace
If the Controller namespace path does not match the model namespace path, you have to define the ENTITY_CLASSNAME constant

```
const ENTITY_CLASSNAME = MyModel::class;
```

Matching:
* ``Vendor\Package\Domain\Model\MyModel`` and ``Vendor\Package\Controller\MyModelController``
* ``Vendor\Package\Domain\Model\Boo\MyModel`` and ``Vendor\Package\Controller\Boo\MyModelController``

Not matching:
* ``Vendor\Package\Domain\Model\MyModel`` and ``Vendor\Package\Controller\BackendModule\MyModelController``
* ``Vendor\Package\Domain\Model\MyModel`` and ``Vendor\Package\Controller\BackendController``
* ``Vendor\Package\Domain\Model\MyModel`` and ``Vendor\Package\Controller\MyController``


## Select the functionalities to add
The ``CrudControllerTrait`` includes all the crud features. 
If you only want to use some of them, you can add the ``BaseControllerTrait`` plus a selection of

* ``CreateControllerTrait``
* ``DeleteControllerTrait``
* ``ReadAllControllerTrait``
* ``ReadOneControllerTrait``
* ``UpdateControllerTrait``
* ``SortingControllerTrait``

