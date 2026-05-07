<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $specialist = $request->specialist;
        $doctors = User::where('role', 'Dokter');
        $specialists = (clone $doctors)->distinct()->pluck('specialist')->toArray();

        $doctors = $doctors->search($search)->specialist($specialist)->latest()->get();

        return view('dashboard.doctors.index', [
            'title' => 'Data Dokter',
            'subtitle' => 'Berikut adalah data dokter yang terdaftar di RS Cepat Sembuh',
            'specialists' => $specialists,
            'doctors' => $doctors
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Room::all();
        return view('dashboard.doctors.create', [
            'title' => 'Tambah Dokter',
            'subtitle' => 'Silahkan tambahkan data dokter yang terdaftar di RS Cepat Sembuh',
            'rooms' => $rooms
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string|max:20|unique:users,employee_id',
            'name' => 'required|string|max:200',
            'specialist' => 'required|string|max:200',
            'phone_number' => 'required|string|max:20',
            'room_code' => 'required|exists:rooms,code',
        ], [
            'employee_id.required' => 'ID Dokter wajib diisi.',
            'employee_id.unique' => 'ID Dokter sudah terdaftar.',
            'name.required' => 'Nama Dokter wajib diisi.',
            'specialist.required' => 'Spesialis wajib diisi.',
            'phone_number.required' => 'Nomor Telepon wajib diisi.',
            'room_code.required' => 'Ruang wajib diisi.',
        ]);

        User::create([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'specialist' => $request->specialist,
            'phone_number' => $request->phone_number,
            'room_code' => $request->room_code,
            'role' => 'Dokter',
            'password' => bcrypt($request->employee_id),
        ]);

        return redirect()->route('dashboard.doctors.index')->with('success', 'Dokter berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $employee_id)
    {
        $doctor = User::with('room')->where('employee_id', $employee_id)->firstOrFail();
        $statusSelected = $request->status;
        $search = $request->search;
        $treatments = Treatment::with('doctor.room')->where('doctor_employee_id', $employee_id)->status($statusSelected)->search($search)->orderBy('updated_at', 'desc')->paginate(10);
        return view('dashboard.doctors.show', [
            'title' => 'Detail Dokter',
            'subtitle' => 'Berikut adalah detail data dokter yang terdaftar di RS Cepat Sembuh',
            'doctor' => $doctor,
            'treatments' => $treatments,
            'statusSelected' => $statusSelected
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($employee_id)
    {
        $doctor = User::where('employee_id', $employee_id)->firstOrFail();
        $rooms = Room::all();
        return view('dashboard.doctors.edit', [
            'title' => 'Edit Dokter',
            'subtitle' => 'Silahkan edit data dokter yang terdaftar di RS Cepat Sembuh',
            'rooms' => $rooms,
            'doctor' => $doctor
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $employee_id)
    {
        $request->validate([
            'employee_id' => 'required|string|max:20|unique:users,employee_id,' . $employee_id . ',employee_id',
            'name' => 'required|string|max:200',
            'specialist' => 'required|string|max:200',
            'phone_number' => 'required|string|max:20',
            'room_code' => 'required|exists:rooms,code',
        ], [
            'employee_id.required' => 'ID Dokter wajib diisi.',
            'employee_id.unique' => 'ID Dokter sudah terdaftar.',
            'name.required' => 'Nama Dokter wajib diisi.',
            'specialist.required' => 'Spesialis wajib diisi.',
            'phone_number.required' => 'Nomor Telepon wajib diisi.',
            'room_code.required' => 'Ruang wajib diisi.',
        ]);

        $doctor = User::where('employee_id', $employee_id)->first();
        $doctor->update([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'specialist' => $request->specialist,
            'phone_number' => $request->phone_number,
            'room_code' => $request->room_code,
        ]);

        return redirect()->route('dashboard.doctors.index')->with('success', 'Dokter berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($employee_id)
    {
        $user = User::where('employee_id', $employee_id)->first();
        if ($user->role === 'Admin') {
            return redirect()->route('dashboard.doctors.index')->with('error', 'Dokter tidak dapat dihapus');
        }
        $user->delete();
        return redirect()->route('dashboard.doctors.index')->with('success', 'Dokter berhasil dihapus');
    }
}
