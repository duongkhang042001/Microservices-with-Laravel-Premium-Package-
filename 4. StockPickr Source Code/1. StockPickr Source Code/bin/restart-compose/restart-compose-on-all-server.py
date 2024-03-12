import sys, os, subprocess
import argparse
sys.path.append('%s/bin/common' % os.getcwd())

parser = argparse.ArgumentParser(description="Restart docker-compose on all servers. Unless a specific SERVER_NAME is given")

parser.add_argument("server_name", metavar="SERVER_NAME", type=str, nargs="?", default=None,
                    help="Which server to deploy. SERVER_NAME must be exist in servers.py. If given ONLY this server will be restarted")

args = parser.parse_args()
server_name = args.server_name

from servers import SERVERS
from functions import write_env_file, copy_env_file_to_remote, remove_env_file

if server_name is not None:
    servers_to_deploy = list(filter(lambda s: s["name"] == server_name, SERVERS))
else:
    servers_to_deploy = SERVERS

if len(servers_to_deploy) == 0:
    print("No servers to deploy...")
    quit()

current_env = os.environ.copy()
for server in servers_to_deploy:
    write_env_file(current_env)  
    copy_env_file_to_remote(server["connection"], current_env)

    remote_script = "cd %s && source deploy.env && python3 %s/bin/restart-compose/restart-compose.py %s %s %s %s" % (
        current_env["SP_DEPLOY_PATH"], 
        current_env["SP_DEPLOY_PATH"], 
        server["compose_target"],
        current_env["CI_REGISTRY"], 
        current_env["CI_REGISTRY_USER"], 
        current_env["CI_REGISTRY_PASSWORD"]
    )

    subprocess.run(["ssh", "-tt", "-o", "StrictHostKeyChecking=no", "-i", 
                    current_env["SP_RUNNER_SSH_KEY_PATH"], server["connection"], remote_script])

    remove_env_file()