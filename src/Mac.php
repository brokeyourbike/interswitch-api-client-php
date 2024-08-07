<?php

// Copyright (C) 2024 Ivan Stasiuk <ivan@stasi.uk>.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

namespace BrokeYourBike\Interswitch;

use BrokeYourBike\Interswitch\Interfaces\TransactionInterface;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class Mac
{
    public static function generate(TransactionInterface $transaction): string
    {
        return hash('sha512',
            $transaction->getCents() .
            $transaction->getCurrency() .
            'CA' .
            $transaction->getCents() .
            $transaction->getCurrency() .
            'AC' .
            $transaction->getRecipientCountry()
        );
    }
}