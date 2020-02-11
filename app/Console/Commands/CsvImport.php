<?php

namespace App\Console\Commands;

use App\Http\Controllers\CsvSearch;
use Illuminate\Console\Command;

class CsvImport extends Command
{

    protected $signature = 'csv:search {csvFile} {columnIndex} {searchString}';

    protected $description = 'Csv Search';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(CsvSearch $csvImportController)
    {
        $csvFile = $this->argument('csvFile');
        $columnIndex = $this->argument('columnIndex');
        $searchString = $this->argument('searchString');
        $csvImportController->search($csvFile, $columnIndex, $searchString);
    }
}
