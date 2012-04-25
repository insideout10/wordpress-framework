<?php

/**
 * class
 *  - meta
 *  - constructors
 *  - properties
 *  - methods
 *    - meta
 *    - parameters
 */
class XRayService {
    
    const PATTERN = "/@(.*?) (.*)/";
    const DESCRIPTORS = "descriptors";
    const METHODS = "methods";
    const PARAMETERS = "parameters";

    const KEY = 1;
    const VALUE = 2;
    
    public static function scan($className) {

        $reflectionClass = new ReflectionClass($className);

        // get the methods descriptors.
        $methods = array();
        
        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            $methodName = $reflectionMethod->name;

            $descriptors = self::getDescriptors(
                $reflectionMethod->getDocComment()
            );

            // get the method parameters.
            $parameters = array();
            
            foreach ($reflectionMethod->getParameters() as $parameter)
                $parameters[] = $parameter->name;
            
            // save the methods and their parameters to an array.
            $methods = array_merge(
                $methods,
                array(
                    $methodName => array(
                        self::DESCRIPTORS => $descriptors,
                        self::PARAMETERS => $parameters
                    )
                )
            );
        }

        // get the class descriptors.
        $descriptors = self::getDescriptors(
            $reflectionClass->getDocComment()
        );

        $class = array(
            $className => array(
                self::DESCRIPTORS => $descriptors,
                self::METHODS => $methods
            )
        );

        return $class;
    }
    
    private static function getDescriptors($docComment) {
        $matches = array();
        preg_match_all(self::PATTERN, $docComment, &$matches, PREG_SET_ORDER);

        return $matches;
    }
    
}

?>