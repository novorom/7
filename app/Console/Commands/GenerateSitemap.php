<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate XML sitemap with all pages and products';

    /**
     * SEO landing page slugs to include in sitemap.
     */
    protected array $seoPages = [
        'spb',
        'keramicheskaya-plitka-spb',
        'keramogranit-spb',
        'plitka-dlya-vannoj-spb',
        'mozaika-spb',
        'dostavka-plitki-spb',
        'magazin-plitki-spb',
    ];

    public function handle(): int
    {
        $this->info('Generating sitemap...');

        $baseUrl = rtrim(config('app.url', 'https://cersanit-spb.ru'), '/');
        $sitemapPath = public_path('sitemap.xml');
        $now = now()->toDateString();

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // ── Static pages ──────────────────────────────────────────────────────
        $staticPages = [
            ['loc' => '',             'changefreq' => 'weekly',  'priority' => '1.0'],
            ['loc' => '/catalog',     'changefreq' => 'daily',   'priority' => '0.9'],
            ['loc' => '/collections', 'changefreq' => 'weekly',  'priority' => '0.8'],
            ['loc' => '/delivery',    'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/about',       'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/contacts',    'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/reviews',     'changefreq' => 'monthly', 'priority' => '0.7'],
        ];

        foreach ($staticPages as $page) {
            $xml .= $this->urlEntry($baseUrl . $page['loc'], $now, $page['changefreq'], $page['priority']);
        }

        // ── SEO landing pages ─────────────────────────────────────────────────
        foreach ($this->seoPages as $slug) {
            $xml .= $this->urlEntry($baseUrl . '/' . $slug, $now, 'weekly', '0.8');
        }

        // ── Product pages ─────────────────────────────────────────────────────
        $products = Product::where('is_active', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->select(['slug', 'updated_at'])
            ->get();

        foreach ($products as $product) {
            $lastmod = $product->updated_at->toDateString();
            $xml .= $this->urlEntry(
                $baseUrl . '/catalog/' . $product->slug,
                $lastmod,
                'weekly',
                '0.7'
            );
        }

        $xml .= '</urlset>' . PHP_EOL;

        File::put($sitemapPath, $xml);

        $staticCount  = count($staticPages);
        $seoCount     = count($this->seoPages);
        $productCount = $products->count();
        $total        = $staticCount + $seoCount + $productCount;

        $this->info("Sitemap generated: {$staticCount} static + {$seoCount} SEO + {$productCount} products = {$total} total URLs.");
        $this->info("Location: {$sitemapPath}");

        return self::SUCCESS;
    }

    /**
     * Build a single <url> entry.
     */
    protected function urlEntry(
        string $loc,
        string $lastmod,
        string $changefreq,
        string $priority
    ): string {
        $entry  = '  <url>' . PHP_EOL;
        $entry .= '    <loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc>' . PHP_EOL;
        $entry .= '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
        $entry .= '    <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL;
        $entry .= '    <priority>' . $priority . '</priority>' . PHP_EOL;
        $entry .= '  </url>' . PHP_EOL;

        return $entry;
    }
}
