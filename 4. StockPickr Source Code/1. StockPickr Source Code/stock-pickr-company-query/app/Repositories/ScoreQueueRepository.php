<?php

namespace App\Repositories;

use App\Exceptions\EmptyQueue;
use Illuminate\Support\Facades\Redis;

/**
 * Pontozáskor, a beérkező pontszámokat tárolja egy Redis queue -ban.
 * Pontozás végén egy batch -ben lesznek update -elve MySQL -be.
 *
 * Azért van így, mert az új pontszámok cégenként érkeznek. Ha ezután rögtön van
 * egy update a position -re, az sokszor duplicate key entry kivételt okoz.
 */
class ScoreQueueRepository
{
    public const QUEUE = 'score-queue';

    public function enqueue(string $json): void
    {
        Redis::rpush(self::QUEUE, $json);
    }

    public function dequeue(): string
    {
        if ($this->isEmpty()) {
            throw new EmptyQueue();
        }

        return Redis::lpop(self::QUEUE);
    }

    public function isEmpty(): bool
    {
        return Redis::llen(self::QUEUE) === 0;
    }
}
