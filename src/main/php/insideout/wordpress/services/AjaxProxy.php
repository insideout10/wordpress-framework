<?php
/**
 * User: david
 * Date: 21/07/12 10:15
 */


class WordPress_AjaxProxy {

    const REQUEST_BODY = "requestBody";
    const PHP_INPUT = "php://input";
    const CALLBACK_RETURN_ERROR = FALSE;
    const CALLBACK_RETURN_NULL = null;

    private $instance;
    private $method;
    private $httpMethod;
    private $action;
    private $authentication;
    private $capabilities;
    private $compression;
    private $jsonService;

    private $logger;

    function __construct( $instance, $method, $httpMethod, $action, $authentication, $capabilities, $compression, &$jsonService, $logger ) {
        $this->instance = $instance;
        $this->method = $method;
        $this->httpMethod = $httpMethod;
        $this->action = $action;
        $this->authentication = $authentication;
        $this->capabilities = $capabilities;
        $this->compression = $compression;
        $this->jsonService = $jsonService;

        $this->logger = $logger;
    }


    private function checkHttpMethod() {
        $httpRequestMethod = $_SERVER['REQUEST_METHOD'];
        $this->logger->trace( "[ httpMethod :: $this->httpMethod ][ httpRequestMethod :: $httpRequestMethod ]." );

        return ( $httpRequestMethod === $this->httpMethod );
    }

    private function checkCapabilities() {
        if ( "any" === $this->capabilities )
            return;

        $capabilities = explode(",", $this->capabilities);

        foreach ($capabilities as $capability) {
            if (false === current_user_can($capability) ) {
                // TODO: format errors and send them to JSON.
                header("Content-type: application/json");
                echo "{\"error\": \"the current user is lacking the " . $capability . " capability.\"}";
                exit;
            }
        }
    }

    public function invoke() {

        // return if the current request method does not match the configured http method.
        if ( false === $this->checkHttpMethod() ) {
            $this->logger->trace( "The request method does not match the configured http method [ httpMethod :: $this->httpMethod ]." );
            return;
        }

        $this->checkCapabilities();

        $reflectionClass = new ReflectionClass( get_class( $this->instance ) );
        $reflectionMethod = $reflectionClass->getMethod( $this->method );
        $parameters = $reflectionMethod->getParameters();

        $args = array();

        foreach ($parameters as $parameter) {
            $parameterName = $parameter->getName();

            if (self::REQUEST_BODY === $parameterName) {
                array_push( $args, file_get_contents(self::PHP_INPUT) );
                continue;
            }

            if ( !array_key_exists( $parameterName, $_REQUEST ) ) {
                if ( !$parameter->isOptional() )
                    throw new Exception( "Parameter [$parameterName] is required." );
                else
                    array_push( $args, $parameter->getDefaultValue() );

                continue;
            }

            array_push( $args, $_REQUEST[$parameterName] );
        }

        $returnValue = call_user_func_array( array( $this->instance, $this->method ), $args);

        if (self::CALLBACK_RETURN_ERROR === $returnValue) {
            // error.
            exit;
        }

        if (self::CALLBACK_RETURN_NULL === $returnValue) {
            // no response / maybe the method returned its own.
            exit;
        }

        $this->jsonService->sendResponse($returnValue, $this->compression);

        exit;
    }
}

?>