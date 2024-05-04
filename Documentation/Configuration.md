# Configuration
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

### Filters
```
views:
    index:
        filter:
          forProduct:
            type: select
```

## Properties-Configuration
### Map properties of connected entities

```
properties:
    firstName:
        propertyPath: 'name.firstName'
    
```

### Add custom Property types
#### PropertyEditor
#### PropertyDisplayer
