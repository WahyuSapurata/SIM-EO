<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ 'Purchase Invoice ' . $client->event . '-' . $vendor->nama_perusahaan }}</title>
</head>

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

    td {
        padding: 1px 25px 1px 10px;
    }

    #table td {
        padding: 5px 10px;
    }
</style>

@php
    setlocale(LC_TIME, 'id_ID.utf8'); // Set locale ke bahasa Indonesia

    $tanggalSekarang = strftime('%d %B %Y');

    $tahun = date('Y'); // Mendapatkan tahun saat ini
    $duaAngkaTerakhir = substr($tahun, -2);
@endphp

<body>
    {{-- <div class="container">
        <div style="display: flex">
            <div style="width: 100%">
                <img src="/logo.png" alt="">
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
                        <td>PO/MKS-{{ $duaAngkaTerakhir . date('m') }}</td>
                    </tr>
                    <tr>
                        <td>Referensi Suppl</td>
                    </tr>
                </table>
                <div style="font-size: 15px; margin-top: 30px; display: grid; gap: 3px">
                    <div style="background-color: #0a3e62; color: #FFFFFF; padding-left: 10px">Dari</div>
                    <div style="padding-left: 10px">CV Cipta Gelegar</div>
                    <div style="padding-left: 10px">Komp Pettarani Center B/19
                        Jl. A.P Pettarani Makassar
                        90222</div>
                    <div style="padding-left: 10px">Tel: 082188665087 </div>
                </div>
            </div>
        </div>

        <div style="width: 100%; margin-top: 30px">
            <table style="width: 100%; font-size: 14px; border-collapse: collapse;" border="2px solid">
                <thead style="background-color: #0a3e62; color: #FFFFFF">
                    <tr>
                        <th>Tags</th>
                        <th>Cara Pengiriman</th>
                        <th>Terms</th>
                        <th>Jatuh Tempo</th>
                        <th>Tgl Pengiriman</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Double Helix Makassar,<br>
                            Ramah Tamah Rakor<br>
                            GPIK Sulampua
                        </td>
                        <td></td>
                        <td>H+14</td>
                        <td>25/12/2023</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <table style="width: 100%; font-size: 14px; border-collapse: collapse; margin-top: 20px" border="2px solid">
                <thead style="background-color: #0a3e62; color: #FFFFFF">
                    <tr>
                        <th>QTY</th>
                        <th>ITEM #</th>
                        <th>KETERANGAN</th>
                        <th>HARGA SATUAN (Rp.)</th>
                        <th>PAJAK</th>
                        <th>DISK</th>
                        <th>JUMLAH (Rp.)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            21 m2
                        </td>
                        <td></td>
                        <td>LED - uk 7x3</td>
                        <td>500.000,00</td>
                        <td></td>
                        <td>0.0%</td>
                        <td>10.500.000,00</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px; padding-left: 20px; display: flex; font-size: 14px">
            <div style="width: 60%; border: 2px solid">
                <div style="border-bottom: 2px; padding: 5px; background-color: #0a3e62; color: #FFFFFF">
                    Catatan
                    dan Instruksi
                    Khusus</div>
                <div style="padding: 5px">
                    <table>
                        <tr>
                            <td>Project</td>
                            <td>: Ramah Tamah Rakor GPIK Sulampua Tahun 2023</td>
                        </tr>
                        <tr>
                            <td>Venue</td>
                            <td>: Ramah Tamah Rakor GPIK Sulampua Tahun 2023</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>: Ramah Tamah Rakor GPIK Sulampua Tahun 2023</td>
                        </tr>
                        <tr>
                            <td>PIC</td>
                            <td>: Ramah Tamah Rakor GPIK Sulampua Tahun 2023</td>
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
                        <td>11.000.000,00</td>
                    </tr>
                    <tr>
                        <td>PPH 23 (2%) 2.0%</td>
                        <td style="border: 2px solid">( 10.000,00)</td>
                    </tr>
                    <tr>
                        <td>Diskon</td>
                        <td style="border: 2px solid">3.500.000,00</td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td>7.490.000,00</td>
                    </tr>
                    <tr>
                        <td>Sisa Tagihan</td>
                        <td>7.490.000,00</td>
                    </tr>
                </table>
            </div>
        </div>
    </div> --}}
</body>

</html>
