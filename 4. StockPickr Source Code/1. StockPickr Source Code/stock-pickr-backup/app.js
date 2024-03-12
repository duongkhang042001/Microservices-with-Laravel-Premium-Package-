const schedule = require('node-schedule');
const config = require('./config');
const BackupService = require('./src/services/backup.service');
const CleanupService = require('./src/services/cleanup.service');
const S3Service = require('./src/services/s3.service');

(async() => {
    try {
        const s3 = new S3Service(config);
        const backupService = new BackupService(config, s3);
        const cleanupService = new CleanupService(config, s3);

        schedule.scheduleJob(`${config.backup.minute} ${config.backup.hour} * * *`, async() => {
            console.log('Running backup');
            await backupService.backup();
        });
        schedule.scheduleJob(`${config.cleanup.minute} ${config.cleanup.hour} * * *`, async() => {
            console.log('Running cleanup');
            await cleanupService.cleanup();
        });
    } catch (err) {
        console.log(err);
    }
})();