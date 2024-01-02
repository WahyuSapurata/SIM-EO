<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ 'Purchase Invoice ' . $client->event . '-' . $vendor->nama_perusahaan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
        }

        .container {
            width: 100%;
            margin-top: 50px;
        }

        .table {
            width: 100%;
            font-size: 14px;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table,
        .th,
        .td {
            border: 1px solid #000;
        }

        .th,
        .td {
            padding: 8px;
        }

        td {
            padding: 1px 25px 1px 10px;
        }

        #table td {
            padding: 5px 10px;
        }
    </style>
</head>

@php
    $subtotalTotal = 0;
    $subTotalPajak = 0;
    setlocale(LC_TIME, 'id_ID.utf8'); // Set locale ke bahasa Indonesia

    $tanggalSekarang = strftime('%d %B %Y');

    $tahun = date('Y'); // Mendapatkan tahun saat ini
    $duaAngkaTerakhir = substr($tahun, -2);
@endphp

<body>
    <div class="container">
        <div style="display: flex">
            <div style="width: 100%">
                <img src="http://103.84.206.99/logo.png" alt="">
                <div style="margin-left: 24px; font-size: 25px">Double Helix Indonesia</div>
            </div>
            <div style="width: 100%">
                <div style="text-align: right; font-size: 18px; color: #456a8c">Pembelian</div>
                <table style="font-size: 13px">
                    <tr style="font-weight: bold">
                        <td>Tanggal</td>
                        <td>{{ $tanggalSekarang }}</td>
                    </tr>
                    <tr>
                        <td>Pembelian #</td>
                        @if (auth()->user()->lokasi === 'makassar')
                            <td>PO/MKS-{{ $duaAngkaTerakhir . date('m') . $newPdfNumber }}</td>
                        @else
                            <td>PO/JKT-{{ $duaAngkaTerakhir . date('m') . $newPdfNumber }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td>Referensi Suppl</td>
                    </tr>
                </table>
                <div style="font-size: 15px; margin-top: 30px; display: grid; gap: 3px">
                    <div style="background-color: #0a3e62; color: #FFFFFF; padding-left: 10px">Dari</div>
                    <div style="padding-left: 10px">{{ $vendor->nama_perusahaan }}</div>
                    <div style="padding-left: 10px">{{ $vendor->alamat_perusahaan }}</div>
                    <div style="padding-left: 10px">Tel: {{ $vendor->no_telp }} </div>
                </div>
            </div>
        </div>

        <div style="width: 100%; margin-top: 30px">
            <table class="table">
                <thead style="background-color: #0a3e62; color: #fff;">
                    <tr class="tr">
                        <th class="th">Tags</th>
                        <th class="th">Cara Pengiriman</th>
                        <th class="th">Terms</th>
                        <th class="th">Jatuh Tempo</th>
                        <th class="th">Tgl Pengiriman</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="tr">
                        <td class="td">
                            Double Helix Makassar,<br>
                            {{ $client->event }}
                        </td>
                        <td class="td"></td>
                        <td class="td">H+ {{ $jumlahHari }}</td>
                        <td class="td">{{ $tempo }}</td>
                        <td class="td"></td>
                    </tr>
                </tbody>
            </table>

            <table class="table">
                <thead style="background-color: #0a3e62; color: #fff;">
                    <tr class="tr">
                        <th class="th">QTY</th>
                        <th class="th">ITEM #</th>
                        <th class="th">KETERANGAN</th>
                        <th class="th">HARGA SATUAN (Rp.)</th>
                        <th class="th">PAJAK</th>
                        <th class="th">DISK</th>
                        <th class="th">JUMLAH (Rp.)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($combinedData as $row)
                        <tr class="tr">
                            <td class="td">
                                {{ $row->qty . ' ' . $row->satuan_kegiatan }}
                            </td>
                            <td class="td"></td>
                            <td class="td">{{ $row->kegiatan }}</td>
                            <td class="td">{{ 'Rp. ' . number_format($row->harga_satuan, 0, ',', '.') }}</td>
                            <td class="td">{{ $row->pajak_po }}</td>
                            <td class="td">0.0%</td>
                            @php
                                $jumlah = $row->harga_satuan * $row->freq * $row->qty;
                                $subtotalTotal += $jumlah;
                            @endphp
                            <td class="td">{{ 'Rp. ' . number_format($jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px; padding-left: 20px; display: flex; font-size: 14px">
            <div style="width: 60%; border: 1px solid">
                <div style="border-bottom: 2px; padding: 5px; background-color: #0a3e62; color: #FFFFFF">
                    Catatan
                    dan Instruksi
                    Khusus</div>
                <div style="padding: 5px">
                    <table>
                        <tr>
                            <td>Project</td>
                            <td>: {{ $client->event }}</td>
                        </tr>
                        <tr>
                            <td>Venue</td>
                            <td>: {{ $client->venue }}</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>: {{ $client->project_date }}</td>
                        </tr>
                        <tr>
                            <td>PIC</td>
                            <td>: {{ $client->nama_pic }}</td>
                        </tr>
                    </table>
                    <div style="margin-top: 15px">
                        <div>Notes :</div>
                        <ul style="margin-top: 2px">
                            <li>Mohon Melampirkan PO dan Invoice sebelum Jatuh Tempo pembayaran.</li>
                            <li>Invoice wajib bermaterai 10.000 dan informasi bank account atas nama
                                perusahaan.</li>
                            <li>Dokumen Tagihan dapat dimasukkan maksimal 7 Hari sebelum Jadwal
                                pembayaran.</li>
                            <li>Dokumen Tagihan tidak dapat diproses apabila tidak memenuhi persyaratan
                                penagihan. Dokumen Tagihan Akan di proses apabila telah melengkapi seluruh
                                berkas tagihan.</li>
                            <li>Mohon kirim tagihan dalam bentuk Hardcopy, ke Jl. Pandang Raya No.8
                                Panakukang, Makassar 90231</li>
                            <li>Purchase order ini diterbitkan atas dasar kesepakatan kedua belah Pihak, Sah dan
                                berlaku walaupun tidak ada stempel dan tanda tangan oleh kedua belah Pihak.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div style="width: 40%; font-size: 14px">
                <table id="table" style="border-collapse: collapse;">
                    <tr>
                        <td>Sub Total</td>
                        <td>{{ 'Rp. ' . number_format($subtotalTotal, 0, ',', '.') }}</td>
                    </tr>
                    @php
                        $combinedPajakRows = [];
                        $subTotalPajak = 0;
                    @endphp

                    @foreach ($orderedPajak as $pajak)
                        @php
                            // Mengecek apakah deskripsi pajak sudah ada sebelumnya
                            $existingPajakRow = collect($combinedPajakRows)
                                ->where('deskripsi_pajak', $pajak->deskripsi_pajak)
                                ->first();

                            if ($existingPajakRow) {
                                // Jika deskripsi pajak sudah ada, tambahkan jumlah pajak ke baris yang sudah ada
                                $existingPajakRow['jumlah_pajak'] += $subtotalTotal * ($pajak->pajak / 100);
                            } else {
                                // Jika deskripsi pajak belum ada, tambahkan baris baru ke dalam array
                                $combinedPajakRows[] = [
                                    'deskripsi_pajak' => $pajak->deskripsi_pajak,
                                    'jumlah_pajak' => $subtotalTotal * ($pajak->pajak / 100),
                                ];
                            }

                            // Hitung total pajak
                            $subTotalPajak += $subtotalTotal * ($pajak->pajak / 100);
                        @endphp
                    @endforeach

                    @foreach ($combinedPajakRows as $combinedPajakRow)
                        <tr>
                            <td>{{ $combinedPajakRow['deskripsi_pajak'] }}</td>
                            <td style="border: 1px solid">(
                                {{ 'Rp. ' . number_format($combinedPajakRow['jumlah_pajak']) }})</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td>Diskon</td>
                        @php
                            $discount = (int) str_replace(['Rp', ',', ' '], '', $disc);
                        @endphp
                        <td style="border: 1px solid">{{ 'Rp. ' . number_format($discount) }}</td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td>{{ 'Rp. ' . number_format($subtotalTotal + $subTotalPajak - (int) str_replace(['Rp', ',', ' '], '', $disc)) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Sisa Tagihan</td>
                        <td>{{ 'Rp. ' . number_format($subtotalTotal + $subTotalPajak - (int) str_replace(['Rp', ',', ' '], '', $disc)) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
