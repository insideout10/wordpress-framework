<?php

/**
 * class
 *  - meta
 *  - constructors
 *  - properties
 *  - methods
 *    - meta
 *    - arguments
 */
class XRayService {
    
    const PATTERN = "/@(.*?) (.*)/";
    const DESCRIPTORS = "descriptors";
    const METHODS = "methods";
    const KEY = 1;
    const VALUE = 2;
    
    public static function scan($className) {

        $reflectionClass = new ReflectionClass($className);

        // get the methods descriptors.
        $methods = array();
        
        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            $name = $reflectionMethod->name;

            $descriptors = self::getDescriptors(
                $reflectionMethod->getDocComment()
            );

            $methods[] = array(
                $name => array(
                    self::DESCRIPTORS => $descriptors
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