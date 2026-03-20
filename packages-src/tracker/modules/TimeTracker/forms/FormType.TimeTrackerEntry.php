<?php

class FormTypeTimeTrackerEntry extends AbstractForm
{
	public const string ID = 'time_tracker_entry';

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
			'path' => '/timetracker/edit/',
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
				$entity = EntityTimetracker::saveFromArray($this->savedata);
				$this->_item_id = $entity->pkey();
				SystemMessages::addSystemMessage(t('timetracker.saved'));

				break;

			case self::_MODE_UPDATE:
				EntityTimetracker::updateById($this->getItemId(), $this->savedata);
				SystemMessages::addSystemMessage(t('timetracker.updated'));

				break;
		}
	}

	public function setMetadata(): void
	{
		if ($this->_mode == self::_MODE_CREATE) {
			$this->_meta->title = t('timetracker.form.title_create');
		} else {
			$this->_meta->title = t('timetracker.form.title_edit');
		}
	}

	public function setInitValues(): void
	{
		$this->initvalues = EntityTimetracker::findById($this->getItemId())?->data() ?? [];

		if ($this->initvalues['connected_ticket_id']) {
			$this->initvalues['ticket_name'] = EntityTicket::getName($this->initvalues['connected_ticket_id']);
		}

		if ($this->initvalues['user_id']) {
			$this->initvalues['user_id_name'] = User::getUsername($this->initvalues['user_id']);
		}
	}

	public function makeInputs(): void
	{
		$description = new FormInputText('description', $this);
		$description->label = t('timetracker.field.description.label');
		$description->explanation = t('timetracker.field.description.explanation');
		$v = new FormValidatorStringlength(t('timetracker.validation.description_max_length'));
		$v->min = 0;
		$v->max = 255;
		$description->addValidator($v);

		$connected_ticket_id = new FormInputText('connected_ticket_id', $this);
		$connected_ticket_id->label = t('timetracker.field.ticket_id.label');

		$ticket_name = new FormInputText('ticket_name', $this);
		$ticket_name->save = false;
		$ticket_name->label = t('timetracker.field.ticket.label');
		$ticket_name->explanation = t('timetracker.field.ticket.explanation');
		$v = new FormValidatorStringlength(t('timetracker.validation.ticket_max_length'));
		$v->min = 0;
		$v->max = 255;
		$ticket_name->addValidator($v);
		$ticket_name->autocomplete_url = Url::getAjaxUrl('TimeTracker.ajax_ticketListAutocomplete', ['filtered' => '0']);
		$ticket_name->connected_autocomplete_fieldname = 'connected_ticket_id';

		$user_id = new FormInputText('user_id', $this);
		$user_id->label = t('user.field.user_id.label');

		$user_id_name = new FormInputText('user_id_name', $this);
		$user_id_name->save = false;
		$user_id_name->label = t('user.field.username.label');
		$user_id_name->explanation = t('timetracker.field.user.explanation');
		$user_id_name->autocomplete_url = Url::getAjaxUrl('users.ajax_userListAutocomplete');
		$user_id_name->connected_autocomplete_fieldname = 'user_id';

		$start_time = new FormInputDateTime('start_time', $this);
		$start_time->label = t('timetracker.field.date.label');
		$start_time->explanation = t('timetracker.field.date.explanation');

		$end_time = new FormInputDateTime('end_time', $this);
		$end_time->label = t('timetracker.field.hours.label');
		$end_time->explanation = t('timetracker.field.hours.explanation');
	}
}
