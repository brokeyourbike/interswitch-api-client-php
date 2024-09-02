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
    // Successful
    case SUCCESS = '90000';

    // Approved by Financial Institution, Partial. Kindly reach out to Interswitch.
    case APPROVED_UPDATE_TRACK_1 = '90010';

    // Approved by Financial Institution, VIP. Kindly reach out to Interswitch.
    case APPROVED_UPDATE_TRACK_2 = '90011';

    // Approved by Financial Institution, Update Track 3. Kindly reach out to Interswitch.
    case APPROVED_UPDATE_TRACK_3 = '90016';

    case BANK_UNAVAILABLE = '70120';

    // Your terminal has not been configured on Quick Teller. Please contact Interswitch.
    case UNRECOGNIZED_TERMINAL_OWNER = '70012';

    // Insufficient Funds. Please contact your bank.
    case INSUFFICIENT_FUNDS = '90051';
}
