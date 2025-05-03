@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Danh sách nhân viên</h1>

    <!-- Form Lọc -->
    <form method="GET" class="mb-4">
        <div class="row">
            {{-- <div class="col">
                <input type="text" name="name" class="form-control" placeholder="Tên" value="{{ request('name') }}">
            </div>
            <div class="col">
                <input type="text" name="code" class="form-control" placeholder="Mã Sinh Viên" value="{{ request('code') }}">
            </div>
            <div class="col">
                <input type="text" name="phone" class="form-control" placeholder="Sđt" value="{{ request('phone') }}">
            </div> --}}

            <div class="col-12 col-md-3 mb-2">
                <input type="date" name="created_at" class="form-control" placeholder="Created At" value="{{ request('created_at') }}">
            </div>
            <div class="col-12 col-md-9">
                <button type="submit" class="btn btn-primary mb-2">Filter</button>
                <a href="{{ url('/home') }}" class="btn btn-secondary mb-2">Reset</a>
                @php
                    $query = http_build_query(request()->only(['name', 'code', 'class', 'enterprise', 'phone', 'created_at']));
                @endphp
                <a href="{{ url('/export?' . $query) }}" class="btn btn-success mb-2">Export Excel</a>
                <button class="btn btn-info mb-2" type="button" onclick="startScanner()">Quét QR</button>
            </div>
        </div>

        {{-- <div class="row mt-2">
            <div class="col">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ url('/home') }}" class="btn btn-secondary">Reset</a>
                @php
                    $query = http_build_query(request()->only(['name', 'code', 'class', 'enterprise', 'phone', 'created_at']));
                @endphp
                <a href="{{ url('/export?' . $query) }}" class="btn btn-success">Export Excel</a>
            </div>
        </div> --}}
    </form>

    <div class="row">
        <div class="col-md-12" style="text-align: center;margin-bottom: 20px;">
            <div id="reader" style="display: inline-block;"></div>
        </div>
	</div>

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
    {{ $employees->links() }}
</div>
<script>
    const html5QrCode = new Html5Qrcode("reader");

    function startScanner() {
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            onScanSuccess,
            error => {}
        );
    }

    function stopScanner() {
        html5QrCode.stop().catch(err => {
            console.error("Failed to stop QR scanner", err);
        });
    }

    function showSnackbar(message, callback) {
        const snackbar = document.getElementById("snackbar");
        snackbar.innerText = message;
        snackbar.className = "show";
        setTimeout(() => {
            snackbar.className = snackbar.className.replace("show", "");
            if (typeof callback === "function") callback();
        }, 3000);
    }

   function onScanSuccess(qrMessage) {
        stopScanner();
        fetch("{{ route('home.scan.qr') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ qr_data: qrMessage })
        })
        .then(response => response.json())
        .then(data => {
            showSnackbar(data.message, () => {
                startScanner(); 
            });
        })
        .catch(error => {
            showSnackbar(data.message, () => {
                startScanner();
            });
        });
    }
</script>
@endsection


