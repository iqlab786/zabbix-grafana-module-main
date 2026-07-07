<?php
namespace Modules\GrafanaDashboards\Actions;

use CController;
use CControllerResponseData;
use CControllerResponseRedirect;

class ActionGrafanaEdit extends CController {

    protected function init(): void {
        $this->disableCsrfValidation();
    }

    protected function checkInput(): bool {
        return true;
    }

    protected function checkPermissions(): bool {
        return ($this->getUserType() >= USER_TYPE_ZABBIX_ADMIN);
    }

    protected function doAction(): void {
        $data_file = __DIR__ . '/../data/dashboards.json';
        $dashboards = file_exists($data_file)
            ? (json_decode(file_get_contents($data_file), true) ?: [])
            : [];

        if (array_key_exists('save', $_POST)) {
            $name          = trim($_POST['name'] ?? '');
            $grafana_url   = rtrim(trim($_POST['grafana_url'] ?? ''), '/');
            $dashboard_uid = trim($_POST['dashboard_uid'] ?? '');
            $description   = trim($_POST['description'] ?? '');
            $id            = $_POST['id'] ?? '';

            if ($name && $grafana_url && $dashboard_uid) {
                $entry = [
                    'id'            => $id ?: uniqid('gf_', true),
                    'name'          => $name,
                    'grafana_url'   => $grafana_url,
                    'dashboard_uid' => $dashboard_uid,
                    'description'   => $description,
                    'updated_at'    => date('c')
                ];

                if ($id) {
                    foreach ($dashboards as &$d) {
                        if ($d['id'] === $id) {
                            $entry['created_at'] = $d['created_at'] ?? date('c');
                            $d = $entry;
                            break;
                        }
                    }
                    unset($d);
                } else {
                    $entry['created_at'] = date('c');
                    $dashboards[] = $entry;
                }

                file_put_contents($data_file, json_encode($dashboards, JSON_PRETTY_PRINT));

                $this->setResponse(new CControllerResponseRedirect(
                    (new \CUrl('zabbix.php'))->setArgument('action', 'grafana.list')
                ));
                return;
            }
        }

        $dashboard = ['id' => '', 'name' => '', 'grafana_url' => '', 'dashboard_uid' => '', 'description' => ''];
        $edit_id   = $_GET['id'] ?? '';

        if ($edit_id) {
            foreach ($dashboards as $d) {
                if ($d['id'] === $edit_id) {
                    $dashboard = $d;
                    break;
                }
            }
        }

        $this->setResponse(new CControllerResponseData([
            'dashboard' => $dashboard,
            'user'      => ['type' => $this->getUserType()]
        ]));
    }
}
