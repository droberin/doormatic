import requests
from yaml import safe_load
from os import environ
from os.path import isfile
from sys import exit
from ping3 import ping

# Reconnect WAN Script.

if ping('8.8.8.8', 30):
    print("WAN seems to be properly connected")
    exit(0)
else:
    print("WAN may be failing... Let's try reconnecting external interface")

configuration_file_path = environ.get('CONFIGURATION_FILE', 'configuration.yaml')

if not isfile(configuration_file_path):
    print('[CRITICAL]: Configuration file not found in {}'.format(configuration_file_path))
    exit(1)

with open(configuration_file_path, 'rb') as auth_data_file_handler:
    auth_data = safe_load(auth_data_file_handler)

configuration = {
    "server": auth_data['server'],
    "schema": "http",
    "stok": None,
    "wan": '0.2582667919750792',
    'username': auth_data['username'],
    'password': auth_data['password'],
}

reconnecter = requests.Session()
reconnecter.post(
    f'{configuration["schema"]}://{configuration["server"]}/cgi-bin/luci',
    data={
        'username': f'{configuration["username"]}',
        'password': f'{configuration["password"]}'
    }
)

stok_rev = {}

try:
    stok_rev = reconnecter.cookies._cookies[f'{configuration["server"]}']['/cgi-bin/luci/']['sysauth']._rest
except KeyError as e:
    print(f'Key error: {e}')
    pass

if 'stok' in stok_rev:
    configuration['stok'] = stok_rev['stok']

pass
reconnect = reconnecter.get(
    f'{configuration["schema"]}://{configuration["server"]}/cgi-bin/luci/;stok={configuration["stok"]}'
    f'/admin/network/iface_reconnect/wan?_={configuration["wan"]}'
)
if reconnect.status_code == 200:
    print("Interface reconnection properly requested...")

print("Trying to logout before leaving :)")
reconnecter.get(
    f'{configuration["schema"]}://{configuration["server"]}/cgi-bin/luci/;stok={configuration["stok"]}/admin/logout')
