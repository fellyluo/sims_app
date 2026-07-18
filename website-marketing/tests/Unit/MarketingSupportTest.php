<?php

namespace Tests\Unit;

use App\Support\Marketing;
use Tests\TestCase;

class MarketingSupportTest extends TestCase
{
    public function test_money_and_tax_helpers(): void
    {
        $this->assertSame('Rp —', Marketing::money(null));
        $this->assertSame('Rp 6.000.000', Marketing::money(6_000_000));
        $this->assertSame(660_000, Marketing::taxAmount(6_000_000, 11));
        $this->assertSame(6_660_000, Marketing::totalWithTax(6_000_000, 11));
    }

    public function test_whatsapp_display_uses_local_zero_prefix(): void
    {
        config()->set('marketing.contact.whatsapp', '6285668330050');

        $this->assertSame('6285668330050', Marketing::whatsappDigits());
        $this->assertSame('085668330050', Marketing::whatsappDisplay());
    }
}
