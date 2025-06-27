<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\Auth; // Pastikan file Auth.php ada di app/Filters/

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf' => CSRF::class,
        'toolbar' => DebugToolbar::class,
        'honeypot' => Honeypot::class,
        'invalidchars' => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors' => Cors::class,
        'forcehttps' => ForceHTTPS::class,
        'pagecache' => PageCache::class,
        'performance' => PerformanceMetrics::class,
        'auth' => Auth::class,// Tambahkan alias filter auth
    ];

    public array $globals = [
        'before' => [
            // 'csrf', // Aktifkan jika diperlukan
        ],
        'after' => [
            'toolbar',
            // 'secureheaders',
        ],
    ];

    public array $methods = [];

    public array $filters = [
        'auth' => ['before' => ['admin/*']], // Terapkan filter auth sebelum akses halaman admin
    ];
}