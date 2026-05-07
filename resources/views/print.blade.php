<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Berobat - {{ $patient->patient_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #166534;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            color: #4b5563;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
        }
        .patient-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .patient-info td {
            padding: 6px;
            vertical-align: top;
        }
        .patient-info strong {
            color: #4b5563;
        }
        .history-table {
            width: 100%;
            border-collapse: collapse;
        }
        .history-table th, .history-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            font-size: 12px;
            vertical-align: top;
        }
        .history-table th {
            background-color: #f3f4f6;
            text-align: left;
            color: #374151;
            font-weight: bold;
        }
        .history-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status {
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 4px;
        }
        .status-selesai {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-proses {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .price {
            white-space: nowrap;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>RS Cepat Sembuh</h1>
        <p>Jl. Kesehatan No. 123, Bandar Lampung | Telp: (0721) 123456</p>
    </div>

    <div class="title">LAPORAN RIWAYAT BEROBAT</div>

    <table class="patient-info">
        <tr>
            <td width="15%"><strong>Nama Pasien</strong></td>
            <td width="35%">: {{ $patient->patient_name }}</td>
            <td width="15%"><strong>Jenis Kelamin</strong></td>
            <td width="35%">: {{ $patient->gender }}</td>
        </tr>
        <tr>
            <td><strong>NIK</strong></td>
            <td>: {{ $patient->nik }}</td>
            <td><strong>Jenis Pasien</strong></td>
            <td>: {{ $patient->insurance_type }}</td>
        </tr>
        <tr>
            <td><strong>No. HP</strong></td>
            <td>: {{ $patient->phone_number }}</td>
            <td><strong>No. BPJS</strong></td>
            <td>: {{ $patient->bpjs_number ?? '-' }}</td>
        </tr>
    </table>

    <table class="history-table">
        <thead>
            <tr>
                <th width="12%">Tanggal</th>
                <th width="20%">Poliklinik & Dokter</th>
                <th width="18%">Keluhan</th>
                <th width="15%">Diagnosa</th>
                <th width="20%">Catatan Dokter</th>
                <th width="15%">Status & Biaya</th>
            </tr>
        </thead>
        <tbody>
            @foreach($treatments as $treatment)
            <tr>
                <td>{{ $treatment->updated_at->translatedFormat('d F Y') }}</td>
                <td>
                    <strong>{{ optional(optional($treatment->doctor)->room)->name ?? 'Belum Ditentukan' }}</strong><br>
                    <span style="color: #4b5563;">{{ optional($treatment->doctor)->name ?? '-' }}</span><br>
                    <span style="font-size: 10px; color: #6b7280;">{{ optional($treatment->doctor)->specialist ?? '' }}</span>
                </td>
                <td>{{ $treatment->complaint_description ?? '-' }}</td>
                <td>{{ $treatment->disease ?? '-' }}</td>
                <td>{{ $treatment->doctor_note ?? '-' }}</td>
                <td>
                    <span class="status {{ $treatment->status == 'Selesai' ? 'status-selesai' : 'status-proses' }}">
                        {{ $treatment->status }}
                    </span><br>
                    <span class="price">Rp {{ number_format($treatment->price, 0, ',', '.') }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
