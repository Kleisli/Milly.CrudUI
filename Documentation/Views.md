# Views
## Theming
Milly.CrudUI provides two themes.
- `neos` - for Neos backend modules
- `tailwind` - an example theme with tailwind classes

### Add your own theme
Copy one of the provided theme configuration files to `Configuration/Settings.CrudUITheme.MyTheme.yaml`
change the key of the theme and the values within the theme.
```
Milly:
  CrudUI:
    themes:
      myTheme: []
```

### Apply a theme
Themes can be applied globally in the Settings Configuration
````
Milly:
  CrudUI:
    defaultTheme: 'myTheme'
````
they can be applied to all actions/views of a Controller
```
class CollectionController extends ActionController
{
    protected string $theme = 'myTheme';
    use CrudControllerTrait;
}
```
or they can be applied only to one view
```
MyVendor.MyPackage.MyModelController.edit = Milly.CrudUI:Template.Edit {
    millyCrudTheme = 'myTheme'
}
```

## Custom templates
Views are implemented in Fusion and expected in the Fusion path ``Vendor.Package.MyModelController.action`` 
create a fusion file for each action in ``Resources\Private\Views\``.

For smaller adjustments the provided Template prototypes can be used.

### Index.fusion
```
MyVendor.MyPackage.MyModelController.index = Milly.CrudUI:Template.Index {
    footer {
        myAction = 'add another action button to the footer'
    }
}
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
MyVendor.MyPackage.MyModelController.show = Milly.CrudUI:Template.Show {
    main {
        second {
            additionalContent = 'add some content to the second column'
        }
    }
}
```

### Edit.fusion
```
MyVendor.MyPackage.MyModelController.edit = Milly.CrudUI:Template.Edit 
```
