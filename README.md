IOIO WordPress Framework
========================

The *IOIO WordPress Framework* aims to be **an easy to use PHP library for rapid development of WordPress Plugins**. It is based on the [Inversion of Control](http://en.wikipedia.org/wiki/Inversion_of_control) and [Depedency Injection](http://en.wikipedia.org/wiki/Dependency_injection) patterns, that allows clean creation of plug-in class instances:

```php
    $applicationContext = WordPress_XmlApplication::getContext( "myPlugin" );
	$myService = $applicationContext->getClass( "myService" );
```

All the configuration for PHP classes is defined in the plugin Xml file:

```xml
    <class id="myService" name="MyPlugin_MyService"
           filename="/php/myplugin/MyService.php">
        <dependsOn filename="/php/myplugin/SomeOtherFile.php" />

        <property name="myProperty" value="has-a-value" />
        <property name="myClass" reference="anotherClass" />
    </class>
```

## Features


The *IOIO WordPress Framework* supports many features, among those:

* configuration via Xml file.
* context names.
* properties placeholders.
* define classes along with properties.
* properties can be values or references to other classes. 
* configuration and registration (hooks) of:
  * metaboxes [[add_meta_box](http://codex.wordpress.org/Function_Reference/add_meta_box)].
  * custom image sizes [[add_image_size](http://codex.wordpress.org/Function_Reference/add_image_size)].
  * custom post types [[register_post_type](http://codex.wordpress.org/Function_Reference/register_post_type), [manage_edit-*customType*_columns](http://codex.wordpress.org/Plugin_API/Filter_Reference/manage_edit-post_type_columns), [manage_posts_custom_column](http://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column)].
  * filters [[add_filter](http://codex.wordpress.org/Function_Reference/add_filter)].
  * actions [[add_action](http://codex.wordpress.org/Function_Reference/add_action)].
  * short-codes [[add_shortcode](http://codex.wordpress.org/Function_Reference/add_shortcode)].
* queuing of:
  * scripts [[wp_register_script](http://codex.wordpress.org/Function_Reference/wp_register_script), [wp_enqueue_script](http://codex.wordpress.org/Function_Reference/wp_enqueue_script)].
  * stylesheets [[wp_enqueue_style](http://codex.wordpress.org/Function_Reference/wp_enqueue_style)].
* powerful configuration of **Ajax Services**  [[wp_ajax_(action)]("http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_(action)")] with built-in support for:
  * authentication,
  * capabilities authorization,
  * JSON output and
  * compression.
  
## Compatibility

The *IOIO WordPress Framework*  is compatible with:

* PHP 5.2 or above.
* WordPress 3.0 or above.

## Examples

*TODO*

## How To

*TODO*

### Initial Set-up

### Context Name

#### Use of context name

### Class configuration

#### Dependencies

#### Value properties

##### Properties placeholders

#### Reference properties

### Metaboxes

### Custom image sizes

### Custom post types

### Filters

### Actions

### Short Codes

### Scripts

```xml
  <wordpress:script target="admin" name="angularjs"
                      version="1.0.1"
                      footer="true"
                      url="/wp-content/plugins/wordlift/js/angular-1.0.1.min.js">
    </wordpress:script>

    <wordpress:script target="admin" name="angularjs-resource"
                      version="1.0.1"
                      footer="true"
                      url="/wp-content/plugins/wordlift/js/angular-resource-1.0.1.min.js">
        <dependsOn name="angularjs" />
    </wordpress:script>
```

Parameters

* **target**, user or admin,
* **name**
* **version**
* **footer**, if *true* load the script in the footer,
* **url**, the URL to the javascript file.

### Stylesheets

### Ajax Services

Example:


```xml

    <wordpress:ajax service="ajaxService" action="wordlift.job" class="jobAjaxService" method="createJob"
                    authentication="false" capabilities="any" httpMethod="GET" />

    <wordpress:ajax service="ajaxService" action="wordlift.job" class="jobAjaxService" method="getJob"
                    authentication="false" capabilities="any" httpMethod="POST" />

    <wordpress:ajax service="ajaxService" action="wordlift.job" class="jobAjaxService" method="updateJob"
                    authentication="false" capabilities="any" httpMethod="PUT" />
```

Parameters

* **service**, use ajaxService
* **action**, the name of the action (admin-ajax.php?action=*name-of-the-action*)
* **httpMethod**, the HTTP method for this action (default GET),
* **class**, the name of the class that will handle the call,
* **method**, the method inside the class,
* **authentication**, if true, authentication is required,
* **capabilities**, the list of capabilities required.

#### Security configuration

##### Authorization

##### Capabilities

### Logging

## Contributions

## Road Map

## License

*TODO*