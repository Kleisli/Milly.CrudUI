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
- `pageSize` : define the number of entities that are displayed on one pagination page, dfeault value `10`

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
#### Fulltext filter
```
filter:
  name:
    type: fulltext
    fields: ['name.firstName', 'name.lastName']
```
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

## Properties-Configuration
### Map properties of connected entities
```
properties:
  firstName:
    propertyPath: 'name.firstName'
```

### Add custom property editors and displayers

