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
        <h1>LAPORAN INFAQ</h1>
        <H2>DKM MASJID KERAMAT MEGU CIREBON</H2>
        @if ($bulan)
        <p>BULAN: {{ $bulan }}</p>
        @endif
    </div>
    <hr>
    
    <table border="1" style="width:100%; margin-top:20px;">
        <tr>
            <th style="width: 1%">No</th>
            <th>Keterangan</th>
            <th>Jumlah</th>
        </tr>
        <tr>
            <th>A</th>
            <th style="width: 50%; text-align: left;">Pemasukan Uang</th>
            <th style="text-align: center">Uang</th>
        </tr>
        @foreach ($infaqs as $infaq)
        <tr>
            <td style="text-align: center">{{ $loop->iteration }}</td>
            <td style="text-align: left">{{ $infaq->nama_donatur }}</td>
            <td style="text-align: left">{{ "Rp " . number_format($infaq->nominal,0,',','.') }}</td>
        </tr>
        @endforeach
        <tr>
            <th>B</th>
            <th style="text-align: left">Total Pemasukan</th>
            <th style="text-align: left">{{ $totalInfaq }}</th>
        </tr>
    </table>
    <div style="margin-top: 20px">
        <p style="font-size: 12px">CIREBON, {{ $date }}</p>
        <P style="font-size: 12px">DKM MASJID KERAMAT MEGU CIREBON</P>
    </div>
</body>
</html>