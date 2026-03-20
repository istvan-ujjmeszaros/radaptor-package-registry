<?php

abstract class FormCustomValidatorProject extends AbstractForm
{
	protected function _validateData(): void
	{
		parent::_validateData();

		$this->validateProjectName();
		$this->validateConnectedCompany();
	}

	private function validateProjectName(): void
	{
		if (!$this->isModified('name')) {
			return;
		}

		$project_id = EntityProject::autoDetectId('_' . $this->getInput('name')->getValue() . '_');

		if ($project_id !== EntityProject::ERROR_PROJECT_NOT_FOUND && $project_id !== $this->getItemId()) {
			$this->getInput('name')->addError('A project with this name already exists');
		}
	}

	private function validateConnectedCompany(): void
	{
		$this->getInput('connected_company_id')
			 ->setValue(EntityCompany::autoDetectId($this->getInput('connected_company_id')->getValue()));

		switch ($this->getInput('connected_company_id')->getValue()) {
			case EntityCompany::ERROR_COMPANY_ID_EMPTY:
				// Not an error; assignment is optional. Set to null to clear previous assignment.
				$this->getInput('connected_company_id')->setValue(null);

				break;

			case EntityCompany::ERROR_MULTIPLE_COMPANIES:
				$this->getInput('connected_company')
					 ->addError('Multiple companies found for this name. Please select one from the list');

				break;

			case EntityCompany::ERROR_COMPANY_NOT_FOUND:
				$this->getInput('connected_company')
					 ->addError('Company not found. Please select from the list or add a new company');

				break;
		}
	}
}
