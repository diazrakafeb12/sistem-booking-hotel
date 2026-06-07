<x-app-layout>
    @section('title', 'Data Pembayaran')

    <div class="card">
        <div class="card-header">
            <h3>💳 Daftar Pembayaran</h3>
            <a href="{{ route('pembayaran.create') }}" class="btn btn-primary">+ Input Pembayaran</a>
        </div>
        <div style="padding:0">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Booking</th>
                        <th>Tamu</th>
                        <th>Jumlah Bayar</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Tgl Bayar</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong style="color:#2e86de">{{ $p->booking->kode_booking ?? '-' }}</strong></td>
                        <td>{{ $p->booking->tamu->nama_lengkap ?? '-' }}</td>
                        <td style="color:#27ae60; font-weight:700">
                            Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($p->metode == 'cash')
                                💵 Cash
                            @elseif($p->metode == 'transfer')
                                🏦 Transfer
                            @else
                                💳 Kartu
                            @endif
                        </td>
                        <td>
                            @if($p->status == 'lunas')
                                <span class="badge badge-success">✅ Lunas</span>
                            @elseif($p->status == 'dp')
                                <span class="badge badge-warning">⚠️ DP</span>
                            @else
                                <span class="badge badge-danger">❌ Belum Lunas</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($p->tgl_bayar)->format('d/m/Y H:i') }}</td>
                        <td style="text-align:center">
                            <a href="{{ route('pembayaran.show', $p->id_pembayaran) }}"
                               class="btn btn-primary btn-sm">🧾 Invoice</a>
                            <form action="{{ route('pembayaran.destroy', $p->id_pembayaran) }}"
                                  method="POST" style="display:inline"
                                  onsubmit="return confirm('Yakin hapus data pembayaran ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:40px; color:#aaa">
                            Belum ada data pembayaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>