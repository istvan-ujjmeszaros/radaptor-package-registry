<?php

abstract class FormCustomValidatorBlog extends AbstractForm
{
	protected function _validateData(): void
	{
		parent::_validateData();

		$this->validateTitle();
		$this->validateSlug();
	}

	private function validateTitle(): void
	{
		$id = EntityBlog::getIdByTitle($this->getInput('title')->getValue());

		if ($id == EntityBlog::ERROR_NOT_FOUND) {
			return;
		}

		if ($id == EntityBlog::ERROR_MULTIPLE) {
			$this->getInput('title')->addError('A blog post with this title already exists');

			return;
		}

		if ($id > 0 && $id !== $this->getItemId()) {
			$this->getInput('title')->addError('A blog post with this title already exists');
		}
	}

	private function validateSlug(): void
	{
		$id = EntityBlog::getIdBySlug($this->getInput('slug')->getValue());

		if ($id == EntityBlog::ERROR_NOT_FOUND) {
			return;
		}

		if ($id == EntityBlog::ERROR_MULTIPLE) {
			$this->getInput('slug')
				 ->addError('A blog post with this slug already exists');

			return;
		}

		if ($id > 0 && $id !== $this->getItemId()) {
			$this->getInput('slug')
				 ->addError('A blog post with this slug already exists');
		}
	}
}
