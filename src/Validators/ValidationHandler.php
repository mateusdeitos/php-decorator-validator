<?php

namespace Validations\Validators;

use Exception;
use Validations\Assertions\Assertion;
use Validations\Exceptions\ValidationException;

class ValidationHandler {
	public $assertion = null;

	public function __construct(Assertion $assertion) {
		$this->assertion = $assertion;
	}

	public function assert() {
		if (!class_exists($this->assertion->class)) {
			throw new Exception("Classe não encontrada: {$this->assertion->class}");
		}

		$instance = new $this->assertion->class;
		$params = $this->getAssertionParams();
		try {
			call_user_func_array([$instance, $this->assertion->method], $params);
			return [
				"field" => $this->assertion->field,
				"fieldValue" => $this->assertion->fieldValue,
				"params" => $this->assertion->params,
				"result" => "OK",
			];

		} catch (ValidationException $e) {
			return [
				"field" => $this->assertion->field,
				"fieldValue" => $this->assertion->fieldValue,
				"params" => $this->assertion->params,
				"result" => "ERROR",
				"message" => $e->getMessage()
			];
		}

	}

	public function getAssertionParams() {
		$params[] = $this->assertion->fieldValue;
		foreach ($this->assertion->params as $param) {
			$params[] = $param;
		}

		return $params;
	}
}
