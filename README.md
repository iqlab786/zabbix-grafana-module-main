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

### 1. Place the module

Unzip this module so the folder ends up here on your Docker host:

```
zabbix-docker/
├── docker-compose.yml
├── modules/
│   └── zabbix-grafana-module-main/     ← this repo goes here
├── config-backups/                     ← create this empty folder (see step 2)
└── ...
```

### 2. Check the docker-compose volume

Make sure your `docker-compose.yml` mounts the `modules` folder into the `zabbix-web` container:

```yaml
    - ./modules:/usr/share/zabbix/modules
```

### 3. Set permissions

The module folder needs to be readable by the web server user inside the container so it shows up in the Zabbix UI:

```bash
docker exec -u root zabbix-web chown -R nginx:nginx /usr/share/zabbix/modules/zabbix-grafana-module-main
docker exec -u root zabbix-web chmod -R 755 /usr/share/zabbix/modules/zabbix-grafana-module-main
```

### 4. Restart and load

After adding the module, restart the `zabbix-web` container:

```bash
docker compose restart zabbix-web
```

Then go to the Zabbix web UI.

### 5. Configure your Grafana container

Find the `grafana` service in your `docker-compose.yml` (this may be in the same compose file as Zabbix, or a separate one) and add the following under its `environment:` section:

```yaml
      - GF_FEATURE_TOGGLES_ENABLE=externalServiceAccounts,mcp,assistants,grafanaAdvisor
      - GF_AUTH_MANAGED_SERVICE_ACCOUNTS_ENABLED=true

      - GF_SECURITY_ALLOW_EMBEDDING=true
      - GF_SECURITY_COOKIE_SAMESITE=disabled

      - GF_SECURITY_COOKIE_SECURE=false
      - GF_SECURITY_CONTENT_SECURITY_POLICY=false
      - GF_AUTH_ANONYMOUS_ENABLED=true
      - GF_AUTH_ANONYMOUS_ORG_ROLE=Viewer
      - GF_SECURITY_X_FRAME_OPTIONS=
```

These allow Grafana dashboards to be embedded in an iframe inside Zabbix and enable anonymous viewer access so dashboards load without a separate login. Then recreate the container so the new environment variables take effect:

```bash
docker compose up -d grafana
```

## Enable in Zabbix

1. Go to **Administration → General → Modules**
2. Click **Scan Directory**
3. Once the module shows up in the list, click it and enable **Grafana Dashboards**
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

- Make sure Grafana allows embedding — either set `allow_embedding = true` in `grafana.ini`, or use the `GF_SECURITY_ALLOW_EMBEDDING=true` environment variable shown in step 5
- If Grafana requires login, use anonymous access (as configured above) or embed tokens
- Dashboards are stored in `data/dashboards.json` inside the module folder

## License

MIT
