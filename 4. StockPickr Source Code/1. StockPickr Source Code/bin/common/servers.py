import os

SERVERS = [
    {
        "name": "production1", 
        "connection": os.environ["SP_SSH_CONNECTION_PROD_1"],
        "compose_target": "prod"
    },
    {
        "name": "production2", 
        "connection": os.environ["SP_SSH_CONNECTION_PROD_2"],
        "compose_target": "prod"
    },
    {
        "name": "production3",
        "connection": os.environ["SP_SSH_CONNECTION_PROD_3"],
        "compose_target": "prod"
    }
]

DB_SERVER = {
    "name": "production-db",
    "connection": os.environ["SP_SSH_CONNECTION_PROD_DB"],
    "compose_target": "prod.db"
}