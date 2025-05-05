<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tên</th>
            <th>Mã Sinh Viên</th>
            <th>Lớp</th>
            <th>Xí Nghiệp</th>
            <th>Sđt</th>
            <th>Ngày</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $employee)
            <tr>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->code }}</td>
                <td>{{ $employee->class }}</td>
                <td>{{ $employee->enterprise }}</td>
                <td>{{ $employee->phone }}</td>
                <td>{{ $employee->created_at->format('Y/m/d') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Phân trang -->
{{ $employees->withPath('/home')->links() }}
