<?php

abstract class FormCustomValidatorTicketState extends AbstractForm
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

		$ticket_state_id = EntityTicket_stat::getIdByName($this->getInput('name')->getValue());

		if ($ticket_state_id > 0) {
			if ($this->getMode() == self::_MODE_CREATE || ($this->getMode() == self::_MODE_UPDATE && $this->getItemId() != $ticket_state_id)) {
				$this->getInput('name')->addError(t('ticket.state.validation.duplicate'));
			}
		}
	}
}
