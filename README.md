# Zabbix Grafana Module

A Zabbix frontend module to embed Grafana dashboards directly inside the Zabbix UI.

## Features

- 📊 Embed any Grafana dashboard as a full-screen iframe inside Zabbix
- ➕ Add, edit, delete dashboards from within Zabbix
- 🔐 Admin-only add/edit/delete controls
- 🔗 Quick link to open dashboard directly in Grafana
- 💾 Dashboards stored in a local JSON file (no DB changes)
- ✅ Compatible with Zabbix 7.0, 7.4, 8.0

## Installation

```bash
# On your Zabbix server (Docker)
cd /tmp
wget https://github.com/vrishabrayu/zabbix-grafana-module/archive/refs/heads/main.zip -O grafana.zip
unzip -o grafana.zip
docker cp zabbix-grafana-module-main zabbix-web:/usr/share/zabbix/modules/zabbix_grafana
docker exec -u root zabbix-web chown -R nginx:nginx /usr/share/zabbix/modules/zabbix_grafana
```

## Enable in Zabbix

1. Go to **Administration → General → Modules**
2. Click **Scan Directory**
3. Enable **Grafana Dashboards**
4. A new **Grafana** menu will appear under **Monitoring**

## Usage

1. Click **Monitoring → Grafana**
2. Click **+ Add Dashboard**
3. Enter:
   - **Name** — Display name (e.g. "ISP Monitoring")
   - **Grafana Base URL** — e.g. `http://192.168.1.100:3000`
   - **Dashboard UID** — Found in Grafana URL: `/d/UID/dashboard-name`
4. Click **Add Dashboard**
5. Click the dashboard name to view it embedded in Zabbix

## Notes

- Make sure Grafana allows embedding (set `allow_embedding = true` in `grafana.ini`)
- If Grafana requires login, use anonymous access or embed tokens
- Dashboards are stored in `data/dashboards.json` inside the module folder

## License

MIT
