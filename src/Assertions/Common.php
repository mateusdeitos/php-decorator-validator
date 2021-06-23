<?php
namespace Validations\Assertions;

use Validations\Exceptions\ValidationException;

class Common {
	public function required($value) {
		if (empty($value)) {
			throw new ValidationException("O valor é obrigatório");
		}
	}
}
