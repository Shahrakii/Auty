<?php

namespace Shahrakii\Crudly;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Crudly
{
    protected $app;
    protected $globalFilters = [];
    protected $tableFilters = [];

    public function __construct($app = null)
    {
        $this->app = $app;
        $this->loadConfig();
    }

    protected function loadConfig()
    {
        $this->globalFilters = config('crudly.global_filters', [
            'id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);

        $this->tableFilters = config('crudly.table_filters', []);
    }

    /**
     * Check if table exists
     */
    public function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }

    /**
     * Get table columns
     */
    public function getTableColumns(string $table): array
    {
        return Schema::getColumns($table);
    }

    /**
     * Get filtered columns (exclude system columns)
     */
    public function getFilteredColumns(string $table): array
    {
        $columns = $this->getTableColumns($table);
        $excludeColumns = $this->getExcludeColumns($table);

        return array_filter($columns, function ($column) use ($excludeColumns) {
            return !in_array($column['name'], $excludeColumns);
        });
    }

    /**
     * Get columns to exclude
     */
    protected function getExcludeColumns(string $table): array
    {
        $exclude = $this->globalFilters;

        // Add table-specific filters
        if (isset($this->tableFilters[$table])) {
            $exclude = array_merge($exclude, $this->tableFilters[$table]);
        }

        return $exclude;
    }

    /**
     * Generate validation rules from table schema
     */
    public function getValidationRules(string $table): array
    {
        $columns = $this->getFilteredColumns($table);
        $rules = [];

        foreach ($columns as $column) {
            $columnName = $column['name'];
            $columnType = $column['type_name'] ?? 'string';

            // Generate rule based on column type
            $rule = $this->generateRule($columnName, $columnType, $column);
            $rules[$columnName] = $rule;
        }

        return $rules;
    }

    /**
     * Generate validation rule based on column type
     */
    protected function generateRule(string $name, string $type, array $column): string
    {
        $required = !($column['nullable'] ?? true);
        $base = $required ? 'required' : 'nullable';

        // Type-based rules
        switch ($type) {
            case 'bigint':
            case 'integer':
            case 'smallint':
            case 'int':
                return "{$base}|integer";

            case 'decimal':
            case 'float':
            case 'double':
                return "{$base}|numeric";

            case 'boolean':
                return "{$base}|boolean";

            case 'datetime':
            case 'timestamp':
            case 'datetimetz':
                return "{$base}|date_format:Y-m-d H:i:s";

            case 'date':
                return "{$base}|date_format:Y-m-d";

            case 'time':
                return "{$base}|date_format:H:i:s";

            case 'text':
            case 'longtext':
            case 'mediumtext':
                return "{$base}|string";

            case 'varchar':
            case 'char':
            case 'string':
            default:
                $maxLength = $column['length'] ?? 255;
                return "{$base}|string|max:{$maxLength}";
        }
    }

    /**
     * Get column display type
     */
    public function getColumnType(string $type): string
    {
        return match ($type) {
            'integer', 'bigint', 'smallint', 'int' => 'number',
            'decimal', 'float', 'double' => 'number',
            'boolean' => 'checkbox',
            'datetime', 'timestamp', 'datetimetz' => 'datetime',
            'date' => 'date',
            'time' => 'time',
            'text', 'longtext', 'mediumtext' => 'textarea',
            'varchar', 'char', 'string' => 'text',
            default => 'text',
        };
    }
}
