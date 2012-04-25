<?php

class AjaxService {
    
    const SERVICE = "service";
    const AUTHENTICATION = "authentication";
    const ACTION = "action";
    const COMPRESSION = "compression";
    
    const SERVICE_AJAX ="ajax";
    const AUTHENTICATION_REQUIRED = "required";
    
    const CALLBACK_ERROR = FALSE;
    
    const WP_AJAX_NOPRIV = "wp_ajax_nopriv_";
    const WP_AJAX_ = "wp_ajax_";
    
    public static function setUpClass($xRayClass) {
        
        $className = key($xRayClass);
        
        $methods = &$xRayClass[$className][XRayService::METHODS];
        
        foreach ($methods as $method) {
            $methodName = key($method);
            
            $descriptors = $method[$methodName][XRayService::DESCRIPTORS];

            $service = null;
            $authentication = null;
            $action = null;
            $compression = true;

            foreach ($descriptors as $descriptor) {
                switch ($descriptor[XRayService::KEY]) {
                    case self::SERVICE:
                        $service = $descriptor[XRayService::VALUE];
                        break;

                    case self::AUTHENTICATION:
                        $authentication = $descriptor[XRayService::VALUE];
                        break;

                    case self::ACTION:
                        $action = $descriptor[XRayService::VALUE];
                        break;

                    case self::COMPRESSION:
                        $compression = (null === $descriptor[XRayService::VALUE] || 'false' !== $descriptor[XRayService::VALUE]);
                        break;
                }
            }
            
            if (self::SERVICE_AJAX === $service && null != $action) {
                // enable public access to the ajax end-point.
                if (null === $authentication || self::AUTHENTICATION_REQUIRED !== $authentication)
                    do_action($actionName = self::WP_AJAX_NOPRIV . $action);

                // enable protected access to the ajax end-point.
                do_action(self::WP_AJAX_ . $action);
                
                // bind the action to the function.
                $compression = var_export($compression, true);
                add_action($actionName, create_function('$parameters',
                            __CLASS__ . "::proxy( array('$className', '$methodName'), \$parameters, $compression);"
                    )
                );
            }
        }        
    }
    
    public static function proxy($method, $parameters, $compression) {
        $returnValue = call_user_func( $method, $parameters);
        
        if (self::CALLBACK_ERROR === $returnValue) {
            // error.
            exit;
        }
        
        JsonService::sendResponse($returnValue, $compression);
        
        exit;
    }    
}

?>