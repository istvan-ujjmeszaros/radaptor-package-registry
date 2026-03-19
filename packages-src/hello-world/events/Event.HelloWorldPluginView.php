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
		$props = [
			'headline' => 'Hello from the plugin view event',
			'lead' => 'This response is rendered by a plugin template.',
			'points' => [
			'The event decides what data the template receives.',
			'The template decides how that data becomes HTML.',
			'This split is easier to extend than raw echo output.',
			],
			'footer_note' => 'Later this same template can also be embedded by another template.',
		];

		$template->props = $props;
		$template->render();
	}
}
