<?php

namespace Shahrakii\Crudly\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Shahrakii\Crudly\Crudly;
use Shahrakii\Crudly\Utilities\ImageHandler;
use Shahrakii\Crudly\Utilities\RelationshipDetector;

class GenerateCrudCommand extends Command
{
    protected $signature = 'crudly:generate 
        {model : The model name (e.g., Post)} 
        {--table= : Specify table name (optional)}
        {--force : Overwrite existing files}
        {--routes : Generate routes automatically}';

    protected $description = 'Generate complete CRUD operations for a model';

    protected $files;
    protected $crudly;
    protected $stubCache = [];
    protected $imageFields = [];
    protected $relationships = [];

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
        $this->crudly = new Crudly(app());
    }

    public function handle()
    {
        $startTime = microtime(true);
        $model = $this->argument('model');
        $table = $this->option('table') ?? Str::snake(Str::pluralStudly($model));
        $force = $this->option('force');

        if (!$this->crudly->tableExists($table)) {
            $this->error("❌ Table '{$table}' does not exist!");
            return 1;
        }

        $this->info("🚀 Generating CRUD for {$model}...\n");

        // Detect relationships
        $detector = new RelationshipDetector($table);
        $this->relationships = $detector->detectRelationships();

        $columns = $this->crudly->getFilteredColumns($table);
        $this->imageFields = ImageHandler::getImageFields($columns);

        if (!empty($this->relationships)) {
            $relationshipNames = implode(', ', array_map(fn($r) => $r['method'], $this->relationships));
            $this->info("🔗 Found relationships: {$relationshipNames}");
        }

        if (!empty($this->imageFields)) {
            $this->info("📸 Found image fields: " . implode(', ', $this->imageFields));
        }

        $generated = [];
        $generated['model'] = $this->generateModel($model, $table, $force);
        $generated['controller'] = $this->generateController($model, $table, $force);
        $generated['views'] = $this->generateViews($model, $table, $force);

        if ($generated['model']) $this->info("✅ Generated Model: {$model}");
        if ($generated['controller']) $this->info("✅ Generated Controller: {$model}Controller");
        if ($generated['views']) $this->info("✅ Generated Views (4 files)");

        if ($this->option('routes') || $this->confirm('Add routes to routes/web.php?')) {
            if ($this->generateRoutes($model)) {
                $this->info("✅ Routes added");
            }
        }

        $duration = number_format(microtime(true) - $startTime, 2);
        $this->info("\n✨ CRUD generation complete in {$duration}s!");
        $this->info("📝 Visit: http://localhost:8000/" . Str::snake(Str::pluralStudly($model)));

        return 0;
    }

    protected function generateModel(string $model, string $table, bool $force): bool
    {
        $path = app_path("Models/{$model}.php");

        if ($this->files->exists($path) && !$force) {
            $this->warn("⚠️  Model {$model} already exists. Use --force to overwrite.");
            return false;
        }

        $columns = $this->crudly->getFilteredColumns($table);
        $fillable = var_export(array_map(fn($col) => $col['name'], $columns), true);
        
        $relationshipMethods = $this->generateRelationshipMethods();
        
        $stub = $this->loadStub('model');
        $stub = str_replace(
            ['{{ MODEL }}', '{{ TABLE }}', '{{ FILLABLE }}', '{{ RELATIONSHIPS }}'],
            [$model, $table, $fillable, $relationshipMethods],
            $stub
        );
        
        $this->files->put($path, $stub);
        return true;
    }

    protected function generateController(string $model, string $table, bool $force): bool
    {
        $path = app_path("Http/Controllers/{$model}Controller.php");

        if ($this->files->exists($path) && !$force) {
            $this->warn("⚠️  Controller {$model}Controller already exists. Use --force to overwrite.");
            return false;
        }

        $columns = $this->crudly->getFilteredColumns($table);
        $rules = $this->crudly->getValidationRules($table);
        
        foreach ($this->imageFields as $imageField) {
            $rules[$imageField] = 'nullable|image|mimes:jpeg,png,gif,webp|max:2048';
        }
        
        $rulesExport = var_export($rules, true);

        $modelPlural = Str::camel(Str::pluralStudly($model));
        $modelPluralSnake = Str::snake(Str::pluralStudly($model));
        $modelLower = Str::camel($model);

        $imageHandlingStore = $this->generateImageHandlingForStore($this->imageFields);
        $imageHandlingUpdate = $this->generateImageHandlingForUpdate($this->imageFields, $modelLower);
        $eagerLoadingChain = $this->generateEagerLoadingChain();  // ← NEW

        $stub = $this->loadStub('controller');
        $stub = str_replace(
            ['{{ MODEL }}', '{{ TABLE }}', '{{ RULES }}', '{{ MODEL_PLURAL_CAMEL }}', '{{ MODEL_PLURAL_SNAKE }}', '{{ MODEL_LOWER }}', '{{ IMAGE_HANDLING_STORE }}', '{{ IMAGE_HANDLING_UPDATE }}', '{{ EAGER_LOADING_CHAIN }}'],
            [$model, $table, $rulesExport, $modelPlural, $modelPluralSnake, $modelLower, $imageHandlingStore, $imageHandlingUpdate, $eagerLoadingChain],
            $stub
        );

        $this->files->put($path, $stub);
        return true;
    }

    protected function generateViews(string $model, string $table, bool $force): bool
    {
        $viewPath = resource_path("views/".Str::snake(Str::pluralStudly($model)));

        if ($this->files->exists($viewPath) && !$force) {
            $this->warn("⚠️  Views already exist. Use --force to overwrite.");
            return false;
        }

        $this->files->makeDirectory($viewPath, 0755, true, true);

        $columns = $this->crudly->getFilteredColumns($table);
        $modelLower = Str::camel($model);
        $modelPlural = Str::snake(Str::pluralStudly($model));
        $modelPluralCamel = Str::camel(Str::pluralStudly($model));

        $columnHeaders = implode("\n                        ", array_map(fn($col) => 
            "<th class=\"px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider\">" . 
            Str::of($col['name'])->replace('_', ' ')->title() . "</th>", 
            $columns
        ));

        $columnData = implode("\n                            ", array_map(fn($col) => 
            "<td class=\"px-6 py-4 text-sm text-gray-300\">{{ \${$modelLower}->{$col['name']} }}</td>", 
            $columns
        ));

        $formFields = implode("\n\n        ", array_map(
            fn($col) => $this->generateFormField($col['name'], $modelLower), 
            $columns
        ));

        $displayFields = implode("\n\n        ", array_map(
            fn($col) => $this->generateDisplayField($col['name'], $modelLower), 
            $columns
        ));

        // Index view
        $stub = $this->loadStub('view/index');
        $stub = str_replace(
            ['{{ MODEL }}', '{{ MODEL_PLURAL }}', '{{ MODEL_LOWER_PLURAL }}', '{{ COLUMN_HEADERS }}', '{{ COLUMN_DATA }}', '{{ MODEL_PLURAL_SNAKE }}', '{{ MODEL_PLURAL_CAMEL }}', '{{ MODEL_LOWER }}'],
            [$model, Str::plural($model), Str::plural(Str::lower($model)), $columnHeaders, $columnData, $modelPlural, $modelPluralCamel, $modelLower],
            $stub
        );
        $this->files->put("$viewPath/index.blade.php", $stub);

        // Create view
        $createStub = $this->loadStub('view/create');
        $createStub = str_replace(
            ['{{ MODEL }}', '{{ MODEL_PLURAL_SNAKE }}', '{{ MODEL_LOWER }}', '{{ FORM_FIELDS }}'],
            [$model, $modelPlural, $modelLower, $formFields],
            $createStub
        );
        $this->files->put("$viewPath/create.blade.php", $createStub);

        // Edit view
        $editStub = $this->loadStub('view/edit');
        $editStub = str_replace(
            ['{{ MODEL }}', '{{ MODEL_PLURAL_SNAKE }}', '{{ MODEL_LOWER }}', '{{ FORM_FIELDS }}'],
            [$model, $modelPlural, $modelLower, $formFields],
            $editStub
        );
        $this->files->put("$viewPath/edit.blade.php", $editStub);

        // Show view
        $showStub = $this->loadStub('view/show');
        $showStub = str_replace(
            ['{{ MODEL }}', '{{ MODEL_PLURAL_SNAKE }}', '{{ MODEL_LOWER }}', '{{ DISPLAY_FIELDS }}'],
            [$model, $modelPlural, $modelLower, $displayFields],
            $showStub
        );
        $this->files->put("$viewPath/show.blade.php", $showStub);

        return true;
    }

    protected function generateFormField(string $fieldName, string $modelLower): string
    {
        // Check if it's a foreign key
        $isForeignKey = Str::endsWith($fieldName, '_id');
        
        if ($isForeignKey) {
            $stub = $this->loadStub('view/select-field');
            $relationshipMethod = Str::camel(Str::replaceLast('_id', '', $fieldName));
            $relationshipModel = null;
            
            foreach ($this->relationships as $rel) {
                if ($rel['method'] === $relationshipMethod) {
                    $relationshipModel = $rel['model'];
                    break;
                }
            }
            
            $label = Str::of($fieldName)->replace('_', ' ')->title();
            
            return str_replace(
                ['{{ FIELD_NAME }}', '{{ FIELD_LABEL }}', '{{ RELATIONSHIP_METHOD }}', '{{ RELATIONSHIP_MODEL }}', '{{ MODEL_LOWER }}'],
                [$fieldName, $label, $relationshipMethod, $relationshipModel ?? 'Model', $modelLower],
                $stub
            );
        }
        
        if (ImageHandler::isImageField($fieldName)) {
            $stub = $this->loadStub('view/image-field');
        } else {
            $stub = $this->loadStub('view/form-field');
        }

        $label = Str::of($fieldName)->replace('_', ' ')->title();
        
        return str_replace(
            ['{{ FIELD_NAME }}', '{{ FIELD_LABEL }}', '{{ FIELD_LABEL_LOWER }}', '{{ FIELD_TYPE }}', '{{ MODEL_LOWER }}', '{{ FIELD_REQUIRED }}'],
            [$fieldName, $label, Str::lower($label), 'text', $modelLower, 'required'],
            $stub
        );
    }

    protected function generateDisplayField(string $fieldName, string $modelLower): string
    {
        // Check if it's a foreign key
        $isForeignKey = Str::endsWith($fieldName, '_id');
        
        if ($isForeignKey) {
            $relationshipMethod = Str::camel(Str::replaceLast('_id', '', $fieldName));
            $label = Str::of($fieldName)->replace('_', ' ')->title();
            
            return "
<div class=\"space-y-1 p-4 bg-gray-700/50 rounded-lg border border-gray-600\">
    <p class=\"text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2\">
        <i class=\"fas fa-link\"></i>
        {$label}
    </p>
    <p class=\"text-lg text-gray-100 font-medium\">{{ \${$modelLower}->{$relationshipMethod}->name ?? 'N/A' }}</p>
</div>";
        }
        
        if (ImageHandler::isImageField($fieldName)) {
            $stub = $this->loadStub('view/image-display');
        } else {
            $stub = $this->loadStub('view/display-field');
        }

        $label = Str::of($fieldName)->replace('_', ' ')->title();
        
        return str_replace(
            ['{{ FIELD_NAME }}', '{{ FIELD_LABEL }}', '{{ MODEL_LOWER }}'],
            [$fieldName, $label, $modelLower],
            $stub
        );
    }

    protected function loadStub(string $path): string
    {
        if (isset($this->stubCache[$path])) {
            return $this->stubCache[$path];
        }

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

    protected function generateRelationshipMethods(): string
    {
        if (empty($this->relationships)) {
            return '// No relationships detected';
        }

        $code = "\n    // Relationships\n";
        
        foreach ($this->relationships as $rel) {
            if ($rel['type'] === 'belongsTo') {
                $code .= "    public function {$rel['method']}()\n";
                $code .= "    {\n";
                $code .= "        return \$this->belongsTo({$rel['model']}::class);\n";
                $code .= "    }\n\n";
            }
        }

        return $code;
    }

    protected function generateEagerLoading(): string
    {
        if (empty($this->relationships)) {
            return '::';
        }

        $relations = array_map(fn($r) => "'{$r['method']}'", $this->relationships);
        return "::with(" . implode(", ", $relations) . ")->";
    }

    protected function generateImageHandlingForStore(array $imageFields): string
    {
        if (empty($imageFields)) {
            return '// No image fields';
        }

        $code = "// Handle image uploads (store - no need to delete old images)\n        ";
        
        foreach ($imageFields as $field) {
            $code .= "if (\$request->hasFile('{$field}')) {\n";
            $code .= "            \$validated['{$field}'] = \$request->file('{$field}')->store('uploads', 'public');\n";
            $code .= "        }\n";
        }

        return $code;
    }

    protected function generateEagerLoadingChain(): string
    {
        if (empty($this->relationships)) {
            return '::latest()->paginate(15)';
        }

        $relations = array_map(fn($r) => "'{$r['method']}'", $this->relationships);
        return '::with(' . implode(", ", $relations) . ')->latest()->paginate(15)';
    }

    protected function generateImageHandlingForUpdate(array $imageFields, string $modelLower): string
    {
        if (empty($imageFields)) {
            return '// No image fields';
        }

        $code = "// Handle image uploads (update - delete old images)\n        ";
        
        foreach ($imageFields as $field) {
            $code .= "if (\$request->hasFile('{$field}')) {\n";
            $code .= "            if (\${$modelLower}->{$field} && \\Illuminate\\Support\\Facades\\Storage::disk('public')->exists(\${$modelLower}->{$field})) {\n";
            $code .= "                \\Illuminate\\Support\\Facades\\Storage::disk('public')->delete(\${$modelLower}->{$field});\n";
            $code .= "            }\n";
            $code .= "            \$validated['{$field}'] = \$request->file('{$field}')->store('uploads', 'public');\n";
            $code .= "        }\n";
        }

        return $code;
    }

    protected function generateRoutes(string $model): bool
    {
        $routesPath = base_path('routes/web.php');
        $controller = "App\\Http\\Controllers\\{$model}Controller";
        $route = Str::snake(Str::pluralStudly($model));

        $routeContent = "Route::resource('{$route}', {$controller}::class);";

        $content = $this->files->get($routesPath);

        if (strpos($content, $routeContent) !== false) {
            $this->warn("⚠️  Routes already exist.");
            return false;
        }

        $content = rtrim($content) . "\n\n// " . $model . " Routes\n" . $routeContent . "\n";
        $this->files->put($routesPath, $content);

        return true;
    }
}