<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kategori Pengguna</title>
</head>
<body>
    <h1>Data Kategori Pengguna</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Kode Kategori</th>
            <th>Nama kategori</th>
        </tr>
        @foreach ($data as $dt)
            <tr>
                <th>{{ $dt->kategori_id }}</th>
                <th>{{ $dt->kategori_kode }}</th>
                <th>{{ $dt->kategori_nama }}</th>
            </tr>
        @endforeach
    </table>
</body>
</html>
