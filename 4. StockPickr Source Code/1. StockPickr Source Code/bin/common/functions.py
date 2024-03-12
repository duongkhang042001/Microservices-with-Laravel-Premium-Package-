import os, subprocess

def write_env_file(env: "dict[str, str]"):
    file = open("deploy.env", "w")
    lines = []
    for key in env.keys():
        if key.startswith("SP_"):
            lines.append("export %s=%s\n" % (key, env[key]))

    file.writelines(lines)

def remove_env_file():
    os.remove('./deploy.env')

def copy_env_file_to_remote(ssh_connection: str, env: "dict[str, str]"):
    subprocess.run(["scp", "-C", "-o", "StrictHostKeyChecking=no", "-i", 
                    env["SP_RUNNER_SSH_KEY_PATH"], "./deploy.env", 
                    "%s:%s/deploy.env" % (ssh_connection, env["SP_DEPLOY_PATH"])])