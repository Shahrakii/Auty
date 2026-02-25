<?php

namespace Shahrakii\Crudly\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class GenerateModelCommand extends Command
{
    protected $signature = 'crudly:model 
        {name : Model name (e.g., Post)} 
        {--force : Overwrite existing files}';

    protected $description = 'Generate a model with fillable properties';

    protected $files;
    protected $stubCache = [];

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $startTime = microtime(true);
        $name = $this->argument('name');
        $force = $this->option('force');

        $path = app_path("Models/{$name}.php");

        if ($this->files->exists($path) && !$force) {
            $this->error("❌ Model {$name} already exists!");
            $this->info("💡 Use --force to overwrite");
            return 1;
        }

        $stub = $this->getStub($name);
        $this->files->put($path, $stub);

        $duration = number_format(microtime(true) - $startTime, 3);
        $this->info("✅ Model {$name} created successfully! ({$duration}s)");
        $this->info("📍 Location: app/Models/{$name}.php");

        return 0;
    }

    protected function getStub(string $name): string
    {
        $cacheKey = "model_{$name}";
        
        if (isset($this->stubCache[$cacheKey])) {
            return $this->stubCache[$cacheKey];
        }

        $stub = $this->loadStub('model');
        $stub = str_replace(['{{ MODEL }}', '{{ NAME }}'], [$name, $name], $stub);

        $this->stubCache[$cacheKey] = $stub;
        return $stub;
    }

    protected function loadStub(string $path): string
    {
        // Cache stubs in memory for performance
        if (isset($this->stubCache[$path])) {
            return $this->stubCache[$path];
        }

        // Load from stubs directory - matches your structure
        // stubs/model/
        
        $possiblePaths = [
            base_path('stubs/' . $path . '.stub'),
            __DIR__ . '/../../../../stubs/' . $path . '.stub',
            dirname(__FILE__, 3) . '/stubs/' . $path . '.stub',
        ];

        foreach ($possiblePaths as $stubPath) {
            if ($this->files->exists($stubPath)) {
                $stub = $this->files->get($stubPath);
                $this->stubCache[$path] = $stub;
                return $stub;
            }
        }

        throw new \RuntimeException("❌ Stub not found: stubs/{$path}.stub\n💡 Create it at: stubs/{$path}.stub");
    }
}
