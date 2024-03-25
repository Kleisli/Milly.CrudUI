# Configuration
## Views
```
views:
    index:
        filter:
          forProduct:
            type: select
        options:
          pagination:
            disable: false
            pageSize: 20
```

## Properties
### Map properties of connected entities

```
properties:
    firstName:
        propertyPath = 'name.firstName'
    
```

### Add custom Property types
#### PropertyEditor
#### PropertyDisplayer
