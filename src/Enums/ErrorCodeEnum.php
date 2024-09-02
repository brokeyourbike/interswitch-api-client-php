<?php

// Copyright (C) 2024 Ivan Stasiuk <ivan@stasi.uk>.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

namespace BrokeYourBike\Interswitch\Enums;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 * @link https://docs.interswitchgroup.com/docs/response-codes#response-codes
 */
enum ErrorCodeEnum: string
{
    case SUCCESS = '90000';
    case BANK_UNAVAILABLE = '70120';
    case INVALID_ENTITY_CODE = '70012';
    case FAILED = '90051';
}
