import subprocess
import argparse
import requests

parser = argparse.ArgumentParser(description="Install a new server")
parser.add_argument("ssh_connection", metavar="SSH_CONNECTION", type=str,
                    help="user@ip_address")

parser.add_argument("gitlab_api_access_token", metavar="GITLAB_API_ACCESS_TOKEN", type=str,
                    help="Token to use Gitlab API")

parser.add_argument("gitlab_deploy_token", metavar="GITLAB_DEPLOY_TOKEN", type=str,
                    help="Token to clone the repo with")

parser.add_argument("gitlab_username", metavar="GITLAB_USERNAME", type=str,
                    help="Username to clone the repo with")

parser.add_argument("gitlab_variable", metavar="GITLAB_VARIABLE", type=str,
                    help="This script will create a new group level variable with this name and the value will be ssh_connection")

parser.add_argument("gitlab_group_id", metavar="GITLAB_GROUP_ID", type=str, nargs="?", default="10799335",
                    help="In which group to create the new variable")

parser.add_argument("ssh_key_path", metavar="SSH_KEY_PATH", type=str, default="$HOME/.ssh/id_rsa", nargs="?",
                    help="Key to SSH into the new server")

parser.add_argument("deploy_path", metavar="DEPLOY_PATH", type=str, default="/usr/local/src/stock-pickr", nargs="?",
                    help="Where to clone git repo")

parser.add_argument("docker_compose_version", metavar="DOCKER_COMPOSE_VERSION", type=str, nargs="?", default="1.29.2",
                    help="Which version to install")

args = parser.parse_args()
gitlab_deploy_token = args.gitlab_deploy_token
gitlab_username = args.gitlab_username
deploy_path = args.deploy_path
docker_compose_version = args.docker_compose_version
ssh_connection = args.ssh_connection
gitlab_variable = args.gitlab_variable
gitlab_group_id = args.gitlab_group_id
gitlab_api_access_token = args.gitlab_api_access_token
ssh_key_path = args.ssh_key_path

def clone_repo(gitlab_username: str, gitlab_deploy_token: str, ssh_key_path: str, ssh_connection: str):    
    git_repo_url = "https://%s:%s@gitlab.com/stock-pickr/stock-pickr.git" % (gitlab_username, gitlab_deploy_token)
    remote_script = "git clone %s %s" % (git_repo_url, deploy_path)

    subprocess.run(["ssh", "-tt", "-o", "StrictHostKeyChecking=no", "-i", 
                    ssh_key_path, ssh_connection, remote_script])

def install_docker_compose(docker_compose_version: str, ssh_key_path: str, ssh_connection: str):
    download_compose_script = "sudo curl -L https://github.com/docker/compose/releases/download/%s/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose" % docker_compose_version
    make_executable_script = "sudo chmod +x /usr/local/bin/docker-compose"

    subprocess.run(["ssh", "-tt", "-o", "StrictHostKeyChecking=no", "-i", 
                    ssh_key_path, ssh_connection, download_compose_script])
    subprocess.run(["ssh", "-tt", "-o", "StrictHostKeyChecking=no", "-i", 
                    ssh_key_path, ssh_connection, make_executable_script])

def create_new_gitlab_variable(gitlab_api_access_token: str, gitlab_group_id: str, 
                               gitlab_variable: str, ssh_connection: str):

    url = "https://gitlab.com/api/v4/groups/%s/variables?private_token=%s" % (gitlab_group_id, gitlab_api_access_token)
    requests.post(url, {
        "key": gitlab_variable,
        "value": ssh_connection
    })

install_docker_compose(docker_compose_version, ssh_key_path, ssh_connection)
clone_repo(gitlab_username, gitlab_deploy_token, ssh_key_path, ssh_connection)
create_new_gitlab_variable(gitlab_api_access_token, gitlab_group_id, gitlab_variable, ssh_connection)