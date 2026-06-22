<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateStorageToCloudinary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:migrate-cloudinary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all local storage/app/public files to Cloudinary';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration to Cloudinary...');

        try {
            $files = Storage::disk('local_public')->allFiles();
        } catch (\Exception $e) {
            $this->error('Failed to access local_public disk. Make sure config/filesystems.php is properly configured. Error: ' . $e->getMessage());
            return 1;
        }

        $total = count($files);
        $this->info("Found {$total} files to migrate.");

        $success = 0;
        $failed = 0;

        foreach ($files as $file) {
            $this->info("Uploading: {$file}");

            try {
                // If it already exists on Cloudinary, we can skip it or overwrite it
                if (Storage::disk('public')->exists($file)) {
                    $this->warn("File already exists on Cloudinary, skipping: {$file}");
                    $success++;
                    continue;
                }

                $contents = Storage::disk('local_public')->get($file);
                Storage::disk('public')->put($file, $contents);
                $this->info("Successfully uploaded: {$file}");
                $success++;
            } catch (\Exception $e) {
                $this->error("Failed to upload: {$file}. Error: " . $e->getMessage());
                $failed++;
            }
        }

        $this->info("Migration completed! Success: {$success}, Failed: {$failed}");
        return 0;
    }
}
