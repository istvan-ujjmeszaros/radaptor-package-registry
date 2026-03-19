<?php

/**
 * Tiny teaching widget.
 *
 * Widgets participate in the CMS rendering pipeline, so they are a better fit
 * than raw events when the output should be placeable on a managed page.
 *
 * Some IDEs grey this class out as "unused". That is expected here:
 * widgets are discovered dynamically and can be attached to pages from the CMS.
 */
class WidgetHelloWorldPluginDemo extends AbstractWidget
{
	public const string ID = 'hello_world_plugin_demo';

	/**
	 * Return the human label shown in widget pickers.
	 *
	 * For now this stays hardcoded, because plugin i18n seed import is not yet
	 * wired into the install lifecycle.
	 */
	public static function getName(): string
	{
		return 'Hello World Plugin Demo';
	}

	/**
	 * Return the short help text shown next to the widget name.
	 */
	public static function getDescription(): string
	{
		return 'Example widget shipped by the Hello World teaching plugin.';
	}

	/**
	 * Keep the example visible for administrators, not only developers.
	 */
	public static function getListVisibility(): bool
	{
		return Roles::hasRole(RoleList::ROLE_SYSTEM_ADMINISTRATOR);
	}

	/**
	 * Suggest a public learning area instead of the admin section.
	 */
	public static function getDefaultPathForCreation(): array
	{
		return [
			'path' => '/learn/',
			'resource_name' => 'hello-world-plugin.html',
			'layout' => 'public_default',
		];
	}

	/**
	 * Build the widget tree that the CMS renderer will place onto a page.
	 */
	protected function buildAuthorizedTree(iTreeBuildContext $tree_build_context, WidgetConnection $connection, array $build_context = []): array
	{
		$props = [
			'title' => 'Hello World Plugin Widget',
			'lead' => 'This widget came from an installed plugin package.',
			'points' => [
				'Widgets are placed onto CMS pages instead of being hit directly as endpoints.',
				'The widget template can reuse smaller template fragments when that keeps markup DRY.',
				'The widget connection id is available for debugging and advanced integrations.',
			],
			'footer_note' => 'This widget reuses the same view template as the direct event demo.',
			'connectionId' => $connection->connection_id,
		];

		return $this->createComponentTree('helloWorldPluginWidget', $props);
	}

	/**
	 * Allow rendering everywhere for this example widget.
	 */
	public function canAccess(iTreeBuildContext $tree_build_context, WidgetConnection $connection): bool
	{
		return true;
	}
}
