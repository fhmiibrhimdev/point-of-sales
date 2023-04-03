<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LAPORAN DAFTAR CUSTOMER</title>
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
            <p>- DAFTAR CUSTOMER -</p>
        </center>
    </div>

    {{-- <div class="container"> --}}
    <table width="100%" class="table table-striped mt-4">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Nama</th>
                <th>No HP</th>
                <th width="40%">Alamat</th>
                <th width="25%">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $row->nama_customer }}</td>
                <td>{{ $row->hp_customer }}</td>
                <td>{{ $row->alamat_customer }}</td>
                <td>{{ $row->deskripsi_customer }}</td>
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
