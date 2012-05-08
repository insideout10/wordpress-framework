<?php


/**
 * @service form
 * @action submit
 */
class MyForm {
    
    /**
     * @type text
     * @maxSize 255
     * @minSize 2
     * @required yes
     * @label Name
     */
    public $name;

    /**
     * @type text
     * @maxSize 255
     * @minSize 2
     * @required yes
     * @label Surname
     */
    public $surname;

    /**
     * @type text
     * @maxSize 255
     * @minSize 2
     * @required yes
     * @label E-mail
     * @validate [a-zA-Z0-9]+@[a-zA-Z0-9]+
     */
    public $email;

    /**
     * @type text
     * @maxSize 255
     * @minSize 2
     * @required no
     * @label Account Id
     */
    public $accountId;

    /**
     * @type submit
     * @action my_action
     */
    public $my_action;
    
    public function submit() {
        echo "Hello $this->name";
        
        return "Thanks";
    }
    
}

?>