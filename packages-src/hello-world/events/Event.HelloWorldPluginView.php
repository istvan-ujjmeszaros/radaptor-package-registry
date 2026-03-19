<?php

/**
 * Slightly more realistic event example.
 *
 * Instead of echoing directly, this event prepares data and delegates the
 * HTML output to a template. That keeps the event focused on orchestration.
 */
class EventHelloWorldPluginView extends AbstractEvent
{
	public function authorize(PolicyContext $policyContext): PolicyDecision
	{
		return PolicyDecision::allow();
	}

	public function run(): void
	{
		$template = new Template('helloWorldPluginView');

		$template->props['headline'] = 'Hello from the plugin view event';
		$template->props['points'] = [
			'The event decides what data the template receives.',
			'The template decides how that data becomes HTML.',
			'This split is easier to extend than raw echo output.',
		];

		$template->render();
	}
}
