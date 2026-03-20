<?php

class FormTypeTicket extends FormCustomValidatorTicket
{
	public const string ID = 'ticket';

	/**
	 * @return array<string, string>
	 */
	public static function buildExtensionStrings(): array
	{
		return [
			'user.form.title_create' => t('user.form.title_create'),
			'project.form.title_create' => t('project.form.title_create'),
			'contact.form.title_create' => t('contact.form.title_create'),
		];
	}

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
			'path' => '/tickets/edit/',
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
		$tags = EntityTag::extractTagsFromString($this->getInput('tags'));

		Audit::beginModificationGroup('tracker_ticket', $this->getItemId());

		switch ($this->getMode()) {
			case self::_MODE_CREATE:

				$ticket = EntityTicket::saveFromArray($this->savedata);
				$ticket_id = $ticket->pkey();
				$this->_item_id = $ticket_id;

				Audit::setModificationGroupConnectedData('tracker_ticket', $ticket_id);

				SystemMessages::addSystemMessage(t('ticket.saved'));

				EntityTag::updateAllTags('tracker_ticket', $ticket_id, $tags);

				break;

			case self::_MODE_UPDATE:

				if (EntityTag::updateAllTags('tracker_ticket', $this->getItemId(), $tags)) {
					SystemMessages::addSystemMessage(t('ticket.tags_updated'));
				}

				EntityTicket::updateById($this->getItemId(), $this->savedata);
				SystemMessages::addSystemMessage(t('ticket.updated'));

				break;
		}

		Audit::endModificationGroup();
	}

	public function setMetadata(): void
	{
		if ($this->_mode == self::_MODE_CREATE) {
			$this->_meta->title = t('ticket.form.title_create');
		} else {
			$this->_meta->title = t('ticket.form.title_edit');
			$this->_meta->sub_title = EntityTicket::getName($this->getItemId());
		}
	}

	public function setInitValues(): void
	{
		$this->initvalues = EntityTicket::findById($this->getItemId())?->data() ?? [];

		$this->initvalues['tags'] = EntityTag::getConnectedTagNamesString('tracker_ticket', $this->getItemId());

		if ($this->initvalues['project_id']) {
			$this->initvalues['project_name'] = EntityProject::getName($this->initvalues['project_id']);
		}

		if ($this->initvalues['assigned_user_id']) {
			$assigned_user_data = User::getUserFromId($this->initvalues['assigned_user_id']);

			if ($assigned_user_data) {
				$this->initvalues['assigned_user'] = $assigned_user_data['username'];
			}
		}

		if ($this->initvalues['connected_contactperson_id']) {
			$connected_contactperson = EntityContactperson::findById($this->initvalues['connected_contactperson_id']);

			if ($connected_contactperson) {
				if ($connected_contactperson->connected_company_id) {
					$company_data = EntityCompany::findById($connected_contactperson->connected_company_id)?->data();
					$this->initvalues['connected_contactperson'] = $connected_contactperson->name . ' (' . $company_data['shortname'] . ')';
				} else {
					$this->initvalues['connected_contactperson'] = $connected_contactperson->name;
				}
			}
		}
		//var_dump($this->initvalues);
	}

	public function makeInputs(): void
	{
		$title = new FormInputText('title', $this);
		$title->label = t('ticket.field.subject.label');
		$title->explanation = t('ticket.form.subject_explanation');
		$v = new FormValidatorStringlength(t('form.validation.max_length', ['max' => 255]));
		$v->min = 0;
		$v->max = 255;
		$title->addValidator($v);
		$title->addValidator(new FormValidatorNotEmpty(t('form.validation.required')));

		$assigned_user_id = new FormInputText('assigned_user_id', $this);
		$assigned_user_id->label = t('ticket.field.assignee_id.label');

		$assigned_user = new FormInputText('assigned_user', $this);
		$assigned_user->save = false;
		$assigned_user->label = t('ticket.field.assignee.label');
		$assigned_user->explanation = t('ticket.form.assignee_explanation');
		$assigned_user->autocomplete_url = Url::getAjaxUrl('users.ajax_userListAutocomplete');
		$assigned_user->connected_autocomplete_fieldname = 'assigned_user_id';

		$project_id = new FormInputText('project_id', $this);
		$project_id->label = t('ticket.field.project_id.label');

		$project_name = new FormInputText('project_name', $this);
		$project_name->save = false;
		$project_name->label = t('ticket.field.project.label');
		$project_name->explanation = t('ticket.form.project_explanation');
		$v = new FormValidatorStringlength(t('form.validation.max_length', ['max' => 255]));
		$v->min = 0;
		$v->max = 255;
		$project_name->addValidator($v);
		$project_name->autocomplete_url = Url::getAjaxUrl('projects.ajax_projectListAutocomplete');
		$project_name->connected_autocomplete_fieldname = 'project_id';

		$connected_contactperson_id = new FormInputText('connected_contactperson_id', $this);
		$connected_contactperson_id->label = t('ticket.field.contactperson_id.label');

		$connected_contactperson = new FormInputText('connected_contactperson', $this);
		$connected_contactperson->save = false;
		$connected_contactperson->label = t('ticket.field.contactperson.label');
		$connected_contactperson->explanation = t('ticket.form.contactperson_explanation');
		$connected_contactperson->autocomplete_url = Url::getAjaxUrl('contactPersons.ajax_contactPersonListAutocomplete');
		$connected_contactperson->connected_autocomplete_fieldname = 'connected_contactperson_id';

		$tags = new FormInputText('tags', $this);
		$tags->save = false;
		$tags->label = t('ticket.field.tags.label');
		$tags->explanation = t('ticket.form.tags_explanation');

		$ticket_state = new FormInputSelect('ticket_state', $this);
		$ticket_state->label = t('ticket.field.state.label');
		$ticket_state->required = false;
		$ticket_state->values = EntityTicket_stat::getListForSelect();
		$ticket_state->explanation = t('ticket.form.state_explanation');
		$ticket_state->addValidator(new FormValidatorSelected(t('ticket.form.state_explanation')));

		$start_data = new FormInputDate('start_date', $this);
		$start_data->label = t('ticket.field.start_date.label');
		$start_data->explanation = t('ticket.form.start_date_explanation');

		$end_date = new FormInputDate('end_date', $this);
		$end_date->label = t('ticket.field.end_date.label');
		$end_date->explanation = t('ticket.form.end_date_explanation');

		$ticket_type = new FormInputSelect('ticket_type', $this);
		$ticket_type->label = t('ticket.field.type.label');
		$ticket_type->required = false;
		$ticket_type->explanation = t('ticket.form.type_explanation');
		$ticket_type->values = EntityTicket_typ::getListForSelect();

		$ticket_type = new FormInputSelect('ticket_priority', $this);
		$ticket_type->label = t('ticket.field.priority.label');
		$ticket_type->required = false;
		$ticket_type->explanation = t('ticket.form.priority_explanation');
		$ticket_type->values = EntityTicket_priority::getListForSelect();

		$__description = new FormInputTextarea('__description', $this);
		$__description->editor = FormInputTextarea::EDITOR_AUTO;
		$__description->label = t('ticket.field.description.label');
		$__description->toolbar = FormInputTextarea::TOOLBAR_FULL;

		if ($this->getMode() == self::_MODE_UPDATE) {
			$__comment = new FormInputTextarea('#comment', $this);
			$__comment->editor = FormInputTextarea::EDITOR_AUTO;
			$__comment->label = t('ticket.form.comment_label');
			$__comment->toolbar = FormInputTextarea::TOOLBAR_TEXTONLY;
		}
	}
}
