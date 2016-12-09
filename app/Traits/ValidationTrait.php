<?php

namespace ProVision\Administration\Traits;


use Illuminate\Support\Facades\Validator;

trait ValidationTrait {

    /**
     * Errors container
     *
     * @var array
     */
    private $errors = [];

    /**
     * Rules container
     *
     * @var array
     */
    private $rules = [];

    /**
     * @var array
     */
    private $messages = [];

    /**
     * Validate data
     *
     * @param $data
     * @return bool
     */
    public function validate($data) {
        // make a new validator object
        $v = Validator::make($data, $this->rules, $this->messages);

        // check for failure
        if ($v->fails()) {
            // set errors and return false
            $this->errors = $v->errors();
            return false;
        }

        // validation pass
        return true;
    }

    /**
     * @return array
     */
    public function getValidationErrors() {
        return $this->errors;
    }

    /**
     * Set single rule
     *
     * @param $rule
     * @param $value
     */
    public function setValidationRule($rule, $value) {
        $this->rules[$rule] = $value;
    }

    /**
     * @param array $rules
     */
    public function setValidationRules(array $rules) {
        $this->rules = array_merge($this->rules, $rules);
    }


}

?>