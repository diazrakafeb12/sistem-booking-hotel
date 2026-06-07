<?php
namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Booking::with(['tamu', 'kamar.tipeKamar', 'pembayaran'])
            ->latest()->get()->map(function($b) {
                return [
                    'Kode Booking'  => $b->kode_booking,
                    'Nama Tamu'     => $b->tamu->nama_lengkap ?? '-',
                    'No. HP'        => $b->tamu->no_hp ?? '-',
                    'Kamar'         => $b->kamar->nomor_kamar ?? '-',
                    'Tipe'          => $b->kamar->tipeKamar->nama_tipe ?? '-',
                    'Check-In'      => $b->tgl_checkin,
                    'Check-Out'     => $b->tgl_checkout,
                    'Malam'         => $b->jumlah_malam,
                    'Total Biaya'   => $b->total_biaya,
                    'Status'        => $b->status,
                    'Pembayaran'    => $b->pembayaran ? $b->pembayaran->status : 'belum',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Booking', 'Nama Tamu', 'No. HP', 'Kamar',
            'Tipe', 'Check-In', 'Check-Out', 'Malam',
            'Total Biaya', 'Status', 'Pembayaran'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                  'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0D2137']]],
        ];
    }
}