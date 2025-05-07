<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;
use App\Models\QR;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $employees = $this->filterEmployees($request)->paginate(10);
        return view('home', compact('employees'))->render();
    }

    public function getEmployees(Request $request)
    {
        $employees = $this->filterEmployees($request)->paginate(10)->appends(request()->query());
        return view('components.employees-table', compact('employees'))->render();
    }

    public function exportExcel(Request $request)
    {
        $employees = $this->filterEmployees($request)->get();
        return Excel::download(new EmployeesExport($employees), 'employees.xlsx');
    }

    public function scanQR(Request $request) {
      
        try {
            $qr = QR::where('hash_id', $request->input('qr_data'))->first();
            $exists = Employee::where('code', $qr['code'])
                    ->whereDate('created_at', Carbon::today())
                    ->exists();
            if ($exists) {
                return response()->json([
                    'message' => '❌ Nhân viên này đã được thêm hôm nay!'
                ], 500);
            }
            Employee::create([
                'name' => $qr['name'],
                'code' => $qr['code'],
                'class' => $qr['class'],
                'enterprise' => $qr['enterprise'],
                'phone' => $qr['phone'],
            ]);

            return response()->json([
                'message' => '✅ Đăng kí thành công!',
            ], 200);

        } catch (\Exception $e) {
            
            return response()->json([
                'message' => '❌ Server error',
            ], 500);
        }
    }

    /**
     * Apply filters to the Employee query.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function filterEmployees(Request $request)
    {
        $query = Employee::query();

        $filters = ['name', 'code', 'created_at', 'phone'];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                if ($filter === 'created_at') {
                    // Lọc theo ngày
                    $query->whereDate('created_at', $request->created_at);
                } else {
                    $query->where($filter, 'like', '%' . $request->$filter . '%');
                }
            }
        }

        return $query->orderBy('created_at', 'desc');
    }
}
