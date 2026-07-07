<?php declare(strict_types = 1);
/**
 * @var CView $this
 * @var array $data
 */

$d        = $data['dashboard'];
$is_admin = ($data['user']['type'] >= USER_TYPE_ZABBIX_ADMIN);

if (!$d) {
	(new CHtmlPage())
		->setTitle(_('Dashboard Not Found'))
		->addItem((new CDiv([
			(new CDiv(_('Dashboard not found.')))->setAttribute('style', 'color:red;margin-bottom:10px;'),
			(new CTag('a', true, '← ' . _('Back to Dashboards')))->setAttribute('href', 'zabbix.php?action=grafana.list')
		])))
		->show();
	return;
}

$iframe_url = rtrim($d['grafana_url'], '/') . '/d/' . $d['dashboard_uid'] . '?orgId=1&kiosk&refresh=30s';

// Nav bar
$nav_items = [
	(new CTag('a', true, '← ' . _('All Dashboards')))->setAttribute('href', 'zabbix.php?action=grafana.list')->setAttribute('style', 'color:#1f83c6;text-decoration:none;')
];
if ($is_admin) {
	$nav_items[] = new CTag('span', true, ' | ');
	$nav_items[] = (new CTag('a', true, '✏ ' . _('Edit')))->setAttribute('href', 'zabbix.php?action=grafana.edit&id=' . urlencode($d['id']))->setAttribute('style', 'color:#1f83c6;text-decoration:none;');
	$nav_items[] = new CTag('span', true, ' | ');
	$nav_items[] = (new CTag('a', true, '↗ ' . _('Open in Grafana')))->setAttribute('href', htmlspecialchars(rtrim($d['grafana_url'], '/') . '/d/' . $d['dashboard_uid']))->setAttribute('target', '_blank')->setAttribute('style', 'color:#1f83c6;text-decoration:none;');
}
$nav_items[] = (new CSpan(htmlspecialchars($d['name'])))->setAttribute('style', 'margin-left:auto;font-weight:bold;color:#333;');

$nav = (new CDiv($nav_items))->setAttribute('style', 'padding:8px 15px;background:#f5f5f5;border-bottom:1px solid #ddd;font-size:13px;display:flex;align-items:center;gap:8px;');

$iframe = (new CTag('iframe', true, ''))
	->setAttribute('src', htmlspecialchars($iframe_url))
	->setAttribute('frameborder', '0')
	->setAttribute('allowfullscreen', 'true')
	->setAttribute('style', 'width:100%;height:calc(100vh - 130px);border:none;display:block;');

$wrapper = (new CDiv([$nav, $iframe]))->setAttribute('style', 'display:flex;flex-direction:column;');

(new CHtmlPage())
	->setTitle(htmlspecialchars($d['name']))
	->addItem($wrapper)
	->show();
