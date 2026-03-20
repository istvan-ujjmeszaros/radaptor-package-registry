<?php

class WidgetBlog extends AbstractWidget
{
	public const string ID = 'blog';

	/**
	 * @return array<string, string>
	 */
	public static function buildEntryListStrings(): array
	{
		return [
			'common.read_more' => t('common.read_more'),
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
		return Roles::hasRole(RoleList::ROLE_BLOG_ADMIN);
	}

	public static function getDefaultPathForCreation(): array
	{
		return [
			'path' => '/blog/',
			'resource_name' => 'index.html',
			'layout' => 'public_default',
		];
	}

	public static function isCatcher(): bool
	{
		return true;
	}

	protected function buildAuthorizedTree(iTreeBuildContext $tree_build_context, WidgetConnection $connection, array $build_context = []): array
	{
		$extra_params = Url::getExtraParams($tree_build_context);

		if (isset($extra_params['standalone'][0])) {
			$id = EntityBlog::getIdBySlug($extra_params['standalone'][0]);
		} else {
			$id = 0;
		}

		if ($id == 0) {
			return $this->_buildBlogEntryListTree($tree_build_context, $connection);
		} else {
			return $this->_buildBlogEntryTree($tree_build_context, $connection, $id);
		}
	}

	private function _buildBlogEntryListTree(iTreeBuildContext $tree_build_context, WidgetConnection $connection): array
	{
		$data = EntityBlog::getList(true);

		$blog_url = Url::getSeoUrl(ResourceTypeWebpage::findWebpageIdWithWidget(WidgetList::BLOG));

		foreach ($data as &$entry) {
			$entry['url'] = $blog_url . $entry['slug'] . "/";
		}

		return $this->createComponentTree('BlogEntryList', [
			'data' => $data,
		], strings: self::buildEntryListStrings());
	}

	private function _buildBlogEntryTree(iTreeBuildContext $tree_build_context, WidgetConnection $connection, $id): array
	{
		$data = EntityBlog::findById($id)?->dto();

		if (is_null($data)) {
			return $this->buildStatusTree([
				'severity' => 'warning',
				'message' => t('blog.not_found'),
			]);
		}

		$tree_build_context->addToTitle($data['title']);

		return $this->createComponentTree('BlogEntry', [
			'data' => $data,
		]);
	}

	public function getEditableCommands(WidgetConnection $connection): array
	{
		$return = [];

		if (!Roles::hasRole(RoleList::ROLE_BLOG_ADMIN)) {
			return $return;
		}

		// Check if viewing specific entry by parsing URL directly
		// The blog widget is a catcher, so URLs like /blog/my-slug/ mean we're viewing an entry
		$blog_id = $this->_getBlogIdFromCurrentUrl();

		if ($blog_id > 0) {
			// EDIT first when viewing entry
			$edit = new WidgetEditCommand();
			$edit->title = t('cms.edit');
			$edit->icon = IconNames::EDIT;
			$edit->url = Form::getSeoUrl(
				FormList::BLOG,
				$blog_id,
				null,
				['connection_id' => $connection->connection_id]
			);
			$return[] = $edit;
		}

		// ADD always available
		$create = new WidgetEditCommand();
		$create->title = t('blog.list.new');
		$create->icon = IconNames::ADD;
		$create->url = Form::getSeoUrl(
			FormList::BLOG,
			null,
			null,
			['connection_id' => $connection->connection_id]
		);
		$return[] = $create;

		return $return;
	}

	/**
	 * Get blog ID from current URL if viewing a specific blog entry.
	 * Parses URLs like /blog/my-slug/ to extract the slug and find the blog ID.
	 */
	private function _getBlogIdFromCurrentUrl(): int
	{
		$request_uri = urldecode($_SERVER['REQUEST_URI'] ?? '');
		$blog_path = '/blog/';

		// Check if we're on a blog page
		if (mb_strpos($request_uri, $blog_path) !== 0) {
			return 0;
		}

		// Extract what comes after /blog/
		$extra = mb_substr($request_uri, mb_strlen($blog_path));

		// Remove trailing slash and split
		$extra = rtrim($extra, '/');

		if (empty($extra) || $extra === 'index.html') {
			return 0;
		}

		// The first segment after /blog/ should be the slug
		$segments = explode('/', $extra);
		$slug = $segments[0];

		if (empty($slug)) {
			return 0;
		}

		return EntityBlog::getIdBySlug($slug);
	}
	public function canAccess(iTreeBuildContext $tree_build_context, WidgetConnection $connection): bool
	{
		return true;
	}
}
