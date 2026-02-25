<?php

namespace Shahrakii\Crudly\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class GenerateControllerCommand extends Command
{
    protected $signature = 'crudly:controller 
        {name : Controller name (e.g., PostController)} 
        {model : Model name (e.g., Post)} 
        {--force : Overwrite existing files}';

    protected $description = 'Generate a CRUD controller for a model';

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
        $model = $this->argument('model');
        $force = $this->option('force');

        $path = app_path("Http/Controllers/{$name}.php");

        if ($this->files->exists($path) && !$force) {
            $this->error("❌ Controller {$name} already exists!");
            $this->info("💡 Use --force to overwrite");
            return 1;
        }

        $stub = $this->getStub($name, $model);
        $this->files->put($path, $stub);

        $duration = number_format(microtime(true) - $startTime, 3);
        $this->info("✅ Controller {$name} created successfully! ({$duration}s)");

        return 0;
    }

    protected function getStub(string $name, string $model): string
    {
        $cacheKey = "controller_{$model}";
        
        if (isset($this->stubCache[$cacheKey])) {
            return $this->stubCache[$cacheKey];
        }

        $stub = $this->loadStub('controller');

        $modelLower = Str::camel($model);
        $modelPlural = Str::camel(Str::pluralStudly($model));

        $stub = str_replace(
            ['{{ CONTROLLER_NAME }}', '{{ MODEL }}', '{{ MODEL_LOWER }}', '{{ MODEL_PLURAL }}'],
            [$name, $model, $modelLower, $modelPlural],
            $stub
        );

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
        // stubs/controller/
        
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
