<?php

abstract class FormCustomValidatorContactPerson extends AbstractForm
{
	protected function _validateData(): void
	{
		parent::_validateData();

		$this->validateConnectedCompany();
		$this->validateContactPersonIsUniqueInCompany();
	}

	private function validateConnectedCompany(): void
	{
		$this->getInput('connected_company_id')
			 ->setValue(EntityCompany::autoDetectId($this->getInput('connected_company_id')->getValue()));

		switch ($this->getInput('connected_company_id')->getValue()) {
			case EntityCompany::ERROR_COMPANY_ID_EMPTY:
				// ez nem hiba, nem kötelező a hozzárendelés, viszont ilyenkor null-ra kell állítani az értéket
				// (így lehet törölni egy korábbi hozzárendelést)
				$this->getInput('connected_company_id')->setValue(null);

				break;

			case EntityCompany::ERROR_MULTIPLE_COMPANIES:
				$this->getInput('connected_company')
					 ->addError(t('contact.field.company.multiple_error'));

				break;

			case EntityCompany::ERROR_COMPANY_NOT_FOUND:
				$this->getInput('connected_company')
					 ->addError(t('contact.field.company.not_found_error'));

				break;
		}
	}

	private function validateContactPersonIsUniqueInCompany(): void
	{
		if (!$this->getInput('name')->isValid()) {
			return;
		}

		if (!$this->getInput('connected_company_id')->isValid()) {
			return;
		}

		$contactperson_id = EntityContactperson::getIdFromNameAndCompanyId($this->getInput('name'), $this->getInput('connected_company_id')->getValue());

		if ($contactperson_id > 0) {
			if ($this->getMode() == self::_MODE_CREATE || ($this->getMode() == self::_MODE_UPDATE && $this->getItemId() != $contactperson_id)) {
				$this->getInput('name')->addError(t('contact.field.name.unique_error'));
			}
		}
	}
}
