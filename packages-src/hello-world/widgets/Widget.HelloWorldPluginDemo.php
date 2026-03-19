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
	 */
	public static function getName(): string
	{
		return t('widget.' . self::ID . '.name');
	}

	/**
	 * Return the short help text shown next to the widget name.
	 */
	public static function getDescription(): string
	{
		return t('widget.' . self::ID . '.description');
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
		$strings = [
			'hello_world.demo.headline' => t('hello_world.demo.headline'),
			'hello_world.demo.lead' => t('hello_world.demo.lead'),
			'hello_world.demo.point_one' => t('hello_world.demo.point_one'),
			'hello_world.demo.point_two' => t('hello_world.demo.point_two'),
			'hello_world.demo.point_three' => t('hello_world.demo.point_three'),
			'hello_world.demo.footer_note' => t('hello_world.demo.footer_note'),
			'hello_world.widget.connection_id_label' => t('hello_world.widget.connection_id_label'),
		];
		$props = [
			'strings' => $strings,
			'points' => [
				$strings['hello_world.demo.point_one'],
				$strings['hello_world.demo.point_two'],
				$strings['hello_world.demo.point_three'],
			],
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
