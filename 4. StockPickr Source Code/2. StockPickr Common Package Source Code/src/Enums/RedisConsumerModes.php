<?php

namespace StockPickr\Common\Enums;

final class RedisConsumerModes
{
    /**
     * MINDEN az event stream -ben lévő eseményt lekérdezi
     */
    public const ALL_EVENTS = 'all-events';
    /**
     * Csak azokat az eseményeket kérdezi le, amiket még nem látott
     */
    public const ONLY_NEW_EVENTS = 'only-new-events';
}