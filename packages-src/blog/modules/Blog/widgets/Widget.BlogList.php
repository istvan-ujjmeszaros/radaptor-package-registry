<?php

class WidgetBlogList extends AbstractWidget
{
	public const string ID = 'blog_list';

	/**
	 * @return array<string, string>
	 */
	public static function buildStrings(): array
	{
		return [
			'blog.list.title' => t('blog.list.title'),
			'blog.list.new' => t('blog.list.new'),
			'common.id' => t('common.id'),
			'blog.field.date.label' => t('blog.field.date.label'),
			'blog.field.title.label' => t('blog.field.title.label'),
			'blog.field.slug.label' => t('blog.field.slug.label'),
			'common.actions' => t('common.actions'),
			'common.operations' => t('common.operations'),
			'common.edit' => t('common.edit'),
			'datatable.processing' => t('datatable.processing'),
			'datatable.search' => t('datatable.search'),
			'datatable.length_menu' => t('datatable.length_menu'),
			'datatable.info_compact' => t('datatable.info_compact'),
			'datatable.info_empty' => t('datatable.info_empty'),
			'datatable.info_filtered_compact' => t('datatable.info_filtered_compact'),
			'datatable.loading_records' => t('datatable.loading_records'),
			'datatable.zero_records' => t('datatable.zero_records'),
			'datatable.empty_table' => t('datatable.empty_table'),
			'datatable.first' => t('datatable.first'),
			'datatable.previous' => t('datatable.previous'),
			'datatable.next' => t('datatable.next'),
			'datatable.last' => t('datatable.last'),
		];
	}

	public static function getName(): string
	{
		return t('widget.' . self::ID . '.name');
	}

	public static function getDescription(): string
	{
		return t('widget.' . self::ID . '.description');
	}

	public static function getListVisibility(): bool
	{
		return Roles::hasRole(RoleList::ROLE_SYSTEM_DEVELOPER);
	}

	public static function getDefaultPathForCreation(): array
	{
		return [
			'path' => '/admin/components/blog/',
			'resource_name' => 'index.html',
			'layout' => 'admin_default',
		];
	}

	protected function buildAuthorizedTree(iTreeBuildContext $tree_build_context, WidgetConnection $connection, array $build_context = []): array
	{
		return $this->createComponentTree('blogList', [], strings: self::buildStrings());
	}
	public function canAccess(iTreeBuildContext $tree_build_context, WidgetConnection $connection): bool
	{
		return Roles::hasRole(RoleList::ROLE_BLOG_ADMIN);
	}
}
