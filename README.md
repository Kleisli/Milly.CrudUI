# Milly.CrudForms
***
Easily build controllers and views to show and edit Neos.Flow domain models. 
***

> We needed to understand 
> how we might reuse tested code and have the machine help in programming. As we
> programmed, we examined the process and tried to think of ways to abstract these 
> steps to incorporate them into higher-level language. This led to the development
> of interpreters, assemblers, compilers, and generators—programs designed to operate 
> on or produce other programs, that is, automatic programming. ([Source](https://en.wikipedia.org/wiki/Automatic_programming))
> 
[Milly Koss](https://en.wikipedia.org/wiki/Milly_Koss) is an American pioneering computer programmer. The Association for Women in Computing awarded her an Ada Lovelace Award in 2000.

## Usage
### Model & Repository
There is no special requirements for Models or Repositories.
#### Sortable Models
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
### Controller
a minimal CrudController to be used as Neos backend module:
```
class MyModelController extends AbstractModuleController
{
    protected $defaultViewObjectName = \Neos\Fusion\View\FusionView::class;
    use CrudControllerTrait;
}
```
this includes all the crud features. If you only want to use some of them, you can add the ``BaseControllerTrait`` plus 

* ``CreateControllerTrait``
* ``DeleteControllerTrait``
* ``ReadAllControllerTrait``
* ``ReadOneControllerTrait``
* ``UpdateControllerTrait``
* ``SortingControllerTrait``

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

### Views
Views are implemented in Fusion and expected in the Fusion path ``Vendor.Package.MyModelController.action`` it is recommended,
to create a fusion file for each action in ``Resurces\Private\Fusion\Integration\Controller\MyModel\``. 
If you have no custom requirements, you can simply use the provided Template prototypes and pass the default arguments.

#### Edit.fusion
```
Vendor.Package.MyModelController.edit = Milly.Crudforms:Template.Edit {
    object={object} 
    configuration={configuration}
}
```

#### Index.fusion
```
Vendor.Package.MyModelController.index = Milly.Crudforms:Template.Index {
    objects={objects}
    configuration={configuration}
}
```

#### New.fusion
```
Vendor.Package.MyModelController.new = Milly.Crudforms:Template.New {
    configuration={configuration} 
    preset.category={Object.identifier(parent)}
}
```
with the preset parameter you can define properties for the entity to be created. When you are managing nested structures
with models organized in categories e.g. you can pass the category in which the entity should be created.

#### Show.fusion
```
Vendor.Package.MyModelController.show = Milly.Crudforms:Template.Show {
    object={object} 
    configuration={configuration}
}
```
### ConfigurationSettings
For each Model Configuration Settings have to be defined that contains
* labels of the entity
* definition of properties and relations
* label of each property
* form element type of each property
* view definitions (what properties should be shown in which views)
* filters for the index view

```
Milly:
  CrudForms:
    Vendor:
      Package:
        MyModel:
          parent: category
          label:
            one: A Thing
            many: Things
          properties:
            name:
              label: Name
            description:
              label: Description
          relations:
            category:
              label: Category
          views:
            index:
              showColumnHeaders: false
              properties: [ description ]
        Category:
          label:
            one: Category
            many: Categories
          properties:
            name:
              label: Name
            image:
              label: Bild
              type: image
          relations:
            things:
              label: Things
```

documentation in progress...

## Adding Property types
### FormElement
### PropertyValue

## Customize Templates
### FormElementAttributes
### FormElementContainer
### PropertyValueContainer

## Kudos
This package is based on the idea and inspired by [Sandstorm.CrudForms](https://github.com/sandstorm/CrudForms)

The development of this package has significantly been funded by [Profolio](https://www.profolio.ch/) - a digital platform for career choice & career counseling
