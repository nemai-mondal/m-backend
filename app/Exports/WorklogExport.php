<?php

namespace App\Exports;

use App\Http\Resources\WorkLogCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class WorklogExport implements FromCollection, WithHeadings
{
    protected $worklogs;

    public function __construct(LengthAwarePaginator $worklogs)
    {
        $this->worklogs = $worklogs;
    }

    public function collection()
    {
        return new WorkLogCollection($this->worklogs);
    }

    public function headings() : array {

        // Allfields will be added atfer finalizing the response;

        return [
            'id',
            'date',
            'task_id',
            'created at',
            'description',
            'client',
            'project',
            'employee',
            'activity',
            'time spent',
        ];
    }
}
