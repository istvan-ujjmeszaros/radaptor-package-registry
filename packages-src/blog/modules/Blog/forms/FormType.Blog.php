<?php

class FormTypeBlog extends FormCustomValidatorBlog
{
	public const string ID = 'blog';

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
			'path' => '/admin/components/blog/edit/',
			'resource_name' => 'index.html',
			'layout' => 'admin_default',
		];
	}

	public function hasRole(): bool
	{
		return Roles::hasRole(RoleList::ROLE_BLOG_ADMIN);
	}

	public function commit(): void
	{
		switch ($this->getMode()) {
			case self::_MODE_CREATE:

				$content_id = EntityBlog::createFromArray($this->savedata)->pkey();

				if ($content_id) {
					SystemMessages::addSystemMessage(t('blog.saved'));
				} else {
					SystemMessages::addSystemMessage(t('blog.error_save'));
				}

				break;

			case self::_MODE_UPDATE:

				EntityBlog::updateById($this->getItemId(), $this->savedata);
				SystemMessages::addSystemMessage(t('blog.updated'));

				break;
		}
	}

	public function setMetadata(): void
	{
		$this->_meta->template = 'blog';

		if ($this->_mode == self::_MODE_CREATE) {
			$this->_meta->title = t('blog.form.title_create');
		} else {
			$this->_meta->title = t('blog.form.title_edit');
			$this->_meta->sub_title = EntityBlog::getTitle($this->getItemId());
		}
	}

	public function setInitValues(): void
	{
		$this->initvalues = EntityBlog::findById($this->getItemId())?->dto();
	}

	public function makeInputs(): void
	{
		$title = new FormInputText('title', $this);
		$title->label = t('blog.field.title.label');
		$title->explanation = t('blog.field.title.explanation');
		$title->addValidator(new FormValidatorNotEmpty(t('form.validation.required')));

		$slug = new FormInputText('slug', $this);
		$slug->label = t('blog.field.slug.label');
		$slug->explanation = t('blog.field.slug.explanation');
		$slug->addValidator(new FormValidatorNotEmpty(t('form.validation.required')));
		$slug->addValidator(new FormValidatorSlug(t('blog.validation.slug_format')));

		$date = new FormInputDate('date', $this);
		$date->label = t('blog.field.date.label');
		$date->explanation = t('blog.field.date.explanation');
		$date->initvalue = date('Y-m-d'); // Default to today

		$description = new FormInputTextarea('__description', $this);
		$description->editor = FormInputTextarea::EDITOR_AUTO;
		$description->toolbar = FormInputTextarea::TOOLBAR_TEXTONLY;
		$description->label = t('blog.field.description.label');
		$description->explanation = t('blog.field.description.explanation');

		$content = new FormInputTextarea('__content', $this);
		$content->editor = FormInputTextarea::EDITOR_AUTO;
		$content->toolbar = FormInputTextarea::TOOLBAR_FULL;
		$content->label = t('blog.field.body.label');
		$content->explanation = t('blog.field.body.explanation');
	}
}
