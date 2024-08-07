<?php

// Copyright (C) 2024 Ivan Stasiuk <ivan@stasi.uk>.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

namespace BrokeYourBike\Interswitch\Tests;

use BrokeYourBike\Interswitch\Interfaces\TransactionInterface;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class MacTest extends TestCase
{
    /** @test */
    public function it_can_generate_mac(): void
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getCents')->willReturn(100000);
        $transaction->method('getCurrency')->willReturn(566);
        $transaction->method('getRecipientCountry')->willReturn('NG');

        /** @var TransactionInterface $transaction */
        $mac = \BrokeYourBike\Interswitch\Mac::generate($transaction);

        $this->assertEquals('9f4e4f53c57be63e1f08d8f07a7bc1a9461e4a7d5304043daa1ef54bd727b6cde148f4fbfc5e2ad8c4a60f78dfa76304de671fbeb70657b1628f14b6b6baa5e1', $mac);
    }
}