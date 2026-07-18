<?php

namespace Tests\Feature;

use Tests\TestCase;

class MarketingPagesTest extends TestCase
{
    public function test_all_marketing_pages_are_available(): void
    {
        $pages = [
            '/' => 'Lebih sedikit urusan sistem.',
            '/fitur' => 'Inventaris fitur nyata',
            '/harga' => 'Pilih fitur. Pilih durasi.',
            '/kontak' => 'Mari bahas kebutuhan sekolah Anda.',
            '/privasi' => 'Bagaimana kami memperlakukan data Anda.',
        ];

        foreach ($pages as $uri => $text) {
            $this->get($uri)
                ->assertOk()
                ->assertSee($text)
                ->assertSee('property="og:title"', false)
                ->assertSee('property="og:image"', false)
                ->assertSee('name="description"', false);
        }
    }

    public function test_home_shows_product_screenshots_and_outcome_trust_bar(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('images/product/dashboard.png', false)
            ->assertSee('Satu alur dari kelas ke laporan')
            ->assertDontSee('AsistenAI')
            ->assertDontSee('100+ tabel');
    }

    public function test_landing_links_to_the_configured_application_login(): void
    {
        config()->set('marketing.app_url', 'https://app.sims.test');

        $this->get('/')
            ->assertOk()
            ->assertSee('https://app.sims.test/login', false);
    }

    public function test_pricing_page_shows_live_amounts_and_not_placeholder_faq(): void
    {
        config()->set('marketing.ppn_rate', 11);
        config()->set('marketing.prices', [
            'dasar' => [3 => 1_200_000, 6 => 2_100_000, 12 => 3_600_000],
            'pro' => [3 => 1_950_000, 6 => 3_600_000, 12 => 6_000_000],
            'enterprise' => [3 => 3_600_000, 6 => 6_600_000, 12 => 12_000_000],
        ]);

        $this->get('/harga')
            ->assertOk()
            ->assertSee('PPN 11%')
            ->assertSee('Dasar')
            ->assertSee('Pro')
            ->assertSee('Enterprise')
            ->assertSee('Paling hemat')
            ->assertSee('Rp 3.600.000')
            ->assertSee('Rp 6.000.000')
            ->assertSee('Rp 12.000.000')
            ->assertSee('Rp 396.000') // PPN 11% dari Dasar 12 bulan
            ->assertSee('Apakah harga di halaman ini final?')
            ->assertDontSee('placeholder eksplisit')
            ->assertDontSee('Mengapa angka harga masih kosong?')
            ->assertSee('btivesolution@gmail.com')
            ->assertSee('085668330050')
            ->assertSee('wa.me/6285668330050', false);
    }

    public function test_home_shows_twelve_month_package_prices(): void
    {
        config()->set('marketing.prices.pro.12', 6_000_000);

        $this->get('/')
            ->assertOk()
            ->assertSee('Rp 6.000.000')
            ->assertDontSee('· contoh');
    }

    public function test_contact_page_shows_real_contact_details(): void
    {
        $this->get('/kontak')
            ->assertOk()
            ->assertSee('btivesolution@gmail.com')
            ->assertSee('085668330050')
            ->assertSee('wa.me/6285668330050', false);
    }
}
