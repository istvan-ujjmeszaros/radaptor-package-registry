<?php

/**
 * Tiny teaching widget.
 *
 * Widgets participate in the CMS rendering pipeline, so they are a better fit
 * than raw events when the output should be placeable on a managed page.
 */
class WidgetHelloWorldPluginDemo extends AbstractWidget
{
	public const string ID = 'hello_world_plugin_demo';

	public static function getName(): string
	{
		return 'Hello World Plugin Demo';
	}

	public static function getDescription(): string
	{
		return 'Example widget shipped by the Hello World teaching plugin.';
	}

	public static function getListVisibility(): bool
	{
		return Roles::hasRole(RoleList::ROLE_SYSTEM_DEVELOPER);
	}

	public static function getDefaultPathForCreation(): array
	{
		return [
			'path' => '/admin/demo/',
			'resource_name' => 'hello-world-plugin.html',
			'layout' => 'admin_default',
		];
	}

	protected function buildAuthorizedTree(iTreeBuildContext $tree_build_context, WidgetConnection $connection, array $build_context = []): array
	{
		return $this->createComponentTree('helloWorldPluginWidget', [
			'title' => 'Hello World Plugin Widget',
			'body' => 'This widget came from an installed plugin package.',
			'connectionId' => $connection->connection_id,
		]);
	}

	public function canAccess(iTreeBuildContext $tree_build_context, WidgetConnection $connection): bool
	{
		return true;
	}
}
