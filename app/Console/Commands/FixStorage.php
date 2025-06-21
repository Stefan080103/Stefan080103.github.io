<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixStorage extends Command
{
    protected $signature = 'storage:fix';
    protected $description = 'Fix storage directories and permissions';

    public function handle()
    {
        $this->info('Fixing storage configuration...');

        // Create necessary directories
        $directories = [
            storage_path('app'),
            storage_path('app/public'),
            storage_path('app/public/posts'),
            storage_path('app/public/avatars'),
            storage_path('logs'),
            bootstrap_path('cache'),
        ];

        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
                $this->info("Created directory: {$directory}");
            } else {
                $this->info("Directory exists: {$directory}");
            }
        }

        // Set permissions
        $this->info('Setting permissions...');
        
        try {
            chmod(storage_path(), 0755);
            chmod(storage_path('app'), 0755);
            chmod(storage_path('app/public'), 0755);
            chmod(storage_path('logs'), 0755);
            chmod(bootstrap_path('cache'), 0755);
            
            $this->info('Permissions set successfully.');
        } catch (\Exception $e) {
            $this->error('Could not set permissions: ' . $e->getMessage());
        }

        // Handle storage link
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');

        if (File::exists($linkPath)) {
            if (is_link($linkPath)) {
                $this->info('Storage link already exists and is valid.');
            } else {
                $this->warn('Storage path exists but is not a symlink. Removing...');
                File::deleteDirectory($linkPath);
            }
        }

        if (!File::exists($linkPath)) {
            try {
                if (PHP_OS_FAMILY === 'Windows') {
                    // For Windows
                    $cmd = "mklink /J \"$linkPath\" \"$targetPath\"";
                    exec($cmd, $output, $returnCode);
                    
                    if ($returnCode === 0) {
                        $this->info('Storage link created successfully (Windows junction).');
                    } else {
                        $this->error('Failed to create storage link on Windows.');
                        $this->info('Try running: php artisan storage:link');
                    }
                } else {
                    // For Unix-like systems
                    symlink($targetPath, $linkPath);
                    $this->info('Storage link created successfully (Unix symlink).');
                }
            } catch (\Exception $e) {
                $this->error('Could not create storage link: ' . $e->getMessage());
                $this->info('Try running: php artisan storage:link');
            }
        }

        // Test write permissions
        $testFile = storage_path('app/public/test_write.txt');
        try {
            File::put($testFile, 'test');
            File::delete($testFile);
            $this->info('✅ Storage write test passed.');
        } catch (\Exception $e) {
            $this->error('❌ Storage write test failed: ' . $e->getMessage());
        }

        $this->info('Storage fix completed!');
        return 0;
    }
}
