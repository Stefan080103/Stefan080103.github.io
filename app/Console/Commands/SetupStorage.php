<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SetupStorage extends Command
{
    protected $signature = 'storage:setup';
    protected $description = 'Setup storage directories and symlink';

    public function handle()
    {
        // Create storage directories
        $directories = ['posts', 'avatars'];
        
        foreach ($directories as $directory) {
            $path = storage_path("app/public/{$directory}");
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
                $this->info("Created directory: {$path}");
            }
        }

        // Create storage link
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');

        if (File::exists($linkPath)) {
            $this->info('Storage link already exists.');
        } else {
            if (!File::exists($targetPath)) {
                File::makeDirectory($targetPath, 0755, true);
            }
            
            try {
                symlink($targetPath, $linkPath);
                $this->info('Storage link created successfully.');
            } catch (\Exception $e) {
                $this->error('Could not create storage link: ' . $e->getMessage());
                return 1;
            }
        }

        $this->info('Storage setup completed successfully!');
        return 0;
    }
}
