import sys, os, subprocess

sys.path.append('%s/bin/common' % os.getcwd())

from servers import SERVERS

for server in SERVERS:
    remote_script = "cd %s && git pull" % os.environ["SP_DEPLOY_PATH"]
    subprocess.run(["ssh", "-tt", "-o", "StrictHostKeyChecking=no", "-i", 
                    os.environ["SP_RUNNER_SSH_KEY_PATH"], server["connection"], remote_script])