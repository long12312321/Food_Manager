<?php

namespace App\Http\Controllers;

use App\Models\QR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TopController extends Controller
{
    public function index(Request $request)
    {
        return view('top');
    }

    public function exportQR(Request $request) {
        try {
            $request->validate([
                'name' => 'required',
                'code' => 'required',
                'class' => 'required',
                'enterprise' => 'required',
                'phone' => 'required',
            ]);

            $employeeData = $request->only(['name', 'code', 'class', 'enterprise', 'phone']);
            $qrData = json_encode($employeeData); 
            $hashId = hash('sha256', $qrData);
            
            QR::create(array_merge($employeeData, ['hash_id' => $hashId]));
            // Tạo mã QR
            $qrCodeImage = QrCode::size(300)->generate($hashId);

            return response()->json([
                'qrCodeImage' => (string) $qrCodeImage,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => '❌ Server error'
            ], 500);
        }

    }
}
