<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
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

{{-- @php
    $subtotalTotal = 0;
    $subTotalPajak = 0;
    setlocale(LC_TIME, 'id_ID.utf8'); // Set locale ke bahasa Indonesia

    $tanggalSekarang = strftime('%d %B %Y');

    $tahun = date('Y'); // Mendapatkan tahun saat ini
    $duaAngkaTerakhir = substr($tahun, -2);
@endphp --}}

<body>
    <div class="container">
        <div style="display: flex; align-items: center; border-bottom: 4px solid #e36c09; padding-bottom: 20px">
            <div style="width: 100%">
                <img src="https://raw.githubusercontent.com/WahyuSapurata/foto/main/Screenshot_from_2024-01-09_10-09-05-removebg-preview.png"
                    alt="">
            </div>
            <div style="width: 100%; font-size: 18px; text-align: end">
                <div style="font-size: 20px; font-weight: bold">PT. LINGKARAN GANDA BERKARYA</div>
                @if (auth()->user()->lokasi === 'makassar')
                    <div>Base : Jl. Pandang Raya No.8,Panakukang, Makassar</div>
                    <div>Telp/fax 0411 425194</div>
                @else
                    <div>Base : Jl. KH. Moh. Naim II No.2A 4, RT.4/RW.11, Cipete Utara, Kec. Kby. Baru, Kota Jakarta
                        Selatan</div>
                    <div>Telp/fax 021-27085607</div>
                @endif
                <div>Email : info@doublehelix.co.id</div>
            </div>
        </div>

        <div style="width: 100%; margin-top: 30px">
            <div style="display: flex">
                <div style="width: 100%; font-weight: bold">
                    <div
                        style="font-size: 23px; border: 1px solid #000; padding: 8px; background-color: #e36c09; width: 350px; color: #FFFFFF">
                        INVOICE
                    </div>
                    <div style="font-size: 16px; margin-left: 20px">INVOICE TO</div>
                    <div style="font-size: 20px; margin-top: 25px; margin-left: 20px">
                        {{ $dataClient->nama_client }}
                        <br> {{ $alamat_perusahaan }}
                    </div>
                </div>
                <div style="width: 100%">
                    <table style="font-size: 13px; padding-left: 50px">
                        <tr>
                            <td>No. Invoice</td>
                            <td>: {{ $no_inv }}</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>: {{ $tanggal_invoice }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <table class="table">
                <thead style="background-color: #548ed4; color: #fff;">
                    <tr class="tr">
                        <th class="th">DESCRIPTION</th>
                        <th class="th" style="width: 150px">TOTAL PRICE (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="tr">
                        <td class="td">
                            {{ $deskripsi }}
                        </td>
                        <td class="td">{{ 'Rp. ' . number_format($total, 0, ',', '.') }}</td>
                    </tr>
                    @php
                        $hasilPajak = 0; // Inisialisasi nilai $hasilPajak
                    @endphp
                    @if ($dataPajak)
                        <tr class="tr">
                            <td class="td">
                                {{ $dataPajak->deskripsi_pajak }}
                            </td>
                            @php
                                $formatter = new NumberFormatter('id', NumberFormatter::SPELLOUT);
                                $totalPajak = $dataPajak->pajak / 100;
                                $hasilPajak = $total * $totalPajak;
                                $terbilang = $formatter->format($total + $hasilPajak);
                            @endphp
                            <td class="td">{{ 'Rp. ' . number_format($hasilPajak, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        @php
                            $formatter = new NumberFormatter('id', NumberFormatter::SPELLOUT);
                            $terbilang = $formatter->format($total);
                        @endphp
                    @endif

                    <tr class="tr">
                        <td class="td">
                            Grand Total
                        </td>
                        <td class="td">{{ 'Rp. ' . number_format($total + $hasilPajak, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
            <div style="font-style: italic; font-size: 16px; margin-top: 10px; margin-bottom: 30px">Terbilang :
                ({{ $terbilang }} rupiah)</div>

            <table class="table">
                <thead style="background-color: #e36c09; color: #fff;">
                    <tr class="tr">
                        <th class="th">PAYMENT METHOD</th>
                        <th class="th">SIGNATURE</th>
                    </tr>
                </thead>
            </table>

            <div style="font-size: 15px; font-weight: bold; margin-left: 20px; margin-top: 30px">
                <div style="font-weight: normal">Pembayaran dapat dilakukan melalui transfer ke Rekening :</div>
                <div>BANK {{ $dataBank->nama_bank }}</div>
                <div>{{ $dataBank->no_rek }}</div>
                <div>{{ $dataBank->cabang }}</div>
                <div>a.n {{ $dataBank->atas_nama }}</div>
            </div>
        </div>

        <div style="width: 100%; margin-top: 100px; font-size: 20px">
            <div style="float: right; text-align: center; margin-right: 70px;">
                <div style="border-bottom: 1px solid">{{ $penanggung_jawab }}</div>
                <div>{{ $jabatan }}</div>
            </div>
        </div>
        <div style="width: 100%; margin-top: 175px; height: 10px; border: 1px solid; background-color: #e36c09"></div>
    </div>
</body>

</html>
