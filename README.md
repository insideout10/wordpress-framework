WordPress Framework
===================

The *WordPress Framework* aims to be **an easy to use PHP library for rapid development of WordPress Plugins**. It is based on the [Inversion of Control pattern](http://en.wikipedia.org/wiki/Inversion_of_control), that allows clean instantiation of plug-in class instances:

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
    </class>
```
