<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
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
            'Ngày'
        ];
    }

    public function map($employees): array
    {
        return [
            $employees->name,
            $employees->code,
            $employees->class,
            $employees->enterprise,
            $employees->phone,
            $employees->created_at->format('d/m/Y'),
        ];
    }
}