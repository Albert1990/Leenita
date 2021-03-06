<?php
namespace App\Http\Enums;


class HttpStatus {
    const OK=200;
    const CREATED=201;
    const NO_CONTENT=204;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const BAD_REQUEST = 500;
    const CONFLICT = 409;
}