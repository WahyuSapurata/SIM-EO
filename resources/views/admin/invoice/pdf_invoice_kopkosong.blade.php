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
        <div style="display: flex; align-items: center; border-bottom: 4px solid #bf504d; padding-bottom: 20px">
        </div>

        <div style="width: 100%; margin-top: 30px">
            <div style="display: flex">
                <div style="width: 100%; font-weight: bold">
                    <div
                        style="font-size: 23px; border: 1px solid #000; padding: 8px; background-color: #bf504d; width: 350px; color: #FFFFFF">
                        INVOICE
                    </div>
                    <div style="font-size: 16px; margin-left: 20px">INVOICE TO</div>
                    <div style="font-size: 20px; margin-top: 25px; margin-left: 20px">
                        {{ $dataClient->nama_client }}
                        <br> {{ $dataClient->venue }}
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
                <thead style="background-color: #bf504d; color: #fff;">
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
                    <tr class="tr">
                        <td class="td">
                            Grand Total
                        </td>
                        <td class="td">{{ 'Rp. ' . number_format($total, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
            <div style="font-style: italic; font-size: 16px; margin-top: 10px; margin-bottom: 30px">Terbilang :
                ({{ $huruf }} rupiah)</div>

            <table class="table">
                <thead style="background-color: #bf504d; color: #fff;">
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
        <div style="width: 100%; margin-top: 175px; height: 10px; border: 1px solid; background-color: #bf504d"></div>
    </div>
</body>

</html>
