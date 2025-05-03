<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TopController extends Controller
{
    public function index(Request $request)
    {
        return view('top');
    }

    public function exportQR(Request $request) {
         $request->validate([
            'name' => 'required',
            'code' => 'required',
            'class' => 'required',
            'enterprise' => 'required',
            'phone' => 'required',
        ]);

        $employeeData = $request->only(['name', 'code', 'class', 'enterprise', 'phone']);
        $qrData = json_encode($employeeData); // Chuyển dữ liệu thành JSON để mã QR

        // Tạo mã QR
        $qrCodeImage = QrCode::generate($qrData);

        return response()->json(['qrCodeImage' => (string) $qrCodeImage]);

    }
}
