<?php

namespace Validations\Assertions;

class Assertion {
	public $field = "";
	public $fieldValue = "";
	public $method = "";
	public $class = "";
	public $params = [];

	public function __construct($field, $fieldValue, $class, $method, $params) {
		$this->field = $field;
		$this->fieldValue = $fieldValue;
		$this->class = $class;
		$this->method = $method;
		$this->params = $params;
	}

	public function getProperties() {
		return get_object_vars($this);
	}
}
