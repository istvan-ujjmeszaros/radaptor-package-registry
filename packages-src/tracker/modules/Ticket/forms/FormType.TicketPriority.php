<?php

class FormTypeTicketPriority extends FormCustomValidatorTicketPriority
{
	public const string ID = 'ticket_priority';

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
			'path' => '/ticket-priorities/edit/',
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

				$ticketPriority = EntityTicket_priority::saveFromArray($this->savedata);
				$this->_item_id = $ticketPriority->pkey();
				SystemMessages::addSystemMessage(t('ticket.priority.saved'));

				break;

			case self::_MODE_UPDATE:

				EntityTicket_priority::updateById($this->getItemId(), $this->savedata);
				SystemMessages::addSystemMessage(t('ticket.priority.updated'));

				break;
		}
	}

	public function setMetadata(): void
	{
		if ($this->_mode == self::_MODE_CREATE) {
			$this->_meta->title = t('ticket.priority.form.title_create');
		} else {
			$this->_meta->title = t('ticket.priority.form.title_edit');
			$this->_meta->sub_title = EntityTicket_priority::getName($this->getItemId());
		}
	}

	public function setInitValues(): void
	{
		$this->initvalues = EntityTicket_priority::findById($this->getItemId())?->data() ?? [];
	}

	public function makeInputs(): void
	{
		$name = new FormInputText('name', $this);
		$name->label = t('ticket.priority.field.name.label');
		$name->explanation = t('ticket.priority.form.name_explanation');
		$name->addValidator(new FormValidatorNotEmpty(t('form.validation.required')));
		$v = new FormValidatorStringlength(t('form.validation.max_length', ['max' => 64]));
		$v->min = 0;
		$v->max = 64;
		$name->addValidator($v);

		$seq = new FormInputText('seq', $this);
		$seq->label = t('ticket.priority.field.seq.label');
		$seq->explanation = t('ticket.priority.form.seq_explanation');
		$v = new FormValidatorNumber(t('ticket.priority.validation.seq_number'));
		$seq->addValidator($v);
		$v = new FormValidatorRange(t('ticket.priority.validation.seq_range'));
		$v->min = 0;
		$v->max = 255;
		$seq->addValidator($v);
	}
}
