<?php

/**
 * Libraries for Tracker theme.
 *
 * Modernized: jQuery 3.7, DataTables 2.2, jsTree 3.3, Stimulus controllers (shared from new theme).
 */
class LibrariesTracker extends LibrariesCommon
{
	/**
	 * jQuery 3.7.1 - modern version from CDN.
	 * The ^ prefix loads in <head> so inline scripts can use jQuery.
	 */
	public const string JQUERY = '
		js:^https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js,
	';

	/**
	 * Override QUERY to drop jQuery Migrate 1.4.1 and BBQ (both incompatible with jQuery 3).
	 */
	public const string QUERY = '
		JQUERY,
	';

	/**
	 * jQuery UI 1.14.1 - compatible with jQuery 3.7.x.
	 */
	public const string JQUERY_UI_10 = '
		JQUERY,
		css:https://cdn.jsdelivr.net/npm/jquery-ui@1.14.1/dist/themes/base/jquery-ui.min.css,
		js:^https://cdn.jsdelivr.net/npm/jquery-ui@1.14.1/dist/jquery-ui.min.js,
	';

	/**
	 * DataTables 2.2 from CDN - default theme CSS (not Bootstrap 5).
	 */
	public const string DATATABLES = '
		JQUERY,
		css:https://cdn.datatables.net/2.2.0/css/dataTables.dataTables.min.css,
		js:https://cdn.datatables.net/2.2.0/js/dataTables.min.js,
	';

	/**
	 * DataTables with filter - DT2 has built-in filtering, no extra plugin.
	 */
	public const string DATATABLES_FILTER = '
		DATATABLES,
	';

	/**
	 * DataTables custom API - DT2 has native ajax.reload().
	 */
	public const string DATATABLES_CUSTOMAPI = '
		DATATABLES,
	';

	/**
	 * jsTree 3.3.17 from CDN + default theme CSS.
	 */
	public const string JSTREE = '
		JQUERY,
		css:https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.17/themes/default/style.min.css,
		js:https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.17/jstree.min.js,
	';

	/**
	 * Stimulus auto-loader (ES module) - shared from new theme.
	 */
	public const string STIMULUS_LOADER = '
		module:/assets/radaptor-portal-admin/js/stimulus-loader.js,
	';

	/**
	 * htmx 2.0.4 from CDN.
	 */
	public const string HTMX = '
		js:https://unpkg.com/htmx.org@2.0.4,
	';

	/**
	 * Override WIDGETTYPE_JSTREE - no longer loading old widgettype.jstree.js.
	 * Replaced by Stimulus controllers.
	 */
	public const string WIDGETTYPE_JSTREE = '';

	/**
	 * Override _ADMIN_DROPDOWN - not used in Tracker theme (avoids 404 on admin_dropdown.css).
	 */
	public const string _ADMIN_DROPDOWN = '';

	/**
	 * Override hoverIntent - not needed, incompatible with jQuery 3.
	 */
	public const string JQUERY_HOVERINTENT = '';

	public const string __WIDEWORKS_TRACK = '
		COMMON,
		JQUERY_UI_10,
		/assets/themes/tracker/admin-site/css-reset/html5-boilerplate.css,
		/assets/themes/tracker/admin-site/admin-site/admin-site.css,
		/assets/themes/tracker/admin-site/buttons.css,
		/assets/themes/tracker/css/timetracker-control.css,
	';

	public const string __PUBLIC_USERLIST = '
		DATATABLES_FILTER,
		/assets/themes/tracker/js/widgettype.publicUserList.js,
	';

	/**
	 * User list widget - uses local widgettype.userList.js (rewritten for DT2).
	 */
	public const string __ADMIN_USERLIST = '
		DATATABLES_FILTER,
		/assets/themes/tracker/admin-site/js/widgettype.userList.js,
	';

	/**
	 * Override __COMMON_ADMIN to use local admin.css with .widgetSelector.
	 */
	public const string __COMMON_ADMIN = '
		/assets/themes/tracker/css/admin.css,
	';

	/**
	 * Override WIDGET_EDIT to use local files with .widgetSelector selector.
	 */
	public const string WIDGET_EDIT = '
		JQUERY,
		JQUERY_UI_10,
		COMBOBOX,
		QTIP,
		/assets/themes/tracker/js/widget-edit.js,
	';

	/**
	 * Resources tree widget - uses Stimulus controllers (shared from new theme).
	 */
	public const string __ADMIN_RESOURCES = '
		JSTREE,
		HTMX,
		STIMULUS_LOADER,
	';

	/**
	 * Roles tree widget - uses Stimulus controllers.
	 */
	public const string __ADMIN_ROLES = '
		JSTREE,
		HTMX,
		STIMULUS_LOADER,
	';

	/**
	 * Usergroups tree widget - uses Stimulus controllers.
	 */
	public const string __ADMIN_USERGROUPS = '
		JSTREE,
		HTMX,
		STIMULUS_LOADER,
	';

	/**
	 * Admin menu tree widget - uses Stimulus controllers.
	 */
	public const string __ADMIN_ADMINMENU = '
		JSTREE,
		HTMX,
		STIMULUS_LOADER,
	';

	/**
	 * Main menu tree widget - uses Stimulus controllers.
	 */
	public const string __ADMIN_MAINMENU = '
		JSTREE,
		HTMX,
		STIMULUS_LOADER,
	';

	/**
	 * Footer menu tree widget - uses Stimulus controllers.
	 */
	public const string __ADMIN_FOOTERMENU = '
		JSTREE,
		HTMX,
		STIMULUS_LOADER,
	';

	/**
	 * Role selector widget - uses Stimulus controllers.
	 */
	public const string __ADMIN_ROLE_SELECTOR = '
		JSTREE,
		STIMULUS_LOADER,
	';

	/**
	 * Usergroup selector widget - uses Stimulus controllers.
	 */
	public const string __ADMIN_USERGROUP_SELECTOR = '
		JSTREE,
		STIMULUS_LOADER,
	';
}
