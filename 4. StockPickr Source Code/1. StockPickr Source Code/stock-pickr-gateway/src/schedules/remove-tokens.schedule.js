const schedule = require('node-schedule');

class RemoveTokensSchedule {
    constructor(redisService) {
        this.redisService = redisService;
    }

    run() {
        schedule.scheduleJob('0 * * * *', async () => {
            await this.redisService.removeTokens();
        });
    }
}

module.exports = RemoveTokensSchedule;
