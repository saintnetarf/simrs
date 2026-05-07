<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->status;
        $search = $request->search;
        $treatments = Treatment::with('doctor.room');

        if (Auth::user()->role == 'Dokter') {
            $status = $request->status ?? 'Menunggu Antrian Dokter';
            $treatments = $treatments->where('doctor_employee_id', Auth::user()->employee_id);
        } elseif (Auth::user()->role == 'Apoteker') {
            $status = $request->status ?? 'Menunggu Pembayaran';
        }

        $treatments = $treatments->status($status)->search($search)->orderBy('updated_at', 'desc')->paginate(10);

        return view('dashboard.treatments.index', [
            'title' => 'Data Pengobatan & Antrian',
            'subtitle' => 'Menampilkan semua data pengobatan & antrian',
            'treatments' => $treatments,
            'statusSelected' => $status
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.treatments.create', [
            'title' => 'Tambah Data Pengobatan & Antrian',
            'subtitle' => 'Menambahkan data pengobatan & antrian',
            'doctors' => User::doctor()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:20',
            'patient_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'phone_number' => 'required|string|max:15',
            'emergency_phone_number' => 'required|string|max:15|different:phone_number',
            'address' => 'required|string',
            'insurance_type' => 'required|in:Umum,BPJS Kesehatan,BPJS Ketenagakerjaan,Asuransi Swasta',
            'bpjs_number' => 'nullable|required_if:insurance_type,BPJS Kesehatan,BPJS Ketenagakerjaan|string|max:20',
            'complaint_description' => 'required|string',
            'doctor_employee_id' => 'required|exists:users,employee_id',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.max' => 'NIK maksimal 20 karakter.',
            'patient_name.required' => 'Nama lengkap wajib diisi.',
            'patient_name.max' => 'Nama maksimal 255 karakter.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.date' => 'Format tanggal lahir tidak valid.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'phone_number.required' => 'Nomor HP / WhatsApp wajib diisi.',
            'phone_number.max' => 'Nomor HP maksimal 15 karakter.',
            'emergency_phone_number.required' => 'Nomor HP Darurat wajib diisi.',
            'emergency_phone_number.max' => 'Nomor HP Darurat maksimal 15 karakter.',
            'emergency_phone_number.different' => 'Nomor HP Darurat tidak boleh sama dengan Nomor HP / WhatsApp Anda.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'insurance_type.required' => 'Jenis pasien / asuransi wajib dipilih.',
            'insurance_type.in' => 'Pilihan jenis pasien tidak valid.',
            'bpjs_number.required_if' => 'Nomor BPJS wajib diisi jika menggunakan asuransi BPJS.',
            'bpjs_number.max' => 'Nomor BPJS maksimal 20 karakter.',
            'complaint_description.required' => 'Keluhan wajib diisi.',
            'doctor_employee_id.required' => 'Dokter wajib diisi.',
            'doctor_employee_id.exists' => 'Dokter yang dipilih tidak valid.',
        ]);

        Treatment::create([
            'code' => (string) Str::orderedUuid(),
            'nik' => $validated['nik'],
            'patient_name' => $validated['patient_name'],
            'birth_date' => $validated['birth_date'],
            'gender' => $validated['gender'],
            'phone_number' => $validated['phone_number'],
            'emergency_phone_number' => $validated['emergency_phone_number'],
            'address' => $validated['address'],
            'insurance_type' => $validated['insurance_type'],
            'bpjs_number' => $validated['bpjs_number'] ?? null,
            'complaint_description' => $validated['complaint_description'],
            'doctor_employee_id' => $validated['doctor_employee_id'],
            'status' => 'Menunggu Antrian Dokter',
        ]);

        return to_route('dashboard.treatments.index')->with('success', 'Data pengobatan & antrian berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Treatment $treatment)
    {
        return view('dashboard.treatments.show', [
            'title' => 'Detail Data Pengobatan & Antrian',
            'subtitle' => 'Menampilkan detail data pengobatan & antrian',
            'treatment' => $treatment->load('doctor.room')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Treatment $treatment)
    {
        return view('dashboard.treatments.edit', [
            'title' => 'Edit Data Pengobatan & Antrian',
            'subtitle' => 'Mengedit data pengobatan & antrian',
            'doctors' => User::doctor()->get(),
            'treatment' => $treatment
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:20',
            'patient_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'phone_number' => 'required|string|max:15',
            'emergency_phone_number' => 'required|string|max:15|different:phone_number',
            'address' => 'required|string',
            'insurance_type' => 'required|in:Umum,BPJS Kesehatan,BPJS Ketenagakerjaan,Asuransi Swasta',
            'bpjs_number' => 'nullable|required_if:insurance_type,BPJS Kesehatan,BPJS Ketenagakerjaan|string|max:20',
            'complaint_description' => 'required|string',
            'doctor_employee_id' => 'required|exists:users,employee_id',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.max' => 'NIK maksimal 20 karakter.',
            'patient_name.required' => 'Nama lengkap wajib diisi.',
            'patient_name.max' => 'Nama maksimal 255 karakter.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.date' => 'Format tanggal lahir tidak valid.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'phone_number.required' => 'Nomor HP / WhatsApp wajib diisi.',
            'phone_number.max' => 'Nomor HP maksimal 15 karakter.',
            'emergency_phone_number.required' => 'Nomor HP Darurat wajib diisi.',
            'emergency_phone_number.max' => 'Nomor HP Darurat maksimal 15 karakter.',
            'emergency_phone_number.different' => 'Nomor HP Darurat tidak boleh sama dengan Nomor HP / WhatsApp Anda.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'insurance_type.required' => 'Jenis pasien / asuransi wajib dipilih.',
            'insurance_type.in' => 'Pilihan jenis pasien tidak valid.',
            'bpjs_number.required_if' => 'Nomor BPJS wajib diisi jika menggunakan asuransi BPJS.',
            'bpjs_number.max' => 'Nomor BPJS maksimal 20 karakter.',
            'complaint_description.required' => 'Keluhan wajib diisi.',
            'doctor_employee_id.required' => 'Dokter wajib diisi.',
            'doctor_employee_id.exists' => 'Dokter yang dipilih tidak valid.',
        ]);

        $treatment->update([
            'nik' => $validated['nik'],
            'patient_name' => $validated['patient_name'],
            'birth_date' => $validated['birth_date'],
            'gender' => $validated['gender'],
            'phone_number' => $validated['phone_number'],
            'emergency_phone_number' => $validated['emergency_phone_number'],
            'address' => $validated['address'],
            'insurance_type' => $validated['insurance_type'],
            'bpjs_number' => $validated['bpjs_number'] ?? null,
            'complaint_description' => $validated['complaint_description'],
            'doctor_employee_id' => $validated['doctor_employee_id'],
        ]);

        return redirect()->route('dashboard.treatments.index')->with('success', 'Data berhasil diupdate.');
    }

    public function doctorAction(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'disease' => 'required|string|max:20',
            'doctor_note' => 'required|string'
        ], [
            'disease.required' => 'Diagnosa wajib diisi.',
            'doctor_note.required' => 'Catatan Dokter wajib diisi.',
        ]);

        $treatment->update([
            'disease' => $validated['disease'],
            'doctor_note' => $validated['doctor_note'],
            'status' => 'Menunggu Pembayaran'
        ]);

        return redirect()->route('dashboard.treatments.index')->with('success', 'Data berhasil diupdate.');
    }

    public function apotekerAction(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
        ], [
            'price.required' => 'Harga wajib diisi.',
            'price.min' => 'Harga minimal 0.',
        ]);

        $treatment->update([
            'price' => $validated['price'],
            'status' => 'Selesai'
        ]);

        return redirect()->route('dashboard.treatments.index')->with('success', 'Data berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Treatment $treatment)
    {
        if ($treatment->status == 'Menunggu Antrian Dokter' || $treatment->status == 'Sudah Mendaftar') {
            $treatment->delete();
            return redirect()->route('dashboard.treatments.index')->with('success', 'Data berhasil dihapus.');
        }

        return redirect()->route('dashboard.treatments.index')->with('error', 'Data tidak dapat dihapus karena sudah ada tindakan.');

    }

    public function booking(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:20',
            'patient_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'phone_number' => 'required|string|max:15',
            'emergency_phone_number' => 'required|string|max:15|different:phone_number',
            'address' => 'required|string',
            'insurance_type' => 'required|in:Umum,BPJS Kesehatan,BPJS Ketenagakerjaan,Asuransi Swasta',
            'bpjs_number' => 'nullable|required_if:insurance_type,BPJS Kesehatan,BPJS Ketenagakerjaan|string|max:20',
            'complaint_description' => 'required|string',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.max' => 'NIK maksimal 20 karakter.',
            'patient_name.required' => 'Nama lengkap wajib diisi.',
            'patient_name.max' => 'Nama maksimal 255 karakter.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.date' => 'Format tanggal lahir tidak valid.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'phone_number.required' => 'Nomor HP / WhatsApp wajib diisi.',
            'phone_number.max' => 'Nomor HP maksimal 15 karakter.',
            'emergency_phone_number.required' => 'Nomor HP Darurat wajib diisi.',
            'emergency_phone_number.max' => 'Nomor HP Darurat maksimal 15 karakter.',
            'emergency_phone_number.different' => 'Nomor HP Darurat tidak boleh sama dengan Nomor HP / WhatsApp Anda.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'insurance_type.required' => 'Jenis pasien / asuransi wajib dipilih.',
            'insurance_type.in' => 'Pilihan jenis pasien tidak valid.',
            'bpjs_number.required_if' => 'Nomor BPJS wajib diisi jika menggunakan asuransi BPJS.',
            'bpjs_number.max' => 'Nomor BPJS maksimal 20 karakter.',
            'complaint_description.required' => 'Keluhan wajib diisi.',
        ]);

        try {
            $treatment = Treatment::create([
                'code' => (string) Str::orderedUuid(),
                'nik' => $validated['nik'],
                'patient_name' => $validated['patient_name'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'phone_number' => $validated['phone_number'],
                'emergency_phone_number' => $validated['emergency_phone_number'],
                'address' => $validated['address'],
                'insurance_type' => $validated['insurance_type'],
                'bpjs_number' => $validated['bpjs_number'] ?? null,
                'complaint_description' => $validated['complaint_description'],
            ]);

            return view('after-booking', [
                'isSuccess' => true,
                'treatment' => $treatment
            ]);

        } catch (Exception $e) {
            return view('after-booking', [
                'isSuccess' => false,
                'reason' => 'Gagal Menyimpan Data',
                'description' => 'Sistem sedang mengalami gangguan, silakan coba beberapa saat lagi atau hubungi admin.',
            ]);
        }
    }

    public function history(Request $request)
    {
        $nik = $request->nik;
        $treatments = null;

        if ($nik) {
            $treatments = Treatment::with('doctor.room')
                ->where('nik', $nik)
                ->orderBy('updated_at', 'desc')
                ->paginate(10)
                ->withQueryString();
        }

        return view('history', [
            'nik' => $nik,
            'treatments' => $treatments
        ]);
    }

    public function print(Request $request)
    {
        $nik = $request->nik;

        if (!$nik) {
            return redirect()->back()->with('error', 'NIK tidak valid.');
        }

        $treatments = Treatment::with('doctor.room')
            ->where('nik', $nik)
            ->orderBy('updated_at', 'desc')
            ->get();

        if ($treatments->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $pdf = Pdf::loadView('print', [
            'treatments' => $treatments,
            'patient' => $treatments->first()
        ]);

        return $pdf->stream('Riwayat_Berobat_'.$nik.'.pdf');
    }

}
