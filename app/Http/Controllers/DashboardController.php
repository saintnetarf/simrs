<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // 1. Data Summary Cards
        $totalPasienQuery = Treatment::query();
        $pendapatanQuery = Treatment::query();
        $menungguQuery = Treatment::where('status', 'Menunggu Antrian Dokter');
        $selesaiQuery = Treatment::whereIn('status', ['Selesai Ditangani', 'Menunggu Pembayaran', 'Selesai']);

        // Jika dokter, hanya hitung data pasien mereka
        if ($user && $user->role === 'Dokter') {
            $totalPasienQuery->where('doctor_employee_id', $user->employee_id);
            $pendapatanQuery->where('doctor_employee_id', $user->employee_id);
            $menungguQuery->where('doctor_employee_id', $user->employee_id);
            $selesaiQuery->where('doctor_employee_id', $user->employee_id);
        }

        $summary = [
            'total_pasien' => $totalPasienQuery->count(),
            'pendapatan' => $pendapatanQuery->sum('price'),
            'menunggu' => $menungguQuery->count(),
            'selesai' => $selesaiQuery->count(),
        ];

        // 2. Data Grafik Kunjungan (7 hari terakhir)
        $chartKunjunganQuery = Treatment::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total')
        )
        ->where('created_at', '>=', now()->subDays(6)->startOfDay())
        ->groupBy('date')
        ->orderBy('date');

        if ($user && $user->role === 'Dokter') {
            $chartKunjunganQuery->where('doctor_employee_id', $user->employee_id);
        }

        $kunjunganData = $chartKunjunganQuery->get()->keyBy('date');

        $labelsKunjungan = [];
        $dataKunjungan = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labelsKunjungan[] = now()->subDays($i)->translatedFormat('d M');
            $dataKunjungan[] = isset($kunjunganData[$date]) ? $kunjunganData[$date]->total : 0;
        }

        // 3. Data Grafik Tipe Pasien
        $chartTipeQuery = Treatment::select(
            'insurance_type',
            DB::raw('count(*) as total')
        )->groupBy('insurance_type');

        if ($user && $user->role === 'Dokter') {
            $chartTipeQuery->where('doctor_employee_id', $user->employee_id);
        }

        $tipePasien = $chartTipeQuery->get();
        $labelsTipe = $tipePasien->pluck('insurance_type')->toArray();
        $dataTipe = $tipePasien->pluck('total')->toArray();

        return view('dashboard.index', [
            'title' => 'Dashboard',
            'subtitle' => 'Selamat datang di dashboard RS Cepat Sembuh, ' . ($user ? $user->name : 'Admin'),
            'summary' => $summary,
            'labelsKunjungan' => json_encode($labelsKunjungan),
            'dataKunjungan' => json_encode($dataKunjungan),
            'labelsTipe' => json_encode($labelsTipe),
            'dataTipe' => json_encode($dataTipe),
        ]);
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'employee_id' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard.index');
        }

        return redirect()->route('login')->with('error', 'Username atau Password salah');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
