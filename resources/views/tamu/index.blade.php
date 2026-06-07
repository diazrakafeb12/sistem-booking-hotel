<x-app-layout>
    @section('title', 'Data Tamu')

    {{-- Header Stats --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:20px">
        @php
            $totalTamu     = $bookings->pluck('id_tamu')->unique()->count();
            $totalCheckin  = $bookings->where('status','checkin')->count();
            $totalCheckout = $bookings->where('status','checkout')->count();
            $totalConfirmed= $bookings->where('status','confirmed')->count();
        @endphp
        <div style="background:#fff; border-radius:10px; padding:14px 18px; box-shadow:0 1px 4px rgba(0,0,0,0.06); border-top:3px solid #2e86de; display:flex; align-items:center; gap:12px">
            <div style="width:40px; height:40px; background:#eef4fb; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px">👤</div>
            <div>
                <div style="font-size:9px; color:#888; font-weight:700; text-transform:uppercase; letter-spacing:1px">Total Tamu</div>
                <div style="font-size:24px; font-weight:800; color:#0d2137; line-height:1.2">{{ $totalTamu }}</div>
            </div>
        </div>
        <div style="background:#fff; border-radius:10px; padding:14px 18px; box-shadow:0 1px 4px rgba(0,0,0,0.06); border-top:3px solid #27ae60; display:flex; align-items:center; gap:12px">
            <div style="width:40px; height:40px; background:#e8f8f1; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px">🏨</div>
            <div>
                <div style="font-size:9px; color:#888; font-weight:700; text-transform:uppercase; letter-spacing:1px">Sedang Check-In</div>
                <div style="font-size:24px; font-weight:800; color:#0d2137; line-height:1.2">{{ $totalCheckin }}</div>
            </div>
        </div>
        <div style="background:#fff; border-radius:10px; padding:14px 18px; box-shadow:0 1px 4px rgba(0,0,0,0.06); border-top:3px solid #f39c12; display:flex; align-items:center; gap:12px">
            <div style="width:40px; height:40px; background:#fef9e7; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px">⏳</div>
            <div>
                <div style="font-size:9px; color:#888; font-weight:700; text-transform:uppercase; letter-spacing:1px">Menunggu</div>
                <div style="font-size:24px; font-weight:800; color:#0d2137; line-height:1.2">{{ $totalConfirmed }}</div>
            </div>
        </div>
        <div style="background:#fff; border-radius:10px; padding:14px 18px; box-shadow:0 1px 4px rgba(0,0,0,0.06); border-top:3px solid #95a5a6; display:flex; align-items:center; gap:12px">
            <div style="width:40px; height:40px; background:#eaecee; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px">🚪</div>
            <div>
                <div style="font-size:9px; color:#888; font-weight:700; text-transform:uppercase; letter-spacing:1px">Check-Out</div>
                <div style="font-size:24px; font-weight:800; color:#0d2137; line-height:1.2">{{ $totalCheckout }}</div>
            </div>
        </div>
    </div>

    {{-- Filter & Sortir --}}
    <div class="card" style="margin-bottom:16px">
        <div class="card-body" style="padding:14px 18px">
            <div style="display:flex; gap:12px; align-items:end; flex-wrap:wrap">

                {{-- Search --}}
                <div style="flex:2; min-width:200px">
                    <label style="font-size:11px; font-weight:700; color:#555; display:block; margin-bottom:5px">🔍 Cari Tamu / Kode</label>
                    <input type="text" id="searchInput" placeholder="Nama tamu atau kode booking..."
                           class="form-control" style="font-size:12px">
                </div>

                {{-- Filter Status --}}
                <div style="flex:1; min-width:150px">
                    <label style="font-size:11px; font-weight:700; color:#555; display:block; margin-bottom:5px">📌 Status</label>
                    <select id="filterStatus" class="form-control" style="font-size:12px">
                        <option value="">Semua Status</option>
                        <option value="confirmed">✅ Confirmed</option>
                        <option value="checkin">🏨 Check-In</option>
                        <option value="checkout">🚪 Check-Out</option>
                        <option value="pending">⏳ Pending</option>
                        <option value="cancelled">❌ Cancelled</option>
                    </select>
                </div>

                {{-- Filter Pembayaran --}}
                <div style="flex:1; min-width:150px">
                    <label style="font-size:11px; font-weight:700; color:#555; display:block; margin-bottom:5px">💳 Pembayaran</label>
                    <select id="filterBayar" class="form-control" style="font-size:12px">
                        <option value="">Semua</option>
                        <option value="lunas">✅ Lunas</option>
                        <option value="dp">⚠️ DP</option>
                        <option value="belum">❌ Belum Bayar</option>
                    </select>
                </div>

                {{-- Sortir --}}
                <div style="flex:1; min-width:150px">
                    <label style="font-size:11px; font-weight:700; color:#555; display:block; margin-bottom:5px">↕️ Urutkan</label>
                    <select id="sortBy" class="form-control" style="font-size:12px">
                        <option value="terbaru">Terbaru</option>
                        <option value="terlama">Terlama</option>
                        <option value="nama_az">Nama A-Z</option>
                        <option value="nama_za">Nama Z-A</option>
                        <option value="total_asc">Total ↑</option>
                        <option value="total_desc">Total ↓</option>
                        <option value="checkin">Check-In Terdekat</option>
                    </select>
                </div>

                {{-- Reset --}}
                <div>
                    <label style="font-size:11px; font-weight:700; color:#555; display:block; margin-bottom:5px">&nbsp;</label>
                    <button onclick="resetFilter()" class="btn btn-secondary btn-sm" style="height:38px; padding:0 16px">
                        🔄 Reset
                    </button>
                </div>

            </div>

            {{-- Info hasil filter --}}
            <div id="filterInfo" style="margin-top:10px; font-size:12px; color:#888; display:none">
                Menampilkan <strong id="jumlahTampil">0</strong> dari <strong>{{ $bookings->count() }}</strong> data
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card">
        <div class="card-header">
            <h3>👤 Riwayat Tamu & Booking</h3>
            <div style="font-size:12px; color:#aaa">Total {{ $bookings->count() }} transaksi</div>
        </div>
        <div style="overflow-x:auto">
            <table style="font-size:12px" id="tabelTamu">
                <thead>
                    <tr>
                        <th style="width:40px; padding:10px 12px">#</th>
                        <th style="padding:10px 12px; cursor:pointer" onclick="sortTable('nama')">
                            Tamu <span style="color:#7fb3d3">↕</span>
                        </th>
                        <th style="padding:10px 12px">Kode Booking</th>
                        <th style="padding:10px 12px; cursor:pointer" onclick="sortTable('kamar')">
                            Kamar <span style="color:#7fb3d3">↕</span>
                        </th>
                        <th style="padding:10px 12px; cursor:pointer" onclick="sortTable('checkin')">
                            Tanggal <span style="color:#7fb3d3">↕</span>
                        </th>
                        <th style="padding:10px 12px; cursor:pointer" onclick="sortTable('malam')">
                            Malam <span style="color:#7fb3d3">↕</span>
                        </th>
                        <th style="padding:10px 12px; cursor:pointer" onclick="sortTable('total')">
                            Total <span style="color:#7fb3d3">↕</span>
                        </th>
                        <th style="padding:10px 12px">Status</th>
                        <th style="padding:10px 12px; text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelBody">
                    @forelse($bookings as $i => $b)
                    <tr class="data-row"
                        data-nama="{{ strtolower($b->tamu->nama_lengkap ?? '') }}"
                        data-kode="{{ strtolower($b->kode_booking) }}"
                        data-status="{{ $b->status }}"
                        data-bayar="{{ $b->pembayaran ? $b->pembayaran->status : 'belum' }}"
                        data-total="{{ $b->total_biaya }}"
                        data-checkin="{{ $b->tgl_checkin }}"
                        data-malam="{{ $b->jumlah_malam }}"
                        data-nama-raw="{{ $b->tamu->nama_lengkap ?? '' }}"
                        data-index="{{ $i }}">

                        <td style="padding:10px 12px; color:#aaa; font-size:11px" class="col-no">{{ $i + 1 }}</td>

                        <td style="padding:10px 12px">
                            <div style="display:flex; align-items:center; gap:8px">
                                <div style="width:32px; height:32px; background:linear-gradient(135deg,#2e86de,#1a5fa0); border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:13px; flex-shrink:0">
                                    {{ strtoupper(substr($b->tamu->nama_lengkap ?? 'T', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600; color:#0d2137; font-size:12px">{{ $b->tamu->nama_lengkap ?? '-' }}</div>
                                    <div style="font-size:10px; color:#aaa">{{ $b->tamu->no_hp ?? $b->tamu->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>

                        <td style="padding:10px 12px">
                            <span style="font-family:monospace; font-size:11px; color:#2e86de; font-weight:700; background:#eef4fb; padding:3px 8px; border-radius:6px">
                                {{ $b->kode_booking }}
                            </span>
                        </td>

                        <td style="padding:10px 12px">
                            <div style="font-weight:600; color:#0d2137; font-size:12px">{{ $b->kamar->nomor_kamar ?? '-' }}</div>
                            <div style="font-size:10px; color:#aaa">{{ $b->kamar->tipeKamar->nama_tipe ?? '-' }}</div>
                        </td>

                        <td style="padding:10px 12px">
                            <div style="font-size:11px; color:#334155">
                                <span style="color:#27ae60; font-weight:600">↓</span>
                                {{ \Carbon\Carbon::parse($b->tgl_checkin)->format('d M Y') }}
                            </div>
                            <div style="font-size:11px; color:#334155">
                                <span style="color:#e74c3c; font-weight:600">↑</span>
                                {{ \Carbon\Carbon::parse($b->tgl_checkout)->format('d M Y') }}
                            </div>
                        </td>

                        <td style="padding:10px 12px; text-align:center">
                            <span style="background:#f0f4f8; color:#334155; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600">
                                {{ $b->jumlah_malam }}🌙
                            </span>
                        </td>

                        <td style="padding:10px 12px">
                            <div style="font-weight:700; color:#27ae60; font-size:12px">
                                Rp {{ number_format($b->total_biaya, 0, ',', '.') }}
                            </div>
                            @if($b->pembayaran)
                                @if($b->pembayaran->status == 'lunas')
                                    <div style="font-size:10px; color:#27ae60">✅ Lunas</div>
                                @elseif($b->pembayaran->status == 'dp')
                                    <div style="font-size:10px; color:#f39c12">⚠️ DP</div>
                                @else
                                    <div style="font-size:10px; color:#e74c3c">❌ Belum</div>
                                @endif
                            @else
                                <div style="font-size:10px; color:#e74c3c">❌ Belum Bayar</div>
                            @endif
                        </td>

                        <td style="padding:10px 12px">
                            @if($b->status == 'confirmed')
                                <span class="badge badge-info">✅ Confirmed</span>
                            @elseif($b->status == 'checkin')
                                <span class="badge badge-success">🏨 Check-In</span>
                            @elseif($b->status == 'checkout')
                                <span class="badge badge-dark">🚪 Check-Out</span>
                            @elseif($b->status == 'pending')
                                <span class="badge badge-warning">⏳ Pending</span>
                            @else
                                <span class="badge badge-danger">❌ Cancelled</span>
                            @endif
                        </td>

                        <td style="padding:10px 12px; text-align:center; white-space:nowrap">
                            <a href="{{ route('booking.show', $b->id_booking) }}"
                               class="btn btn-primary btn-sm" title="Detail">👁️</a>

                            @if($b->status == 'confirmed')
                                <form action="{{ route('booking.checkin', $b->id_booking) }}"
                                      method="POST" style="display:inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-success btn-sm" title="Check-In">🏨</button>
                                </form>
                            @elseif($b->status == 'checkin')
                                @if($b->pembayaran && $b->pembayaran->status == 'lunas')
                                    <form action="{{ route('booking.checkout', $b->id_booking) }}"
                                          method="POST" style="display:inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-warning btn-sm" title="Check-Out">🚪</button>
                                    </form>
                                @else
                                    <a href="{{ route('booking.show', $b->id_booking) }}"
                                       class="btn btn-sm" style="background:#e67e22; color:#fff" title="Lunasi dulu">💰</a>
                                @endif
                            @endif

                            @if(in_array($b->status, ['confirmed', 'pending']))
                                <form action="{{ route('booking.destroy', $b->id_booking) }}"
                                      method="POST" style="display:inline"
                                      onsubmit="return confirm('Yakin batalkan booking ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Batalkan">❌</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="9" style="text-align:center; padding:50px; color:#ccc">
                            <div style="font-size:36px; margin-bottom:10px">👤</div>
                            <div style="font-size:14px; font-weight:600; color:#aaa">Belum ada data tamu</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- No result --}}
            <div id="noResult" style="display:none; text-align:center; padding:40px; color:#aaa">
                <div style="font-size:32px; margin-bottom:8px">🔍</div>
                <div style="font-size:13px; font-weight:600">Tidak ada data yang cocok</div>
                <div style="font-size:12px; margin-top:4px">Coba ubah filter atau kata kunci pencarian</div>
            </div>
        </div>
    </div>

    <script>
    let sortDir = {};

    function applyFilter() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const status = document.getElementById('filterStatus').value;
        const bayar  = document.getElementById('filterBayar').value;
        const rows   = document.querySelectorAll('.data-row');
        let visible  = 0;

        rows.forEach(row => {
            const nama  = row.dataset.nama;
            const kode  = row.dataset.kode;
            const rStatus = row.dataset.status;
            const rBayar  = row.dataset.bayar;

            const matchSearch = !search || nama.includes(search) || kode.includes(search);
            const matchStatus = !status || rStatus === status;
            const matchBayar  = !bayar  || rBayar === bayar;

            if (matchSearch && matchStatus && matchBayar) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update nomor urut
        let no = 1;
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                row.querySelector('.col-no').textContent = no++;
            }
        });

        document.getElementById('noResult').style.display    = visible === 0 ? 'block' : 'none';
        document.getElementById('filterInfo').style.display  = 'block';
        document.getElementById('jumlahTampil').textContent  = visible;

        applySort();
    }

    function applySort() {
        const sortVal = document.getElementById('sortBy').value;
        const tbody   = document.getElementById('tabelBody');
        const rows    = Array.from(tbody.querySelectorAll('.data-row'));

        rows.sort((a, b) => {
            switch(sortVal) {
                case 'terbaru':   return b.dataset.index - a.dataset.index;
                case 'terlama':   return a.dataset.index - b.dataset.index;
                case 'nama_az':   return a.dataset.namaRaw.localeCompare(b.dataset.namaRaw);
                case 'nama_za':   return b.dataset.namaRaw.localeCompare(a.dataset.namaRaw);
                case 'total_asc': return parseFloat(a.dataset.total) - parseFloat(b.dataset.total);
                case 'total_desc':return parseFloat(b.dataset.total) - parseFloat(a.dataset.total);
                case 'checkin':   return new Date(a.dataset.checkin) - new Date(b.dataset.checkin);
                case 'malam':     return parseFloat(b.dataset.malam) - parseFloat(a.dataset.malam);
                default: return 0;
            }
        });

        rows.forEach(row => tbody.appendChild(row));
    }

    function sortTable(col) {
        const sortMap = {
            'nama':   sortDir.nama   === 'az' ? 'nama_za'    : 'nama_az',
            'total':  sortDir.total  === 'asc'? 'total_desc' : 'total_asc',
            'checkin':'checkin',
            'malam':  'malam',
            'kamar':  sortDir.kamar  === 'az' ? 'nama_za'    : 'nama_az',
        };
        if (col === 'nama')  sortDir.nama  = sortDir.nama  === 'az' ? 'za' : 'az';
        if (col === 'total') sortDir.total = sortDir.total === 'asc'? 'desc': 'asc';

        document.getElementById('sortBy').value = sortMap[col] || 'terbaru';
        applySort();
    }

    function resetFilter() {
        document.getElementById('searchInput').value  = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterBayar').value  = '';
        document.getElementById('sortBy').value        = 'terbaru';
        applyFilter();
        document.getElementById('filterInfo').style.display = 'none';
    }

    // Event listeners
    document.getElementById('searchInput').addEventListener('input', applyFilter);
    document.getElementById('filterStatus').addEventListener('change', applyFilter);
    document.getElementById('filterBayar').addEventListener('change', applyFilter);
    document.getElementById('sortBy').addEventListener('change', applyFilter);
    </script>
</x-app-layout>