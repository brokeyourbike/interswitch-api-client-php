<?php

// Copyright (C) 2024 Ivan Stasiuk <ivan@stasi.uk>.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

namespace BrokeYourBike\Interswitch\Interfaces;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
interface ConfigInterface
{
    public function getAuthUrl(): string;
    public function getUrl(): string;
    public function getUsername(): string;
    public function getPassword(): string;
    public function getEntityCode(): string;
    public function getTransferCodePrefix(): string;
    public function getTerminalId(): string;
}
