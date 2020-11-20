<?php

require 'ErrorHandler.php';

class Validator
{
    protected $errorHandler;

    protected $rules = [
        'required', 'maxlength', 'minlength', 'email', 'rfc', 'cp'
    ];

    public $messages = [
        'required'  => 'The :field field is required',
        'minlength' => 'The :field field must be a minimum of :satisifer length',
        'maxlength' => 'The :field field must be a maximum of :satisifer length',
        'email'     => 'That is not a valid email address',
        'rfc'       => 'That is not a valid RFC',
        'cp'        => 'That is not a valid postal code',
    ];

    public function __construct()
    {
        $this->errorHandler = new ErrorHandler;
    }

    public function check($items, $rules)
    {
        foreach ($items as $item => $value) {
            if (in_array($item, array_keys($rules))) {
                $this->validate([
                    'field' => $item,
                    'value' => $value,
                    'rules' => $rules[$item]
                ]);
            }
        } return $this;
    }

    protected function validate($item)
    {
        $field = $item['field'];
        foreach ($item['rules'] as $rule => $satisifer) {
            if (in_array($rule, $this->rules )) {
                if (! call_user_func_array(
                    [$this, $rule], [$field, $item['value'], $satisifer])
                ) {
                    $this->errorHandler->addError(
                        str_replace(
                            [':field', ':satisifer'],
                            [$field, $satisifer],
                            $this->messages[$rule]
                        ),
                        $field
                    );
                }
            }
        }
    }

    public function fails()
    {
        return $this->errorHandler->hasErrors();
    }

    public function errors()
    {
        return $this->errorHandler;
    }

    protected function required($field, $value, $satisifer)
    {
        return !empty(trim($value));
    }

    protected function minlength($field, $value, $satisifer)
    {
        return mb_strlen($value) >= $satisifer;
    }

    protected function maxlength($field, $value, $satisifer)
    {
        return mb_strlen($value) <= $satisifer;
    }

    protected function email($field, $value, $satisifer)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) <= $satisifer;
    }

    protected function rfc($field, $value, $satisifer){
        if (!empty(trim($value))) {
            $regex = '/^[A-Z,Ñ,&,a-z,ñ]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,a-z,0-9][A-Z,a-z,0-9][0-9,A-Z,a-z]?$/D';
            if (preg_match($regex, $value)) {
                return true;
            }
            else{
                return false;
            }
        }
        return true;
    }

    protected function cp($field, $value, $satisifer){
        if (!empty(trim($value))) {
             $regex = "/^[0-9a-zA-Z]+$/";
            if (preg_match($regex, $value)) {
                return true;
            }
            else{
                return false;
            }
        }
        return true;
    }
}