const tar = require('tar');
const fs = require('fs');

class BackupService {
    constructor(config, s3Service) {
        this.config = config;
        this.s3Service = s3Service;
    }

    async backup() {
        if (this.config.env !== 'prod') {
            console.log('Aborting backup. App not in production');
            return;
        }

        tar.c({
            gzip: true
        }, [this.config.paths.mysql]).pipe(fs.createWriteStream('/tmp/mysql.tgz', { flags: 'w+' }));

        tar.c({
            gzip: true
        }, [this.config.paths.redis]).pipe(fs.createWriteStream('/tmp/redis.tgz', { flags: 'w+' }));

        /**
         * Azért van setTimeout -ban, mert így megvárja, amíg a két temp file létrejön
         * Ennél jobb megoldás lenne, ha S3 service valahogy implementálna egy writable stream -et pl
         */
        setTimeout(async() => {
            const now = (new Date()).toISOString();
            await this.s3Service.putObject(`backups/mysql-${now}.tgz`, fs.createReadStream('/tmp/mysql.tgz'));
            await this.s3Service.putObject(`backups/redis-${now}.tgz`, fs.createReadStream('/tmp/redis.tgz'));

            fs.unlink('/tmp/mysql.tgz', () => {});
            fs.unlink('/tmp/redis.tgz', () => {});
        }, 10000);
    }
}

module.exports = BackupService;