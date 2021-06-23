<?php

namespace Validations\Assertions;

use Validations\Exceptions\ValidationException;

class isNumber {

	public function max($value, $max) {
		if (!is_numeric($value)) {
			throw new ValidationException("Formato do valor inválido");
		}

		if ($value > $max) {
			throw new ValidationException("O valor deve ser no máximo {$max}.");
		}
	}
}
