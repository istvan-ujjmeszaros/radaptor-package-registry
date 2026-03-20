<?php

abstract class FormCustomValidatorProjectState extends AbstractForm
{
	protected function _validateData(): void
	{
		parent::_validateData();

		$this->validateUniqueName();
	}

	private function validateUniqueName(): void
	{
		if ($this->getInput('name')->getValue() == '') {
			return;
		}

		$project_state_id = EntityProject_stat::getIdByName($this->getInput('name')->getValue());

		if ($project_state_id > 0) {
			if ($this->getMode() == self::_MODE_CREATE || ($this->getMode() == self::_MODE_UPDATE && $this->getItemId() != $project_state_id)) {
				$this->getInput('name')->addError('This project status already exists');
			}
		}
	}
}
