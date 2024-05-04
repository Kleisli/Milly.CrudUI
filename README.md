# Milly.CrudUI

Easily build controllers and views to show and edit Neos.Flow domain models. 

## Models-Views-Controllers
### Domain Models and Repositories
With Milly.CrudUI you can manage objects of any model, even existing models of third party packages.

#### Customize
- [Sorting](Documentation/Domain.md#sorting)

### Views
Milly.CrudUI loads default fusion templates out of the box, but you can create your own, if 
you want to add custom elements.

#### Customize
- [Add custom fusion templates](Documentation/Views.md)
- apply custom styles with theming


### Controller
Milly.CrudUI can be used for Neos backend modules or with regular ActionControllers to provide 
crud functionality to frontend users.

Neos backend module
```
class MyModelController extends AbstractModuleController
{
    use CrudControllerTrait;
}
```

For Neos frontend or in plain Flow applications
```
class MyModelController extends ActionController
{
    use CrudControllerTrait;
}
```

#### Customize
- [What to do with a mismatching Model Namespace?](Documentation/Controller.md#mismatching-model-namespace)
- [Select the crud functionalities to add](Documentation/Controller.md#select-the-features-to-add)


## Configuration
Configuration Settings have to be defined for each model
```
Milly:
  CrudUI:
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
### Labels

- `label` : Singular and plural form of how the entity class should be labelled for the user

### Parent
- `parent` : If you want to manage nested structures, you can set a property, that is a reference the parent of the current model.
            The parent model must have a configured `relation` to the child model. 


### Views-Configuration

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
For each view, an array of properties that should be rendered can be configured.
The view `relation` specifies what properties of a model are displayed when it is shown as relation in the 
`show` view of the `parent` model.

For the `index` and the `relation` view the column headers that display the property 
labels can be shown or not. The default value is `false`. 

The views configuration are optional. When no views configuration exists for a view, all properties are displayed 
and the default settings are applied.

#### Customize
- configure filter options in the `index` view
- configure the pagination for the `index` view

see [Documentation/Configuration.md](Documentation/Configuration.md#views)

### Properties-Configuration
To make Milly.CrudUi aware of a property of your model, it has to be added as a key to the `properties` configuration.

```
properties:
    textProperty: []
    title:
        label: 'Title'
```

If there is no property configuration
- the default `type` `string` will be applied
- the label will be fetched from the xlf file `Model/MyModel.xlf` with the property name as trans-unit id. see [Documentation/MyModel.xlf](Documentation/MyModel.xlf) for an example.
    - if there is no such file or key, the property name will be used as label

```
properties:
    image:
        type: image
    entity:
        type: select
        options:
            repository: MyVendor\MyPackage\Domain\Repository\MyEntityRepository
```

PropertyEditors provide form fields to edit properties and PropertyDisplayers are used to display properties values.
There are several built-in combinations of PropertyEditor and PropertyDisplayer defined by a `type`. 
For some types there are `options` that can or have to be defined.

The following property types exist. For more details, see [Property Types](Documentation/PropertyTypes.md)
- `string` (default)
- `textarea`
- `number`
- `email`
- `select` for ManyToOne properties
- `multiSelect` for OneToMany or ManyToMany properties
- `booleanRadio` for boolean properties
- `booleanCheckbox` for boolean properties
- `date` for DateTime properties
- `dateTime` for DateTime properties
- `dateInterval` for DateInterval properties
- `image` for `\Neos\Flow\ResourceManagement\PersistentResource` properties
- `audio` for `\Neos\Flow\ResourceManagement\PersistentResource` properties
- `jsonList` for array properties annotated with `@ORM\Column(type="flow_json_array")`


#### Customize
- Map properties (e.g. of connected entities)
- Add custom property editors and displayers


  see [Documentation/Configuration.md](Documentation/Configuration.md#properties)

### Relations-Configuration
Technically, a property can also be a relation (see types `select` or `multiSelect`) and relations 
are also properties of the class.

The differences in the context of Milly.CrudUI are:
- when configured in `properties`, the user can only choose from existing objects, while in `relations` the user can create
new objects of the relation class
- the list of objects in `relations` can show more than one property of the objects (configured in `views.relation`), 
as `properties` the objects are displayed as list of their labels

```
relations: []
```

## Why Milly?
[Milly Koss](https://en.wikipedia.org/wiki/Milly_Koss) was an American pioneering computer programmer. The Association for Women in Computing awarded her an Ada Lovelace Award in 2000.
> We needed to understand
> how we might reuse tested code and have the machine help in programming. As we
> programmed, we examined the process and tried to think of ways to abstract these
> steps to incorporate them into higher-level language. This led to the development
> of interpreters, assemblers, compilers, and generators—programs designed to operate
> on or produce other programs, that is, automatic programming. (Milly Koss, [Source](https://en.wikipedia.org/wiki/Automatic_programming))
>



## Kudos
This package is based on the idea and inspired by [Sandstorm.CrudForms](https://github.com/sandstorm/CrudForms)

The development of this package has significantly been funded by [Profolio](https://www.profolio.ch/) - a digital platform for career choice & career counseling
