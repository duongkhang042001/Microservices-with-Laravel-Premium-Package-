class CleanupService {
    constructor(config, s3Service) {
        this.config = config;
        this.s3Service = s3Service;
    }

    async cleanup() {
        if (this.config.env !== 'prod') {
            console.log('Aborting cleanup. App not in production');
            return;
        }

        const promises = (await this.s3Service.getObjectsFromBackup())
            .filter(o => this.isOld(o.LastModified))
            .map(o => o.Key)
            .map(key => this.s3Service.deleteObject(key));

        return await Promise.all(promises);
    }

    isOld(lastModifiedDate) {
        const diffMillisec = Math.abs(new Date() - new Date(lastModifiedDate));
        const diffDays = diffMillisec / 1000 / 60 / 60 / 24;

        return diffDays >= this.config.cleanup.age;
    }
}

module.exports = CleanupService;