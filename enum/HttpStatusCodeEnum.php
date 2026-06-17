<?php

declare(strict_types=1);

namespace enum;

enum HttpStatusCodeEnum: int
{
    case HttpOk = 200;
    case HttpForbidden = 403;
    case HttpNotFound = 404;
}
