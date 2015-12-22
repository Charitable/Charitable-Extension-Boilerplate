# Charitable Extension Boilerplate
This is an extension boilerplate to speed up Charitable development.

## Getting Started

There are two ways you can use this boilerplate. If you are comfortable with the command line, the absolute quickest way to get started is with the Grunt way.

### The Grunt way

1. Clone or download this Github repository.
2. [Install Grunt](https://github.com/gruntjs/grunt-cli) if you don't have it already.
3. From the command line, run the `grunt build` command with the following arguments: `name`, `textdomain` and `class`. For example: 
    
    ```sh
    grunt build --name="Subscribe" --textdomain="charitable-subscribe" --class="Charitable_Subscribe"
    ```
    
4. The build will create a new folder inside build/textdomain. Copy and paste this into your site's `plugins` directory and it's ready to activate.

### The old-fashioned way

1. Clone or download this Github repository.
2. Copy and paste the contents of the `src` directory into a new plugin directory. 
3. Do a search and replace to replace the following phrases: "Extension Boilerplate", "extension boilerplate", "Extension_Boilerplate", "extension-boilerplate", "extension_boilerplate". 
4. You also need to rename any files that have `extension-boilerplate` in them. If you are on a Mac, we recommend using [Namechanger](http://mrrsoftware.com/namechanger/) to make this easy.

## Highlights

### Template class 

The Boilerplate includes a Template class. See [`includes/class-charitable-extension-boilerplate-template.php`](https://github.com/Charitable/Charitable-Extension-Boilerplate/blob/master/includes/class-charitable-extension-boilerplate-template.php). You can call this class using the class itself or through the `charitable_extension_boilerplate_template()` function, detailed below. 

All templates are to be included under the `templates` directory, and you can pass arguments to the template. The easiest way to do this is with the `charitable_extension_boilerplate_template()` function. For example, if you want to add a template called `my-template.php` and pass a `$campaign` object as an argument, you would call the template with the following line of code:

    charitable_extension_boilerplate_template( 'my-template.php', array( 'campaign' => $campaign ) );

The corresponding template file would be created at `templates/my-template.php`. You can get any passed arguments using the `$view_args` variable. In the example above, your template might look like this: 

    $campaign = $view_args[ 'campaign' ];
    ?>
    <h3><?php echo $campaign->title ?></h3>
  
Another feature of the Template class is that it makes it easy for users to override templates in their theme. The default template path to be used within themes/child themes is `charitable/charitable-extension-boilerplate`. By using a consistent Template structure, users can organize their theme files in a logical way. 

### Upgrade class

The Boilerplate also includes a simple Upgrade class. To use this class, simply populate the `$upgrade_actions` property with the versions and corresponding callback methods to be used. For example:

    protected $upgrade_actions = array(
            '1.0.1' => 'flush_permalinks', 
            '1.1.0' => 'upgrade_1_1_0', 
            '1.1.3' => 'flush_permalinks'
    );

Each upgrade action is represented as a `$key => $value` pair, where the `$key` is the version for which the upgrade should take place, and the `$value` is the name of a method inside the Upgrade class. In the example above, the `flush_permalinks()` method was called when upgrading to 1.0.1 and 1.1.3, while the `upgrade_1_1_0()' method was called on the 1.1.0 upgrade.
