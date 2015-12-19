# Charitable Subscribe
This is an subscribe to speed up Charitable development.

## Getting Started

1. Clone or download this Github repository.
2. If you want to use Grunt to build the project, create this extension inside a folder inside of your `wp-content` folder. For example, we have a folder under `wp-content` called `src`. When we are working on an extension inside this folder, we simply run `grunt` in the command line to have our changes automatically synced to the `wp-content/plugins` directory.
3. Do a search and replace to replace the following phrases: "Subscribe", "subscribe", "Subscribe", "extension-boilerplate", "extension_boilerplate". 
4. You also need to rename any files that have `extension-boilerplate` in them. If you are on a Mac, we recommend using [Namechanger](http://mrrsoftware.com/namechanger/) to make this easy.

## Highlights

### Template class 

The Boilerplate includes a Template class. See [`includes/class-charitable-subscribe-template.php`](https://github.com/Charitable/Charitable-Extension-Boilerplate/blob/master/includes/class-charitable-subscribe-template.php). You can call this class using the class itself or through the `subscribe_template()` function, detailed below. 

All templates are to be included under the `templates` directory, and you can pass arguments to the template. The easiest way to do this is with the `subscribe_template()` function. For example, if you want to add a template called `my-template.php` and pass a `$campaign` object as an argument, you would call the template with the following line of code:

    subscribe_template( 'my-template.php', array( 'campaign' => $campaign ) );

The corresponding template file would be created at `templates/my-template.php`. You can get any passed arguments using the `$view_args` variable. In the example above, your template might look like this: 

    $campaign = $view_args[ 'campaign' ];
    ?>
    <h3><?php echo $campaign->title ?></h3>
  
Another feature of the Template class is that it makes it easy for users to override templates in their theme. The default template path to be used within themes/child themes is `charitable/charitable-subscribe`. By using a consistent Template structure, users can organize their theme files in a logical way. 

### Upgrade class

The Boilerplate also includes a simple Upgrade class. To use this class, simply populate the `$upgrade_actions` property with the versions and corresponding callback methods to be used. For example:

    protected $upgrade_actions = array(
            '1.0.1' => 'flush_permalinks', 
            '1.1.0' => 'upgrade_1_1_0', 
            '1.1.3' => 'flush_permalinks'
    );

Each upgrade action is represented as a `$key => $value` pair, where the `$key` is the version for which the upgrade should take place, and the `$value` is the name of a method inside the Upgrade class. In the example above, the `flush_permalinks()` method was called when upgrading to 1.0.1 and 1.1.3, while the `upgrade_1_1_0()' method was called on the 1.1.0 upgrade.