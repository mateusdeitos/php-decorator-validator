<?php

namespace Validations\Assertions;

use Validations\Exceptions\ValidationException;

class isString {

	public function minLength($value, $min) {
		if(mb_strlen($value) < $min) {
			throw new ValidationException("O valor deve ter um tamanho de no mínimo {$min} caracteres.");
		};
	}

}
