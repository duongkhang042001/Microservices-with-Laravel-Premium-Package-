<?php

namespace StockPickr\Common\Events\User;

use StockPickr\Common\Containers\User\UserContainer;
use StockPickr\Common\Events\Event;
use StockPickr\Common\Events\Events;

class UserCreatedEvent extends Event
{
    public string $type = Events::USER_CREATED;

    public function __construct(public UserContainer $data) {}
}