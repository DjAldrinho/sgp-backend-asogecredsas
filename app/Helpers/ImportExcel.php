<?php


namespace App\Helpers;


use App\Models\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportExcel implements ToCollection, WithStartRow
{

    private $rows = 0;

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            ++$this->rows;


            Client::create([
                'document_number' => isset($row[0]) ? $this->cleanInput($row[0]) : 0,
                'name' => isset($row[1]) ? $this->cleanInput($row[1]) : "NA",
                'position' => isset($row[2]) ? $this->cleanInput($row[2]) : "NA",
                'salary' => isset($row[3]) ? $this->cleanInput($row[3]) : 0,
                'start_date' => $this->convertDateExcelToPHP($row[4]),
                'bonding' => isset($row[5]) ? $this->cleanInput($row[5]) : "NA",
                'client_type' => json_encode(['debtor'])
            ]);
        }
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    protected function convertDateExcelToPHP($value)
    {
        if ($value) {
            $milliseconds = ($value - (25567 + 2)) * 86400 * 1000;
            $seconds = $milliseconds / 1000;
            return date("Y-m-d", $seconds);
        }

        return date('Y-m-d');
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    protected function cleanInput($input)
    {

        $search = array(
            '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
        );

        return preg_replace($search, '', $input);
    }

}
