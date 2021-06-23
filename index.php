<?php

require "vendor/autoload.php";

use Validations\Validators\SchemaValidator;

class ProdutoValidator extends SchemaValidator {

	/**
	 * @validate isNumber::max(30)
	 */
	public $id = 50;

	/**
	 * @validate required
	 * @validate isString::minLength(10)
	 */
	public $descricao = "oi";
}
$result = (new ProdutoValidator())->validate();

var_dump($result);
