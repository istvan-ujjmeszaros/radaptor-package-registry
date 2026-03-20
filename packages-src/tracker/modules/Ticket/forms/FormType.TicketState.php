<?php

class FormTypeTicketState extends FormCustomValidatorTicketState
{
	public const string ID = 'ticket_state';

	public static function getName(): string
	{
		return t('form.' . self::ID . '.name');
	}

	public static function getDescription(): string
	{
		return t('form.' . self::ID . '.description');
	}

	public static function getListVisibility(): bool
	{
		return Roles::hasRole(RoleList::ROLE_SYSTEM_DEVELOPER);
	}

	public static function getDefaultPathForCreation(): array
	{
		return [
			'path' => '/ticket-states/edit/',
			'resource_name' => 'index.html',
			'layout' => 'public_default',
		];
	}

	public function hasRole(): bool
	{
		return Roles::hasRole(RoleList::ROLE_SYSTEM_DEVELOPER);
	}

	public function commit(): void
	{
		switch ($this->getMode()) {
			case self::_MODE_CREATE:

				$ticketState = EntityTicket_stat::saveFromArray($this->savedata);
				$this->_item_id = $ticketState->pkey();
				SystemMessages::addSystemMessage(t('ticket.state.saved'));

				break;

			case self::_MODE_UPDATE:

				EntityTicket_stat::updateById($this->getItemId(), $this->savedata);
				SystemMessages::addSystemMessage(t('ticket.state.updated'));

				break;
		}
	}

	public function setMetadata(): void
	{
		if ($this->_mode == self::_MODE_CREATE) {
			$this->_meta->title = t('ticket.state.form.title_create');
		} else {
			$this->_meta->title = t('ticket.state.form.title_edit');
			$this->_meta->sub_title = EntityTicket_stat::getName($this->getItemId());
		}
	}

	public function setInitValues(): void
	{
		$this->initvalues = EntityTicket_stat::findById($this->getItemId())?->data() ?? [];
	}

	public function makeInputs(): void
	{
		$name = new FormInputText('name', $this);
		$name->label = t('ticket.state.field.name.label');
		$name->explanation = t('ticket.state.form.name_explanation');
		$name->addValidator(new FormValidatorNotEmpty(t('form.validation.required')));
		$v = new FormValidatorStringlength(t('form.validation.max_length', ['max' => 64]));
		$v->min = 0;
		$v->max = 64;
		$name->addValidator($v);

		$is_open = new FormInputCheckbox('is_open', $this);
		$is_open->label = t('ticket.state.field.is_open.label');
		$is_open->explanation = t('ticket.state.form.is_open_explanation');
	}
}
