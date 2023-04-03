<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LAPORAN KARTU STOCK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');

    th {
        font-weight: 400 !important;
        font-size: 14px !important;
    }

    td {
        font-size: 14px !important;
    }

</style>

<body style="font-family: 'Roboto', sans-serif">

    <div style="margin-top: 20px;">
        <center>
            <span style="font-size: 20px !important">PT. Midragon Corporation</span><br>
            <p>- KARTU STOCK -</p>
            <p>{{ date('d F Y', strtotime($dari_tanggal)) }} -
                {{ date('d F Y', strtotime($sampai_tanggal)) }}</p>
        </center>
    </div>

    {{-- <div class="container"> --}}
    <table width="100%" class="table table-striped mt-4">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Keterangan</th>
                <th>Stock Awal</th>
                <th>In</th>
                <th>Out</th>
                <th>Stock Akhir</th>
            </tr>
        </thead>
        <tbody>
            @php
            $amountIn = 0;
            $amountOut = 0;
            $amountBalance = 0;
            $amountBalanceLast = 0;
            @endphp
            @foreach ($data as $row)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">{{ $row->tanggal }}</td>
                <td>{{ $row->nama_barang }}</td>
                <td>{{ $row->keterangan }}</td>
                <td class="text-center">
                    @if ($row->status == 'Balance')
                    @php
                    $amountBalance += $row->qty;
                    @endphp
                    {{ $row->qty }},00
                    @else
                    -
                    @endif
                </td>
                <td class="text-center">
                    @if ($row->status == 'In')
                    @php
                    $amountIn += $row->qty;
                    @endphp
                    {{ $row->qty }},00
                    @else
                    -
                    @endif
                </td>
                <td class="text-center">
                    @if ($row->status == 'Out')
                    @php
                    $amountOut += $row->qty;
                    @endphp
                    {{ $row->qty }},00
                    @else
                    -
                    @endif
                </td>
                <td class="text-center">
                    @php
                    $amountBalanceLast = last((array)$row->balancing);
                    @endphp
                    {{ $row->balancing }},00
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- </div> --}}



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script>
        window.print()

    </script>
</body>

</html>
