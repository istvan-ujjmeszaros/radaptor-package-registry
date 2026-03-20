<?php

abstract class FormCustomValidatorTicket extends AbstractForm
{
	protected function _validateData(): void
	{
		parent::_validateData();

		$this->validateAssignedUser();
		$this->validateConnectedProject();
		$this->validateContactPerson();
	}

	private function validateAssignedUser(): void
	{
		$this->getInput('assigned_user_id')->setValue(User::autoDetectUserId($this->getInput('assigned_user_id')
																				 ->getValue()));

		switch ($this->getInput('assigned_user_id')->getValue()) {
			case UserErrorCode::ERROR_USER_ID_EMPTY->value:
				// ez nem hiba, nem kötelező a hozzárendelés, viszont ilyenkor null-ra kell állítani az értéket
				// (így lehet törölni egy korábbi hozzárendelést)
				$this->getInput('assigned_user_id')->setValue(null);

				break;

			case UserErrorCode::ERROR_MULTIPLE_USERS->value:
				$this->getInput('assigned_user')
					 ->addError(t('ticket.validation.assigned_user_multiple'));

				break;

			case UserErrorCode::ERROR_USER_NOT_FOUND->value:
				$this->getInput('assigned_user')
					 ->addError(t('ticket.validation.assigned_user_not_found'));

				break;
		}
	}

	private function validateContactPerson(): void
	{
		$this->getInput('connected_contactperson_id')
			 ->setValue(EntityContactperson::autoDetectId($this->getInput('connected_contactperson_id')
																	   ->getValue()));

		switch ($this->getInput('connected_contactperson_id')->getValue()) {
			case EntityContactperson::ERROR_CONTACTPERSON_ID_EMPTY:
				// ez nem hiba, nem kötelező a hozzárendelés, viszont ilyenkor null-ra kell állítani az értéket
				// (így lehet törölni egy korábbi hozzárendelést)
				$this->getInput('connected_contactperson_id')->setValue(null);

				break;

			case EntityContactperson::ERROR_MULTIPLE_CONTACTPERSONS:
				$this->getInput('connected_contactperson')
					 ->addError(t('ticket.validation.contactperson_multiple'));

				break;

			case EntityContactperson::ERROR_CONTACTPERSON_NOT_FOUND:
				$this->getInput('connected_contactperson')
					 ->addError(t('ticket.validation.contactperson_not_found'));

				break;
		}
	}

	private function validateConnectedProject(): void
	{
		if ($this->getInput('project_name')->getValue() == '') {
			return;
		}

		$this->getInput('project_id')->setValue(EntityProject::autoDetectId($this->getInput('project_id')
																				   ->getValue()));

		switch ($this->getInput('project_id')->getValue()) {
			case EntityProject::ERROR_PROJECT_ID_EMPTY:
				$this->getInput('project_name')->addError(t('ticket.validation.project_required'));

				break;

			case EntityProject::ERROR_MULTIPLE_PROJECTS:
				$this->getInput('project_name')->addError(t('ticket.validation.project_multiple'));

				// no break
			case EntityProject::ERROR_PROJECT_NOT_FOUND:
				$this->getInput('project_name')->addError(t('ticket.validation.project_not_found'));

				break;
		}
	}
}
