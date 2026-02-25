<?php

namespace Shahrakii\Crudly\Utilities;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RelationshipDetector
{
    protected $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function detectRelationships(): array
    {
        $relationships = [];
        $foreignKeys = $this->getForeignKeys();
        
        foreach ($foreignKeys as $foreignKey) {
            $relationships[] = [
                'type' => 'belongsTo',
                'column' => $foreignKey['column'],
                'table' => $foreignKey['references_table'],
                'model' => $this->tableToModel($foreignKey['references_table']),
                'method' => $this->columnToMethod($foreignKey['column']),
            ];
        }

        return $relationships;
    }

    protected function getForeignKeys(): array
    {
        $foreignKeys = [];
        $columns = Schema::getColumns($this->table);

        foreach ($columns as $column) {
            if (Str::endsWith($column['name'], '_id') && $column['name'] !== 'id') {
                $columnName = $column['name'];
                $referencedTable = $this->getReferencedTable($columnName);
                
                if ($referencedTable) {
                    $foreignKeys[] = [
                        'column' => $columnName,
                        'references_table' => $referencedTable,
                    ];
                }
            }
        }

        return $foreignKeys;
    }

    protected function getReferencedTable(string $column): ?string
    {
        $tableName = Str::singular(Str::replaceLast('_id', '', $column));
        $pluralTable = Str::plural($tableName);
        
        if (Schema::hasTable($pluralTable)) {
            return $pluralTable;
        }
        
        if (Schema::hasTable($tableName)) {
            return $tableName;
        }
        
        return null;
    }

    protected function tableToModel(string $table): string
    {
        return Str::studly(Str::singular($table));
    }

    protected function columnToMethod(string $column): string
    {
        return Str::camel(Str::replaceLast('_id', '', $column));
    }
}