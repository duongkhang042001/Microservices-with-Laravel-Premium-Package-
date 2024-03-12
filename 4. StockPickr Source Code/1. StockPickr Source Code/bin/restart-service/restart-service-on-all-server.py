import sys, os, subprocess
import argparse
sys.path.append('%s/bin/common' % os.getcwd())

from servers import SERVERS
from functions import write_env_file, copy_env_file_to_remote, remove_env_file

parser = argparse.ArgumentParser(description='Deploy services to servers')
parser.add_argument('service', metavar='SERVICE', type=str,
                    help='Which service to restart in docker-compose')

parser.add_argument('image', metavar='IMAGE', type=str,
                    help='Which docker image to use')

args = parser.parse_args()
service = args.service
image = args.image

current_env = os.environ.copy()
for server in SERVERS:
    write_env_file(current_env)  
    copy_env_file_to_remote(server["connection"], current_env)

    remote_script = "cd %s && source deploy.env && python3 %s/bin/restart-service/restart-service.py %s %s %s %s %s %s" % (
        current_env["SP_DEPLOY_PATH"], 
        current_env["SP_DEPLOY_PATH"], 
        service,
        image,
        server["compose_target"],
        current_env["CI_REGISTRY"], 
        current_env["CI_REGISTRY_USER"], 
        current_env["CI_REGISTRY_PASSWORD"]
    )

    subprocess.run(["ssh", "-tt", "-o", "StrictHostKeyChecking=no", "-i", 
                    current_env["SP_RUNNER_SSH_KEY_PATH"], server["connection"], remote_script])

    remove_env_file()