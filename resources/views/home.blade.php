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
                <button type="submit" class="btn btn-primary mb-2">Tìm kiếm</button>
                <a href="{{ url('/home') }}" class="btn btn-secondary mb-2">Hoàn tác</a>
                @php
                    $query = http_build_query(request()->only(['name', 'code', 'class', 'enterprise', 'phone', 'created_at']));
                @endphp
                <a href="{{ url('/export?' . $query) }}" class="btn btn-success mb-2">Xuất excel</a>
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

    <div id="employees-table">
        @include('components.employees-table', ['employees' => $employees])
    </div>
</div>
<script>
    function showLoading() {
        const loading = document.getElementById("loading");
        loading.style.visibility = "visible";
        loading.style.opacity = "1";
    }

    function hideLoading() {
        const loading = document.getElementById("loading");
        loading.style.opacity = "0";
        setTimeout(() => {
            loading.style.visibility = "hidden";
        }, 300); // trùng với transition
    }
    const html5QrCode = new Html5Qrcode("reader");

    function startScanner() {
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            onScanSuccess,
            error => {}
        )
        .finally(() => {
            hideLoading();
        });

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
        showLoading();
        fetch("{{ route('home.scan.qr') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ qr_data: qrMessage })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message);
                });
            }
            return response.json();
        })
        .then(data => {
             showSnackbar(data.message, async() => {
                reloadEmployeesTable()
                startScanner(); 
            });
        })
        .catch(error => {
            hideLoading();
            showSnackbar(error.message, () => {
                stopScanner();
            });
        })
    }

    function reloadEmployeesTable() {
        const params = new URLSearchParams(window.location.search);
        fetch("/employees-table?" + params.toString())
            .then(response => response.text())
            .then(html => {
                document.getElementById("employees-table").innerHTML = html;
            });
    }
</script>
@endsection


