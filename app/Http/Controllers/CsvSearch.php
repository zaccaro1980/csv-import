<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use OutOfBoundsException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

class CsvSearch extends Controller
{

    private $_output;

    /**
     * class construct
     * 
     * @param ConsoleOutput $output
     * @access public
     */
    public function __construct(ConsoleOutput $output) {
        $this->_output = $output;
    }

    /**
     * main class method
     * 
     * @param string    $csvFile the csv file to search for (it must be in storage/app directory)
     * @param int       $columnIndex the column index
     * @param string    $searchString  the search string to look for
     * @return void
     * @access public
     */
    public function search(string $csvFile, int $columnIndex, string $searchString) : void {

        $this->_output->writeln(["", "<info>Searching for '$searchString' into '$csvFile' on column index '$columnIndex'</info>", ""]);
        
        try {
            $this->checkFileExists($csvFile);
            $recordFound = $this->searchByColumnNumberAndString($csvFile, $columnIndex, $searchString);
        } catch(OutOfBoundsException | Exception $e) {
            $this->_output->writeln(["<error>{$e->getMessage()}</error>",""]);
            return;
        }

        $this->printResult($recordFound);
        
    }

    /**
     * check if the csv file exists
     * 
     * @param string $csvFile the csv file to search into (it must be in storage/app directory)
     * @return void
     * @access private
     */
    private function checkFileExists(string $csvFile) : void {
        if ( ! Storage::disk('local')->exists($csvFile)) {
            throw new Exception("$csvFile not found");
        }
    }

    /**
     * search for a specific keyword, into the csv file on the index passed
     * 
     * @param string    $csvFile the csv file to search into (it must be in storage/app directory)
     * @param int       $columnIndex the column index
     * @param string    $searchString the search string to look for
     * @return array    the array of the records found
     * @access private
     */
    private function searchByColumnNumberAndString(string $csvFile, int $columnIndex, string $searchString) : array {

        $reader = Reader::createFromString(Storage::disk('local')->get($csvFile), 'r');
        $records = $reader->getRecords();

        $recordsFound = [];

        foreach ($records as $record) {

            if( ! isset($record[$columnIndex])) {
                throw new OutOfBoundsException("<error>Column with index $columnIndex doesn't exists</error>");
            }

            if ($record[$columnIndex] == $searchString) {
                array_push($recordsFound, $record);
            }

        }

        return $recordsFound;
    }

    /**
     * print the search results
     * 
     * @param array $recordFound
     * @return void
     * @access private
     */
    private function printResult(array $recordsFound) : void {
        $table = new Table($this->_output);
        if (count($recordsFound) == 0) {
            $table->setRows([['no records found']]);
        } else {
            $table->setHeaderTitle('Search results');
            $table->setHeaders(['0 - ID', '1 - Name', '2 - Surname', '3 - Birthday']);
            $table->setRows($recordsFound);
        }
        $table->render();
        $this->_output->writeln("");
    }
    
}