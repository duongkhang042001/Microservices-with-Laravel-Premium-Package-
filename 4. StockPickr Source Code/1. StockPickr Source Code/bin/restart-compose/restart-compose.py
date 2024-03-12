import subprocess, os
import argparse

parser = argparse.ArgumentParser(description='Deploy services to servers')
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
targetCompose = args.target
registry = args.registry
registryUser = args.registryUser
registryPassword = args.registryPassword

subprocess.run(["docker", "login", "-u", registryUser, "-p", registryPassword, registry])

# Visszaadja a submodulokat (git ls-tree kimenetét) az alábbi formátumban:
# ['16000 commit d343dea stock-pickr-companies']
# Az utolsó két elem a commit hash és a submodule neve
def getSubmodules() -> "list[str]":
    result = subprocess.run(["git", "ls-tree", "HEAD:./"], stdout=subprocess.PIPE, encoding="utf8")
    lines = result.stdout.splitlines()
    lines = list(map(lambda line: line.replace("\t", " "), lines))
    return list(filter(lambda line: "commit" in line, lines))

# Visszaadja a submodulokhoz tartozó image -eket és azokat az ENV változókat, amiket
# a docker-compose használ, pl {"envVariable": "MARKET_DATA_IMAGE", "image": ".../stock-pickr-market-data"}
def getImages(submodules: list) -> "list[dict[str, str]]":
    images = list()
    for line in submodules:
        lineArr = line.split(" ")    
        serviceLongName = lineArr[3] # stock-pickr-companies
        serviceShortName = serviceLongName[12:] # companies
        commit = lineArr[2][:8]

        images.append({
            "envVariable": "%s_IMAGE" % serviceShortName.replace("-", "_").upper(),
            "image": "registry.gitlab.com/stock-pickr/%s/prod:%s" % (serviceLongName, commit)
        })

    return images

# Hozzáadja az aktuális env -hez az image -ekből összállított env változókat.
# Fontos, hogy hozzáadja, hiszen gitlab-ci is for exportálni env -eket (pl secret) mielőtt ezt a scriptet meghívja
def getMergedEnvironment(images) -> "dict[str, str]":
    env = os.environ.copy()
    env["NUMBER_OF_PROC"] = str(os.cpu_count())

    for o in images:
        env[o['envVariable']] = o['image']

    return env

submodules = getSubmodules()
images = getImages(submodules)
env = getMergedEnvironment(images)

print(env)

subprocess.run(["docker-compose", "-f", "docker-compose.%s.yml" % targetCompose, "down", "-v"], env=env)
subprocess.run(["docker-compose", "-f", "docker-compose.%s.yml" % targetCompose, "up", "-d", "--remove-orphans"], env=env)