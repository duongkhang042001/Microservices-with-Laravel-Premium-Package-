import subprocess, os
import argparse

parser = argparse.ArgumentParser(description='Deploy services to servers')
parser.add_argument('service', metavar='SERVICE', type=str,
                    help='Which service to restart in docker-compose')

parser.add_argument('image', metavar='IMAGE', type=str,
                    help='Which docker image to use')

parser.add_argument('target', metavar='TARGET_COMPOSE', type=str,
                    help='Which docker-compose to deploy. docker-compose.{TARGET_COMPOSE}.yml.' + 
                    ' To target docker-compose.prod.yml TARGET_COMPOSE needs to be prod')

parser.add_argument('registry', metavar='REGISTRY', type=str,
                    help='Which docker image registry to login and pull images')

parser.add_argument('registryUser', metavar='REGISTRY_USER', type=str,
                    help='Username for docker image registry')

parser.add_argument('registryPassword', metavar='REGISTRY_PASSWORD', type=str,
                    help='Password for docker image registry')

args = parser.parse_args()
service = args.service
image = args.image
target = args.target
registry = args.registry
registryUser = args.registryUser
registryPassword = args.registryPassword

# Visszaadja, hogy egy service -hez tartozik -e API service, tehát {service}-api nevű konténer
def hasProcessOfType(service: str, type: str = None) -> str:
    # Azért van _ a végén, mert különben a stock-pickr_charts-api is találat lenne
    dockerService = "stock-pickr_%s_" % service 
    if type is not None:
        dockerService = "%s-%s" % (service, type)

    ps = subprocess.run(["docker", "ps"], stdout=subprocess.PIPE, encoding="utf8")
    processes = ps.stdout.splitlines()[1:]  # Az első elem a fejléc
    filteredProcesses = list(filter(lambda process: dockerService in process, processes))
    
    return len(filteredProcesses) != 0

def stopContainers(service: str, hasDefaultProcess: bool, hasApiProcess: bool, 
                   apiService: str, hasWorkerProcess: bool, workerService: str): 
    if hasDefaultProcess:
        subprocess.run(["docker-compose", "-f", "docker-compose.%s.yml" % target, "stop", service])
    if hasApiProcess:
        subprocess.run(["docker-compose", "-f", "docker-compose.%s.yml" % target, "stop", apiService])
    if hasWorkerProcess:
        subprocess.run(["docker-compose", "-f", "docker-compose.%s.yml" % target, "stop", workerService])

def prune(): 
    subprocess.run(["docker", "system", "prune", "-f"])

def removeVolumes(service: str): 
    subprocess.run(["docker", "volume", "rm", "-f", "stock_pickr_%s-data" % service])
    subprocess.run(["docker", "volume", "rm", "-f", "stock_pickr_%s-nginx" % service])

def startContainers(service: str, hasDefaultProcess: bool, hasApiProcess: bool, hasWorkerProcess: bool, 
                    apiService: str, workerService: str, env: "dict[str, str]"):

    if hasDefaultProcess:
        subprocess.run(["docker-compose", "-f", "docker-compose.%s.yml" % target, 
                        "up", "-d", "--no-deps", service], env=env)

    if hasApiProcess:
        subprocess.run(["docker-compose", "-f", "docker-compose.%s.yml" % target, 
                        "up", "-d", "--no-deps", apiService], env=env)
    if hasWorkerProcess:
        subprocess.run(["docker-compose", "-f", "docker-compose.%s.yml" % target, 
                        "up", "-d", "--no-deps", workerService], env=env)

def getMergedEnv(service: str, image: str):
    env = os.environ.copy()
    # service=market-data, ezért kell a replace
    envName = "%s_IMAGE" % service.replace("-", "_").upper()
    env[envName] = image
    env["NUMBER_OF_PROC"] = str(os.cpu_count())

    return env

workerService = "%s-worker" % service
apiService = "%s-api" % service
hasDefaultProcess = hasProcessOfType(service)
hasApiProcess = hasProcessOfType(service, 'api')
hasWorkerProcess = hasProcessOfType(service, 'worker')
env = getMergedEnv(service, image)

# Pl worker szerveren olyan service, amihez csak API tartozik, de worker nem
if not hasDefaultProcess and not hasApiProcess and not hasWorkerProcess:
    print("No service to restart...")
    quit()

subprocess.run(["docker", "login", "-u", registryUser, "-p", registryPassword, registry])
subprocess.run(["docker", "pull", image])

# Először le kell állítani minden olyan konténert, ami named volumet használ
stopContainers(service, hasDefaultProcess, hasApiProcess, apiService, hasWorkerProcess, workerService)

# Törölni kell a leállított konténerekt, hogy a volume törölhető legyn
prune()

# Utána törölni a volume -t, mivel nem fog frissülni az image -ben lévő forrással
removeVolumes(service)

# És ezután lehet elindítani a konténereket
startContainers(service, hasDefaultProcess, hasApiProcess, hasWorkerProcess, apiService, workerService, env)