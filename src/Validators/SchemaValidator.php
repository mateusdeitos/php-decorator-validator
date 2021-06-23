<?php

namespace Validations\Validators;
use Validations\Assertions\Assertion;
use ReflectionClass;
use ReflectionProperty;

abstract class SchemaValidator {

	/**
	 * @var ValidationHandler[]
	 */
	private $schemas = [];

	public function validate() {
		$this->generateSchemas();
		$result = [];
		foreach ($this->schemas as $schema) {
			$result[] = $schema->assert();
		}
		return $result;
	}

	protected function generateSchemas() {
		$assertions = $this->getClassAssertions();
		foreach ($assertions as $assertion) {
			$this->schemas[] = new ValidationHandler($assertion);
		}
	}

	/**
	 * @return Assertion[]
	 */
	private function getClassAssertions() {
		$props = (new ReflectionClass($this))->getProperties();
		$assertions = [];
		foreach ($props as $prop) {
			foreach ($this->getPropValidations($prop) as $validation) {
				if (empty(trim($validation))) {
					continue;
				}

				$assertions[] = $this->parseValidation($prop, trim($validation));
			}
		}

		return $assertions;

	}

	private function getPropValidations(ReflectionProperty $prop): array {
		$comments = (new ReflectionProperty($this, $prop->getName()))->getDocComment();
		$comments = trim(str_replace(["\n", "*", "/"], "", $comments));
		return explode("@validate", $comments);
	}

	private function parseValidation(ReflectionProperty $prop, $validation): Assertion {
		$assertionClass = $this->parseClass($validation);
		$assertionMethod = $this->parseMethod($validation);
		$parsedParams = $this->parseValidationParameters($validation);
		return new Assertion($prop->getName(), $this->{$prop->getName()}, $assertionClass, $assertionMethod, $parsedParams);
	}

	private function parseClass($validation) {
		$matches = [];
		preg_match("/[^(]*/", $validation, $matches);
		$exploded = explode("::", $matches[0]);
		[$class] = $exploded;
		if (count($exploded) == 1) {
			$class = "Common";
		}
		return "\\Validations\\Assertions\\{$class}";
	}

	private function parseMethod($validation) {
		$matches = [];
		preg_match("/[^(]*/", $validation, $matches);
		$exploded = explode("::", $matches[0]);
		if (count($exploded) == 1) {
			[$method] = $exploded;
		} else {
			[,$method] = $exploded;
		}
		return $method;
	}

	private function parseValidationParameters($validation) {
		$matches = [];
		preg_match("/\(([^\)]+)\)/", $validation, $matches);
		if (!empty($matches)) {
			return explode(',', $matches[1]);
		}
		return [];
	}

}
