<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $baseUrl = config('app.url');
        $currentDate = now()->format('Y-m-d');

        $urls = [
            [
                'loc' => $baseUrl,
                'lastmod' => $currentDate,
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'loc' => $baseUrl . '/features',
                'lastmod' => $currentDate,
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ],
            [
                'loc' => $baseUrl . '/pricing',
                'lastmod' => $currentDate,
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ],
            [
                'loc' => $baseUrl . '/explore',
                'lastmod' => $currentDate,
                'changefreq' => 'daily',
                'priority' => '0.8'
            ],
            [
                'loc' => $baseUrl . '/contact',
                'lastmod' => $currentDate,
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ],
            [
                'loc' => $baseUrl . '/faq',
                'lastmod' => $currentDate,
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $baseUrl . '/privacy',
                'lastmod' => $currentDate,
                'changefreq' => 'yearly',
                'priority' => '0.3'
            ],
            [
                'loc' => $baseUrl . '/terms',
                'lastmod' => $currentDate,
                'changefreq' => 'yearly',
                'priority' => '0.3'
            ],
            [
                'loc' => $baseUrl . '/login',
                'lastmod' => $currentDate,
                'changefreq' => 'monthly',
                'priority' => '0.5'
            ],
            [
                'loc' => $baseUrl . '/register',
                'lastmod' => $currentDate,
                'changefreq' => 'monthly',
                'priority' => '0.5'
            ]
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8'
        ]);
    }
}
