<?php

// Copyright (C) 2024 Ivan Stasiuk <ivan@stasi.uk>.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

namespace BrokeYourBike\Interswitch\Interfaces;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
interface TransactionInterface
{
    public function getReference(): string;
    public function getCurrency(): int;
    public function getCents(): int;

    public function getSenderFirstName(): string;
    public function getSenderLastName(): string;
    public function getSenderPhone(): ?string;
    public function getSenderEmail(): ?string;

    public function getRecipientCountry(): string;
    public function getRecipientFirstName(): string;
    public function getRecipientLastName(): string;
    public function getRecipientBankCode(): ?string;
    public function getRecipientAccountNumber(): ?string;
}
