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
        <h1>LAPORAN HASIL PEMASUKAN ZAKAT</h1>
        <H2>DKM MASJID KERAMAT MEGU CIREBON</H2>
        @if ($bulan)
        <p>BULAN: {{ $bulan }}</p>
        @endif
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
            <th style="width: 50%; text-align: left;">Penerimaan Zakat Fitrah</th>
            <th>Muzaki</th>
            <th>Uang</th>
            <th>Beras</th>
        </tr>
        <tr>
            <td style="text-align: center">1</td>
            <td>Penerimaan Zakat Uang</td>
            <td style="text-align: center">{{ $muzakkiFitrahUang }}</td>
            <td style="text-align: center">{{ $zakatFitrahUang }}</td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: center">2</td>
            <td>Penerimaan Zakat Beras</td>
            <td style="text-align: center">{{ $muzakkiFitrahBeras }}</td>
            <td style="text-align: center"></td>
            <td style="text-align: center">{{ $zakatFitrahBeras }} KGS</td>
        </tr>
        <tr>
            <th>B</th>
            <th style="text-align: left">Penerimaan Zakat Maal</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td style="text-align: center">1</td>
            <td>Penerimaan Zakat Uang</td>
            <td style="text-align: center">{{ $muzakkiZakatMaal }}</td>
            <td style="text-align: center">{{ $zakatMaal }}</td>
            <td></td>
        </tr>
        <tr>
            <th>C</th>
            <th style="text-align: left">Total A+B</th>
            <th>{{ $totalMuzakki }}</th>
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