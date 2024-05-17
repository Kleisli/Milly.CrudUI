# Customize Configuration
## Views-Configuration
### Show Column Headers
```
views:
  index:
    showColumnHeaders: true
  relation:
    showColumnHeaders: true      
```
For the `index` and the `relation` view the column headers that display the property
labels can be shown or not. The default value is `false`.

### Pagination
```
views:
  index:
    options:
      pagination:
        disable: false
        pageSize: 20
```
The `index` view has a configurable pagination component.

Options
- `disable` : to disable the pagination and always display all entities, default value `false`
- `pageSize` : define the number of entities that are displayed on one pagination page, default value `10`

### Filters
```
views:
  index:
    filter: []
```
Filters can be defined for each property in the `index` view, a filter will be rendered in the header of the property
column of the index table.

#### Select filter
```
filter:
  <entityProperty>:
    type: select
```
Select filters can be used for properties of type `select` or `multiSelect` and provide a select field to filter 
the list of entities by all available options. 

#### Fulltext filter
```
filter:
  <textProperty>:
    type: fulltext
    fields: ['<textProperty>']
```
Fulltext filters provide a text input field to filter by a string that is contained in any of the indicated fields.

```
filter:
  label:
    type: fulltext
    fields: ['name.firstName', 'name.lastName']
```
The fulltext filter can also be applied to the `label`, which is always the first column of an index table, and filter
by a number of properties that can even be properties of referenced entities. 

#### IsSet filter
```
filter:
  <booleanProperty>:
    type: isSet
    typeOptions:
      labels:
        set: 'yep'
        notSet: 'nope'
```
The isSet filter can be used for boolean properties.

### order by
```
views:
  index:
    orderBy: [property1, property2]
```
For the user to be able to order all entities by properties, they have to be added to the `orderBy` array.

## Properties-Configuration
### Map properties of connected entities
```
properties:
  firstName:
    propertyPath: 'name.firstName'
```
With Milly.CrudUI properties of referenced entities can also be displayed and edited as if they were properties of 
the current entity. e.g. the firstName or lastName in a name property of a user. 

### Add custom property editors and displayers
```
properties:
  fancyProperty:
    ui:
      editor: 'MyVendor.MyPackage:Component.PropertyEditor.FancyEditor'
      displayer: 'MyVendor.MyPackage:Component.PropertyDisplayer.FancyDisplayer'
```
Custom editors to edit/set a property or displayers to display a property can be added as fusion prototype names  
to the property configuration.

```
properties:
  textPropertyWithFancyEditor:
    type: select
    ui:
      editor: 'MyVendor.MyPackage:Component.PropertyEditor.FancyEditor'
```
If either the editor or the displayer of a default type can be used, it is also possible to override only one of them.
