# Property types

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
