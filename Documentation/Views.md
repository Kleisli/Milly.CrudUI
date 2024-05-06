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
Themes can be applied globally for all packages of an installation in the Settings Configuration
````
Milly:
  CrudUI:
    defaultTheme: 'myTheme'
````
they can be applied to actions/views of just one Controller
```
class CollectionController extends ActionController
{
    protected string $theme = 'myTheme';
    use CrudControllerTrait;
}
```
or they can be applied to single views
```
MyVendor.MyPackage.MyModelController.edit = Milly.CrudUI:Template.Edit {
    millyCrudTheme = 'myTheme'
}
```

## Custom templates
Fusion files for custom templates are expected in the directory ``Resources\Private\Views\``.
### Default package views
Milly.CrudUI provides default templates for each action, but these default templates can be 
overwritten per package. A typical use case would be if there is a backend module to administrate
different Models and you want to add a navigation to switch between the Models.

Add a fusion file for each action, then use and extend the Template components provided by 
Milly.CrudUI.

e.g. *Edit.fusion*
```
Milly.CrudUI.Default.edit = Milly.CrudUI:Template.Edit {
    header.navigation = MyVendor.MyPackage:NavigationComponent
    header.navigation.@position = 'start'
} 
```


### Individual controller views
Views are expected in the Fusion path ``<Vendor>.<Package>.<MyModelController>.<action>``

For smaller adjustments the provided Template prototypes can be used.

*Index.fusion*
```
MyVendor.MyPackage.MyModelController.index = Milly.CrudUI:Template.Index {
    footer {
        myAction = 'add another action button to the footer'
    }
}
```

*New.fusion*
```
MyVendor.MyPackage.MyModelController.new = Milly.CrudUI:Template.New {
    # optional presets
    preset.property = "value"
}
```
with the preset parameter you can define properties for the entity to be created.

*Show.fusion*
```
MyVendor.MyPackage.MyModelController.show = Milly.CrudUI:Template.Show {
    main {
        second {
            additionalContent = 'add some content to the second column'
        }
    }
}
```
