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
  * metaboxes ([add_meta_box](http://codex.wordpress.org/Function_Reference/add_meta_box)).
  * custom image sizes ([add_image_size](http://codex.wordpress.org/Function_Reference/add_image_size)).
  * custom post types ([register_post_type](http://codex.wordpress.org/Function_Reference/register_post_type), [manage_edit-*customType*_columns](http://codex.wordpress.org/Plugin_API/Filter_Reference/manage_edit-post_type_columns), [manage_posts_custom_column](http://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column)).
  * filters ([add_filter](http://codex.wordpress.org/Function_Reference/add_filter)).
  * actions ([add_action](http://codex.wordpress.org/Function_Reference/add_action)).
  * short-codes ([add_shortcode](http://codex.wordpress.org/Function_Reference/add_shortcode)).
* queuing of:
  * scripts ([wp_register_script](http://codex.wordpress.org/Function_Reference/wp_register_script), [wp_enqueue_script](http://codex.wordpress.org/Function_Reference/wp_enqueue_script)).
  * stylesheets ([wp_enqueue_style](http://codex.wordpress.org/Function_Reference/wp_enqueue_style)).
* powerful configuration of **Ajax Services**  ([wp_ajax_(action)](http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_(action\))) with built-in support for:
 * authentication,
  * capabilities authorization,
  * JSON output and
  * compression.
* editor (TinyMCE) customization and configuration:
  * editor style-sheets ([mce_css](http://codex.wordpress.org/Plugin_API/Filter_Reference/mce_css)),
  * editor configuration ([tiny_mce_before_init](http://codex.wordpress.org/Plugin_API/Filter_Reference/tiny_mce_before_init)).
  
## Compatibility

The *IOIO WordPress Framework*  is compatible with:

* PHP 5.2 or above.
* WordPress 3.0 or above.

## Examples

*TODO*

#### An Html-based meta-box

```xml

	<!-- WordPress_HtmlMetaBox is provided out of the box by the WordPress Framework -->
    <class id="entitiesHtmlMetaBox" name="WordPress_HtmlMetaBox"
           filename="/php/insideout/wordpress/metaboxes/HtmlMetaBox.php">
        <dependsOn filename="/php/insideout/wordpress/interfaces/IMetaBox.php" />

        <property name="htmlFilename" value="/wordlift/html/insideout/wordlift/build/disambiguation.html" />
    </class>

	<!-- add some custom styling -->
    <wordpress:style target="admin" name="wordlift.disambiguation.css"
    	url="/wp-content/plugins/wordlift/sass/css/wordlift.disambiguation.css" />

	<!-- add some scripting for a full-fledge client-side AJAX metabox -->
    <wordpress:script target="admin" name="wordlift-disambiguation"
                      version="1.0.0"
                      footer="true"
                      url="/wp-content/plugins/wordlift/js/wordlift.disambiguation.js">
        <dependsOn name="angularjs" />
    </wordpress:script>

    <!-- add the meta-box -->
    <wordpress:metaBox id="wordlift_entity_references_meta_box" title="WordLift Entity References" class="entitiesHtmlMetaBox"
                       postType="post" context="side" priority="high" />

```

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

Parameters:

* **target**, the target (*required*), can be **user**, **admin** to target the front-end or the admin area respectively
* **name**
* **version**
* **footer**, if *true* load the script in the footer,
* **url**, the URL to the javascript file.

### Stylesheets

To configure stylesheets, forget about filters or actions: use the **wordpress:style** element.

Configuration can be applied to any kind of stylesheet, be it for the *user*, *admin* and also the *editor* (TinyMCE) area.

```xml
    <wordpress:style target="user" name="style.css"
   					  url="/wp-content/plugins/wordlift/sass/css/style.css" />

    <wordpress:style target="admin" name="admin.css"
    				  url="/wp-content/plugins/wordlift/sass/css/admin.css" />

	<wordpress:style target="editor" name="wordlift.disambiguate.css"
				 	 url="../wp-content/plugins/wordlift/sass/css/wordlift.disambiguate.css" />
```

Parameters:

* **name**, the name of the stylesheet (*required*), e.g. "style.css"
* **url**, the URL, relative or absolute (*required*), e.g. "http://my.wordpress.blog/wp-content/plugins/my-plugin/css/style.css"
* **target**, the target (*required*), can be **user**, **admin** or **editor** to target the front-end, the admin area or the editor iframe respectively.
* **version**, the version (*optional*), e.g. "1.0.0"
* **media**, the media (*optional*), e.g. "screen"


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

### Editor Configuration (TinyMCE)

To configure the WordPress editor (TinyMCE) during initialization, use the **wordpress:editor** element. Any valid property can be set.

```xml
	<wordpress:editor property="extended_valid_elements" value="span[about|class|id|typeof]" />
```

Parameters:

* **property**, the name of the editor configuration property (*required*),
* **value**, the value to add to the property (*required*).


### Logging

## Contributions

## Road Map

## License

*TODO*