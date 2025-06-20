<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
class RepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat repository class di folder app/Repositories';

    public function handle()
    {
        $name = $this->argument('name');
        $repositoryName = $name;
        $directory = app_path('Repositories');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = $directory . '/' . $repositoryName . '.php';

        if (File::exists($filePath)) {
            $this->error("Repository {$repositoryName} sudah ada!");
            return;
        }

        $content = <<<PHP
<?php

namespace App\Repositories;

use App\Repositories\Repository;

class {$repositoryName} extends Repository
{
    //
}

PHP;
        File::put($filePath, $content);
        // Menampilkan full path dan pesan sukses
        $realPath = realpath($filePath);
        $this->info("Console command [{$realPath}] created successfully.");
    }
}
