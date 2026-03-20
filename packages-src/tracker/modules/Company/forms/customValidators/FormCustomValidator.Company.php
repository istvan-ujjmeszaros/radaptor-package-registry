<?php

abstract class FormCustomValidatorCompany extends AbstractForm
{
	protected function _validateData(): void
	{
		parent::_validateData();

		$this->validateUniqueCompanyName();
	}

	private function validateUniqueCompanyName(): void
	{
		if (!$this->getInput('name')->isValid()) {
			return;
		}

		if (!is_null($this->getInput('name')->getValue())) {
			$company_id = EntityCompany::getIdFromName($this->getInput('name')->getValue());

			if ($company_id > 0) {
				if ($this->getMode() == self::_MODE_CREATE || ($this->getMode() == self::_MODE_UPDATE && $this->getItemId() != $company_id)) {
					$this->getInput('name')->addError(t('company.field.name.unique_error'));
				}
			}
		}
	}
}
