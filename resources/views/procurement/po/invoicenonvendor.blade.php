<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ 'Purchase Invoice ' . $client->event }}</title>
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
                <div style="text-align: right; font-size: 18px; color: #456a8c"></div>
                <table style="font-size: 13px">
                    <tr style="font-weight: bold">
                        <td>Tanggal</td>
                        <td>{{ $tanggalSekarang }}</td>
                    </tr>
                    <tr>
                        <td>Pembelian #</td>
                        @if (auth()->user()->lokasi === 'makassar')
                            <td>PO/MKS-{{ $duaAngkaTerakhir . date('m') . $no_invoice }}</td>
                        @else
                            <td>PO/JKT-{{ $duaAngkaTerakhir . date('m') . $no_invoice }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td>Referensi Suppl</td>
                    </tr>
                </table>
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
                    @foreach ($realCost as $row)
                        <tr class="tr">
                            <td class="td">
                                {{ $row->qty . ' ' . $row->satuan_kegiatan }}
                            </td>
                            <td class="td"></td>
                            <td class="td">{{ $row->kegiatan }}</td>
                            <td class="td">{{ 'Rp. ' . number_format($row->satuan_real_cost, 0, ',', '.') }}</td>
                            <td class="td">
                                {{ $row->pajak_po === '0' || $row->pajak_po === null ? '' : $row->pajak_po }}
                                {{ $row->pajak_pph === '0' || $row->pajak_pph === null ? '' : $row->pajak_pph }}
                            </td>
                            <td class="td">{{ 'Rp. ' . number_format($row->disc_item, 0, ',', '.') }}</td>
                            @php
                                $jumlah = $row->satuan_real_cost * $row->freq * $row->qty - $row->disc_item;
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
                            <li>Mohon kirim tagihan dalam bentuk Hardcopy, ke
                                {{ auth()->user()->lokasi === 'makassar'
                                    ? 'Jl. Pandang Raya No.8
                                                                                                                                    Panakukang, Makassar 90231'
                                    : 'Jl. KH Moh Naim II No. 2A, Cipete Utara, Jakarta Selatan' }}
                            </li>
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
                            // Pastikan pajak_data tidak null
                            if ($pajak->pajak_data) {
                                // Hitung jumlah pajak
                                $jumlahPajak = ($pajak->satuan_real_cost * $pajak->qty * $pajak->freq - $pajak->disc_item) * ($pajak->pajak_data->pajak / 100);

                                // Mengecek apakah deskripsi pajak dan jumlah pajak sudah ada sebelumnya
                                $existingPajakRowKey = optional($pajak->pajak_data)->deskripsi_pajak;
                                $existingPajakRow = &$combinedPajakRows[$existingPajakRowKey];

                                if ($existingPajakRow) {
                                    // Jika deskripsi pajak sudah ada, tambahkan jumlah pajak ke baris yang sudah ada
                                    $existingPajakRow['jumlah_pajak'] += $jumlahPajak;
                                } else {
                                    // Jika deskripsi pajak belum ada, tambahkan baris baru ke dalam array
                                    $combinedPajakRows[$existingPajakRowKey] = [
                                        'deskripsi_pajak' => $existingPajakRowKey,
                                        'jumlah_pajak' => $jumlahPajak,
                                    ];
                                }

                                // Hitung total pajak
                                $subTotalPajak += $jumlahPajak;
                            }
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
                        <td>TOTAL</td>
                        <td>{{ 'Rp. ' . number_format($subtotalTotal + $subTotalPajak) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Sisa Tagihan</td>
                        <td>{{ 'Rp. ' . number_format($subtotalTotal + $subTotalPajak) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
