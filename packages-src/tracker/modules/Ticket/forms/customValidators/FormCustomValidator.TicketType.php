<?php

abstract class FormCustomValidatorTicketType extends AbstractForm
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

		$ticket_type_id = EntityTicket_typ::getIdByName($this->getInput('name')->getValue());

		if ($ticket_type_id > 0) {
			if ($this->getMode() == self::_MODE_CREATE || ($this->getMode() == self::_MODE_UPDATE && $this->getItemId() != $ticket_type_id)) {
				$this->getInput('name')->addError(t('ticket.type.validation.duplicate'));
			}
		}
	}
}
