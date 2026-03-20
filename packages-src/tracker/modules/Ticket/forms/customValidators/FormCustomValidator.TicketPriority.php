<?php

abstract class FormCustomValidatorTicketPriority extends AbstractForm
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

		$ticket_priority_id = EntityTicket_priority::getIdByName($this->getInput('name')->getValue());

		if ($ticket_priority_id > 0) {
			if ($this->getMode() == self::_MODE_CREATE || ($this->getMode() == self::_MODE_UPDATE && $this->getItemId() != $ticket_priority_id)) {
				$this->getInput('name')->addError(t('ticket.priority.validation.duplicate'));
			}
		}
	}
}
