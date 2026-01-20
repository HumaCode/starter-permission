<?php

namespace App\Console\Commands;

use App\Helpers\MenuHelper;
use Illuminate\Console\Command;

class ClearMenuCache extends Command
{
    protected $signature = 'menu:clear-cache {--user= : Clear cache for specific user} {--role= : Clear cache for specific role}';
    protected $description = 'Clear menu cache';

    public function handle()
    {
        if ($userId = $this->option('user')) {
            MenuHelper::clearCacheForUser($userId);
            $this->info("Menu cache cleared for user {$userId}");
        } elseif ($roleName = $this->option('role')) {
            MenuHelper::clearCacheForRole($roleName);
            $this->info("Menu cache cleared for role {$roleName}");
        } else {
            MenuHelper::clearAllCache();
            $this->info('All menu caches cleared');
        }

        return 0;
    }
}