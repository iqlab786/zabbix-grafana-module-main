<?php declare(strict_types = 1);
/**
 * @var CView $this
 * @var array $data
 */

$is_admin   = ($data['user']['type'] >= USER_TYPE_ZABBIX_ADMIN);
$dashboards = $data['dashboards'];

// Add button
$add_btn = $is_admin
	? (new CTag('a', true, '+ ' . _('Add Dashboard')))
		->setAttribute('href', 'zabbix.php?action=grafana.edit')
		->setAttribute('style', 'display:inline-block;padding:6px 14px;background:#1f83c6;color:#fff;border-radius:3px;text-decoration:none;font-size:13px;margin-bottom:16px;')
	: null;

// Build table
if (empty($dashboards)) {
	$table = (new CDiv(_('No Grafana dashboards added yet. Click "Add Dashboard" to get started.')))
		->setAttribute('style', 'padding:20px;color:#888;font-size:14px;');
} else {
	$table = (new CTableInfo())
		->setHeader([_('Name'), _('Grafana URL'), _('Dashboard UID'), _('Description'), _('Actions')]);

	foreach ($dashboards as $d) {
		$view_url   = 'zabbix.php?action=grafana.view&id=' . urlencode($d['id']);
		$edit_url   = 'zabbix.php?action=grafana.edit&id=' . urlencode($d['id']);
		$delete_url = 'zabbix.php?action=grafana.delete&id=' . urlencode($d['id']);

		$actions = [(new CTag('a', true, _('View')))->setAttribute('href', $view_url)];
		if ($is_admin) {
			$actions[] = new CTag('span', true, ' | ');
			$actions[] = (new CTag('a', true, _('Edit')))->setAttribute('href', $edit_url);
			$actions[] = new CTag('span', true, ' | ');
			$actions[] = (new CTag('a', true, _('Delete')))
				->setAttribute('href', $delete_url)
				->setAttribute('onclick', "return confirm('Delete this dashboard?');")
				->setAttribute('style', 'color:#e74c3c;');
		}

		$table->addRow(new CRow([
			(new CTag('a', true, htmlspecialchars($d['name'])))->setAttribute('href', $view_url)->setAttribute('style', 'font-weight:bold;color:#1f83c6;'),
			htmlspecialchars($d['grafana_url']),
			htmlspecialchars($d['dashboard_uid']),
			htmlspecialchars($d['description'] ?? ''),
			(new CDiv($actions))
		]));
	}
}

(new CHtmlPage())
	->setTitle(_('Grafana Dashboards'))
	->addItem($add_btn)
	->addItem($table)
	->show();
