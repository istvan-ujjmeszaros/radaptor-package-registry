<?php

class LayoutComponentTimeTrackerControl extends AbstractLayoutComponent
{
	public const string ID = 'time_tracker_control';

	/**
	 * @return array<string, string>
	 */
	public static function buildStrings(): array
	{
		return [
			'timetracker.control.title' => t('timetracker.control.title'),
			'timetracker.control.stop' => t('timetracker.control.stop'),
			'timetracker.control.ticket_placeholder' => t('timetracker.control.ticket_placeholder'),
			'timetracker.control.cancel_title' => t('timetracker.control.cancel_title'),
			'timetracker.control.cancel_confirm' => t('timetracker.control.cancel_confirm'),
			'timetracker.field.ticket.label' => t('timetracker.field.ticket.label'),
			'timetracker.list.start_time' => t('timetracker.list.start_time'),
			'timetracker.list.end_time' => t('timetracker.list.end_time'),
			'timetracker.field.date.label' => t('timetracker.field.date.label'),
			'timetracker.control.start' => t('timetracker.control.start'),
		];
	}

	public function buildTree(): array
	{
		$timetracker_data = UserConfig::getConfig('TimeTracker');

		if ($timetracker_data == '') {
			return $this->createComponentTree('TimeTrackerControl.stopped', [], strings: self::buildStrings());
		} else {
			return $this->createComponentTree('TimeTrackerControl.running', [
				'data' => unserialize($timetracker_data),
			], strings: self::buildStrings());
		}
	}

	public static function getLayoutComponentName(): string
	{
		return t('layout.' . self::ID . '.name');
	}

	public static function getLayoutComponentDescription(): string
	{
		return t('layout.' . self::ID . '.description');
	}
}
