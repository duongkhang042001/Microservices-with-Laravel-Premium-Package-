const config = {
    backup: {
        minute: process.env.BACKUP_MINUTE,
        hour: process.env.BACKUP_HOUR,
    },
    cleanup: {
        hour: process.env.CLEANUP_HOUR,
        minute: process.env.CLEANUP_MINUTE,
        // Ennél régebbi file -okat fogja törölni
        age: process.env.CLEANUP_AGE_IN_DAYS,
    },
    paths: {
        mysql: process.env.MYSQL_DATA_PATH,
        redis: process.env.REDIS_DATA_PATH
    },
    s3: {
        endpoint: process.env.S3_ENDPOINT,
        accessKey: process.env.S3_ACCESS_KEY,
        secret: process.env.S3_SECRET,
        bucket: process.env.S3_BUCKET
    },
    env: process.env.NODE_ENV
};

module.exports = config;