<?php

/**
 * Slightly more realistic event example.
 *
 * Instead of echoing directly, this event prepares data and delegates the
 * HTML output to a template. That keeps the event focused on orchestration.
 */
class EventHelloWorldPluginView extends AbstractEvent
{
	/**
	 * Keep the demo endpoint public so it is easy to try.
	 */
	public function authorize(PolicyContext $policyContext): PolicyDecision
	{
		return PolicyDecision::allow();
	}

	/**
	 * Prepare props and hand the HTML work over to a template.
	 */
	public function run(): void
	{
		$template = new Template('helloWorldPluginView');
		$strings = [
			'hello_world.demo.headline' => t('hello_world.demo.headline'),
			'hello_world.demo.lead' => t('hello_world.demo.lead'),
			'hello_world.demo.point_one' => t('hello_world.demo.point_one'),
			'hello_world.demo.point_two' => t('hello_world.demo.point_two'),
			'hello_world.demo.point_three' => t('hello_world.demo.point_three'),
			'hello_world.demo.footer_note' => t('hello_world.demo.footer_note'),
		];
		$props = [
			'strings' => $strings,
			'points' => [
				$strings['hello_world.demo.point_one'],
				$strings['hello_world.demo.point_two'],
				$strings['hello_world.demo.point_three'],
			],
		];

		$template->props = $props;
		$template->render();
	}
}
