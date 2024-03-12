const AWS = require('aws-sdk');

class S3Service {
    constructor(config) {
        this.config = config;
        this.client = new AWS.S3({
            endpoint: new AWS.Endpoint(this.config.s3.endpoint),
            accessKeyId: this.config.s3.accessKey,
            secretAccessKey: this.config.s3.secret
        });
    }

    async putObject(key, inputStream) {
        const params = {
            Bucket: this.config.s3.bucket,
            Key: key,
            Body: inputStream,
            ACL: 'private'
        };

        return new Promise((res, rej) => {
            this.client.putObject(params, (err, data) => {
                if (err) {
                    rej(err);
                } else {
                    res(data);
                }
            });
        });
    }

    async listObjects() {
        const params = {
            Bucket: this.config.s3.bucket,
        };

        return new Promise((res, rej) => {
            this.client.listObjects(params, function(err, data) {
                if (err) {
                    rej(err);
                } else {
                    res(data['Contents']);
                }
            });
        });
    }

    async deleteObject(key) {
        console.log('Delete object: ', key);
        const params = {
            Bucket: this.config.s3.bucket,
            Key: key
        };

        return new Promise((res, rej) => {
            this.client.deleteObject(params, function(err, data) {
                if (err) {
                    rej(err);
                } else {
                    res(data);
                }
            });
        });

    }

    async getObjectsFromBackup() {
        return (await this.listObjects())
            .filter(o => this.isInBackupsFolder(o) && !this.isFolder(o))
    }

    isFolder(object) {
        return object.Size === 0;
    }

    isInBackupsFolder(object) {
        return object.Key.startsWith('backups/');
    }
}

module.exports = S3Service;