<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class EmployeesExport implements FromCollection, WithHeadings
{
    protected $employees;

    public function __construct(EloquentCollection $employees) 
    {
        $this->employees = $employees;
    }

    public function collection()
    {
        return $this->employees;
    }

    public function headings(): array
    {
        return [
            'Tên',
            'Mã Sinh Viên',
            'Lớp',
            'Xí Nghiệp',
            'Sđt',
        ];
    }
}