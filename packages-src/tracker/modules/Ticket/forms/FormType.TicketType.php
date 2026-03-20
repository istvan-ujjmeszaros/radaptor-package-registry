<?php

class FormTypeTicketType extends FormCustomValidatorTicketType
{
	public const string ID = 'ticket_type';

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
			'path' => '/ticket-types/edit/',
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

				$ticketType = EntityTicket_typ::saveFromArray($this->savedata);
				$this->_item_id = $ticketType->pkey();
				SystemMessages::addSystemMessage(t('ticket.type.saved'));

				break;

			case self::_MODE_UPDATE:

				EntityTicket_typ::updateById($this->getItemId(), $this->savedata);
				SystemMessages::addSystemMessage(t('ticket.type.updated'));

				break;
		}
	}

	public function setMetadata(): void
	{
		if ($this->_mode == self::_MODE_CREATE) {
			$this->_meta->title = t('ticket.type.form.title_create');
		} else {
			$this->_meta->title = t('ticket.type.form.title_edit');
			$this->_meta->sub_title = EntityTicket_typ::getName($this->getItemId());
		}
	}

	public function setInitValues(): void
	{
		$this->initvalues = EntityTicket_typ::findById($this->getItemId())?->data() ?? [];
	}

	public function makeInputs(): void
	{
		$name = new FormInputText('name', $this);
		$name->label = t('ticket.type.field.name.label');
		$name->explanation = t('ticket.type.form.name_explanation');
		$name->addValidator(new FormValidatorNotEmpty(t('form.validation.required')));
		$v = new FormValidatorStringlength(t('form.validation.max_length', ['max' => 64]));
		$v->min = 0;
		$v->max = 64;
		$name->addValidator($v);
	}
}
