<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    body{
        font-family: sans-serif
    }
    hr{
        border: none;
        height: 3px;
        background: black;
    }
    table, th, td {
        border-collapse: collapse;
        border:1px solid black;
    }
</style>
<body>
    <div style="text-align: center">
        <h1>LAPORAN HASIL PEMASUKAN DAN PENGELUARAN ZAKAT, INFAQ DAN SHADAQAH</h1>
        <H2>DKM MASJID KERAMAT MEGU CIREBON</H2>
        <H2>1 SYAWAL 1444 H/TH 2023M</H2>
    </div>
    <hr>
    
    <table border="1" style="width:100%; margin-top:20px;">
        <tr>
            <th style="width: 1%">No</th>
            <th colspan="2">Keterangan</th>
            <th colspan="2">Jumlah</th>
        </tr>
        <tr>
            <th>A</th>
            <th style="width: 50%; text-align: left;">Penerimaan Zakat</th>
            <th>Muzaki</th>
            <th>Uang</th>
            <th>Beras</th>
        </tr>
        <tr>
            <td style="text-align: center">1</td>
            <td>Penerimaan Zakat Uang</td>
            <td style="text-align: center">{{ $zakatMuzakkiCash }}</td>
            <td style="text-align: center">{{ $zakatCash }}</td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: center">2</td>
            <td>Penerimaan Zakat Beras</td>
            <td style="text-align: center">{{ $zakatMuzakkiBeras }}</td>
            <td></td>
            <td style="text-align: center">{{ $zakatBeras }} kg</td>
        </tr>
        <tr>
            <th></th>
            <th style="text-align: left">Total Penerimaan Zakat</th>
            <th>{{ $totalZakatMuzakki }}</th>
            <th>{{ $zakatCash }}</th>
            <th>{{ $zakatBeras }} KGS</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th>B</th>
            <th style="text-align: left;">Penerimaan Infaq / Shadaqah</th>
            <th>Muzaki</th>
            <th>Uang</th>
            <th>Beras</th>
        </tr>
        <tr>
            <td style="text-align: center">1</td>
            <td>Penerimaan Infaq / Shadaqah Uang</td>
            <td style="text-align: center">{{ $totalInfaqShadaqahMuzakki }}</td>
            <td style="text-align: center">{{ $totalInfaqShadaqahCash }}</td>
            <td></td>
        </tr>
        <tr>
            <th></th>
            <th style="text-align: left">Total Penerimaan Uang</th>
            <th>{{ $totalInfaqShadaqahMuzakki }}</th>
            <th>{{ $totalInfaqShadaqahCash }}</th>
            <th></th>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th>C</th>
            <th style="text-align: left;">Pengeluaran</th>
            <th>Mustahiq</th>
            <th>Uang</th>
            <th>Beras</th>
        </tr>
        @foreach ($pengeluarans as $pengeluaran)
        <tr>
            <td style="text-align: center">{{ $loop->iteration }}</td>
            <td>{{ $pengeluaran->kebutuhan }}</td>
            <td style="text-align: center">{{ $pengeluaran->jumlah_mustahiq }}</td>
            <td style="text-align: center">{{ "Rp " . number_format($pengeluaran->nominal,0,',','.') }}</td>
            <td style="text-align: center">{{ $pengeluaran->berat_beras }} KGS</td>
        </tr>   
            
        @endforeach
        <tr>
            <th></th>
            <th style="text-align: left">Total Pengeluaran</th>
            <th>{{ $totalPengeluaranMustahik }}</th>
            <th>{{ $totalPengeluaran }}</th>
            <th>{{ $totalPengeluaranBeras }} KGS</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th>D</th>
            <th style="text-align: left">Total (A+B)-C</th>
            <th></th>
            <th>{{ $totalSaldoUang }}</th>
            <th>{{ $totalSaldoBeras }} KGS</th>
        </tr>
    </table>
    <div style="margin-top: 20px">
        <p style="font-size: 12px">CIREBON, {{ $date }}</p>
        <P style="font-size: 12px">DKM MASJID KERAMAT MEGU CIREBON</P>
    </div>
</body>
</html>