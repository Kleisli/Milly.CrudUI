# Views
## Fusion
Views are implemented in Fusion and expected in the Fusion path ``Vendor.Package.MyModelController.action`` it is recommended,
to create a fusion file for each action in ``Resources\Private\Views\``.
If you have no custom requirements, you can use the provided Template prototypes.

### Index.fusion
```
MyVendor.MyPackage.MyModelController.index = Milly.CrudUI:Template.Index
```

### New.fusion
```
MyVendor.MyPackage.MyModelController.new = Milly.CrudUI:Template.New {
    # optional presets
    preset.property = "value"
}
```
with the preset parameter you can define properties for the entity to be created.

### Show.fusion
```
MyVendor.MyPackage.MyModelController.show = Milly.CrudUI:Template.Show
```

### Edit.fusion
```
MyVendor.MyPackage.MyModelController.edit = Milly.CrudUI:Template.Edit
```
