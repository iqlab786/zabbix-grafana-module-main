<?php
namespace Modules\GrafanaDashboards;

use Zabbix\Core\CModule;
use APP;
use CMenuItem;
use CMenu;

class Module extends CModule {

	public function init(): void {
		// Ensure data file exists
		$data_file = __DIR__ . '/data/dashboards.json';
		if (!file_exists($data_file)) {
			if (!is_dir(__DIR__ . '/data')) {
				mkdir(__DIR__ . '/data', 0755, true);
			}
			file_put_contents($data_file, json_encode([]));
		}

		// Add Grafana submenu under Monitoring
		APP::Component()->get('menu.main')
			->findOrAdd(_('Monitoring'))
				->getSubmenu()
					->add(
						(new CMenuItem(_('Grafana')))
							->setAction('grafana.list')
					);
	}
}
