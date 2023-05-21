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
        <h1>LAPORAN PENGELUARAN</h1>
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
            <th style="width: 50%; text-align: left;">Pengeluaran Uang</th>
            <th>Mustahik</th>
            <th>Uang</th>
            <th>Beras</th>
        </tr>
        
        @foreach ($pengeluaranUangs as $pengeluaranUang)
        <tr>
            <td style="text-align: center">{{ $loop->iteration }}</td>
            <td>{{ $pengeluaranUang->kebutuhan }}</td>
            <td style="text-align: center">{{ $pengeluaranUang->jumlah_mustahiq }}</td>
            <td style="text-align: center">{{ "Rp " . number_format($pengeluaranUang->nominal,0,',','.') }}</td>
            <td style="text-align: center">{{ $pengeluaranUang->berat_beras }} KGS</td>
        </tr>
        @endforeach
        
        <tr>
            <th>B</th>
            <th style="text-align: left">Pengeluaran Beras</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>

        @foreach ($pengeluaranBerass as $pengeluaranBeras)
        <tr>
            <td style="text-align: center">{{ $loop->iteration }}</td>
            <td>{{ $pengeluaranBeras->kebutuhan }}</td>
            <td style="text-align: center">{{ $pengeluaranBeras->jumlah_mustahiq }}</td>
            <td style="text-align: center">{{ "Rp " . number_format($pengeluaranBeras->nominal,0,',','.') }}</td>
            <td style="text-align: center">{{ $pengeluaranBeras->berat_beras }}</td>
        </tr>
        @endforeach

        <tr>
            <th>C</th>
            <th style="text-align: left">Total Pengeluaran</th>
            <th></th>
            <th>{{ $totalSaldoPengeluaranUang }}</th>
            <th>{{ $totalSaldoPengeluaranBeras }} KGS</th>
        </tr>
    </table>
    <div style="margin-top: 20px">
        <p style="font-size: 12px">CIREBON, {{ $date }}</p>
        <P style="font-size: 12px">DKM MASJID KERAMAT MEGU CIREBON</P>
    </div>
</body>
</html>