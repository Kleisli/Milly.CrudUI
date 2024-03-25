# Milly.CrudForms
***
Easily build controllers and views to show and edit Neos.Flow domain models. 
***

> We needed to understand 
> how we might reuse tested code and have the machine help in programming. As we
> programmed, we examined the process and tried to think of ways to abstract these 
> steps to incorporate them into higher-level language. This led to the development
> of interpreters, assemblers, compilers, and generators—programs designed to operate 
> on or produce other programs, that is, automatic programming. (Milly Koss, [Source](https://en.wikipedia.org/wiki/Automatic_programming))
> 
[Milly Koss](https://en.wikipedia.org/wiki/Milly_Koss) was an American pioneering computer programmer. The Association for Women in Computing awarded her an Ada Lovelace Award in 2000.

## Domain Models and Repositories
There is no special requirements for Models or Repositories.

see [Documentation/Domain.md](Documentation/Domain.md) for 
- Sorting

## Controller
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

see [Documentation/Controller.md]() for
- Mismatching Model Namespace


## Views
### Fusion
Views are implemented in Fusion and expected in the Fusion path ``Vendor.Package.MyModelController.action`` it is recommended,
to create a fusion file for each action in ``Resurces\Private\Fusion\Integration\Controller\MyModel\``. 
If you have no custom requirements, you can use the provided Template prototypes.

#### Index.fusion
```
MyVendor.MyPackage.MyModelController.index = Milly.Crudforms:Template.Index
```

#### New.fusion
```
MyVendor.MyPackage.MyModelController.new = Milly.Crudforms:Template.New {
    # optional presets
    preset.property = "value"
}
```
with the preset parameter you can define properties for the entity to be created. 

#### Show.fusion
```
MyVendor.MyPackage.MyModelController.show = Milly.Crudforms:Template.Show
```

#### Edit.fusion
```
MyVendor.MyPackage.MyModelController.edit = Milly.Crudforms:Template.Edit
```

## Configuration
Configuration Settings have to be defined for each model
```
Milly:
  CrudForms:
    MyVendor:
      MyPackage:
        MyModel:
          label:
            one: 'My model'
            many: 'My models'
          parent: 'parentProperty'
          views: []
          properties: []
          relations: []
```
- `label` : Singular and plural form of how the entity class should be labelled for the user
- `parent` : If you want to manage nested structures, you can set a property, that is a reference the parent of the current model.
            The parent model must have a configured `relation` to the child model. 


### Views
The views configuration is optional. When no views configuration exists for a given view, all properties are displayed and the default settings are applied.
```
views:
    index:
        properties: [textProperty, image]
        showColumnHeaders: false
    show:
        properties: []
    edit:
        properties: []
    relation:
        properties: []
        showColumnHeaders: false
    export:
        properties: []
```
For each view, an array of properties to be displayed or edited can be configured.
The configuration of view `relation` specifies what properties of a model are displayed when shown in the `show`view of the `parent` model.

For the `index` and the `relation` view it can be configured whether the column headers that display the property labels should be shown or not. The default value is `false`. 

see [Documentation/Configuration.md](Documentation/Configuration.md#views) to
- configure filter options in the `index` view
- configure the pagination for the `index` view
- apply different styles with theming

### Properties
To make Milly.CrudUi aware of a property of your model, add it as a key to `properties`.
If you don't add any property configuration
- the default `type` `string` will be applied
- the label will be fetched from the xlf file `Model/MyModel.xlf` with the property name as trans-unit id. see [Documentation/MyModel.xlf](Documentation/MyModel.xlf) for an example.
    - if there is no such file or key, the property name will be used as label
```
properties:
    textProperty: []
    title:
        label: 'Title'
```
PropertyEditors provide form fields to edit properties and PropertyDisplayers are used to display properties of different types.
There are several combinations of PropertyEditor and PropertyDisplayer that can be configured by `type`. 
For some types there are `options` that can or have to be defined.
```
properties:
    image:
        type: image
    entity:
        type: select
        options:
            repository: MyVendor\MyPackage\Domain\Repository\MyEntityRepository
```

Property types
- `string` (default)
  - Editor: an input field with type text
  - Displayer: the plain property value
- `textarea`
  - Editor: a textarea
  - Displayer: the thext with newlines converted to html-breaks
- `number`
  - Editor: an input field with type number
  - Displayer: the plain property value 
- `email`
  - Editor: an input field with type text
  - Displayer: a mailto-link with the email address
- `select` for ManyToOne properties
  - Editor: a select field, populated with entries fetched from a `dataSource` or a `repository
  - Displayer: either the label of the object or a link to the `show` view of the object
  - Options
    - `link`, optional : `true` or `false`, default is `false`
    - `dataSource`, either this or `repository` has to be set
      - the FQN of a [Flow DataSource](https://docs.neos.io/guide/advanced/extending-neos-with-php-flow/custom-data-sources)
    - `repository`, either this or `dataSource` has to be set
      - either only the FQN of a class, then `findAll()` is called on the repository to get the select options
      - a method name can be appended to the repository class `MyVendor\MyPackage\Domain\Repository\MyEntityRepository->findOptionsForObject` - 
        the currently edited object will be passed as argument to this method and it has to return an `Iterable`
      - as label of the objects in the select field, the return value of one of the following methods will be used in this priority
        1. `getLabel()`
        2. `getTitle()`
        3. `getName()`
- `multiSelect` for OneToMany or ManyToMany properties
  - Editor: a list of the current values with the option to remove them from the relation and a select field to add objects to the relation
  - Displayer: a list of either the label of the objects or a link to the `show` view of the objects
  - Options: the same as for `select`
- `booleanRadio` for boolean properties
  - Editor: two radio buttons with the labels defined in `options`
  - Displayer: the label for the current value of the property
  - Options
    - `0` : Label if the value is false
    - `1` : Label if the value is true
- `booleanCheckbox` for boolean properties
  - Editor: a input field with type checkbox 
  - Displayer: either Yes or No
- `date` for DateTime properties
  - Editor: input field with type date
  - Displayer: the date formatted as "d.m.Y"
- `dateTime` for DateTime properties
  - Editor: input field with type datetime-local
  - Displayer: the date formatted as "d.m.Y H:i"
- `dateInterval` for DateInterval properties
  - Editor: three input fields with type number to enter years, months and days
  - Displayer: the dateInterval formatted as string
- `image` for `\Neos\Flow\ResourceManagement\PersistentResource` properties
  - Editor: The Displayer with a checkbox to delete the image and an upload field
  - Displayer: the image, linked to the image file
- `audio` for `\Neos\Flow\ResourceManagement\PersistentResource` properties
  - Editor: The Displayer with a checkbox to delete the audio file and an upload field
  - Displayer: a html audio element to play the audio file
- `jsonList` for array properties annotated with `@ORM\Column(type="flow_json_array")` 
  - Editor: a textarea with the JSON string
  - Displayer: a nested list with keys and values of the JSON string

see [Documentation/Configuration.md](Documentation/Configuration.md#properties) to
- Map properties (e.g. of connected entities)
- Add custom property editors and displayers


### Relations
Technically, a property can also be a relation (see types `select` or m`multiSelect`) and relations are also properties of the class.

The differences in this context are:
- when configured in `properties`, the user can only choose from existing objects, while in `relations` the user can create
new objects of the relation class
- the list of objects in `relations` can show more than one property of the objects (configured in `views.relation`), 
as `properties` the objects are displayed as list of their labels

```
relations: []
```






## Kudos
This package is based on the idea and inspired by [Sandstorm.CrudForms](https://github.com/sandstorm/CrudForms)

The development of this package has significantly been funded by [Profolio](https://www.profolio.ch/) - a digital platform for career choice & career counseling
