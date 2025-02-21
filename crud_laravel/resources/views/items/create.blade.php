<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
</head>
<body>
    <h1>Add Item</h1>
    {{-- Form untuk memproses dan menginputkan data kedalam tabel item. --}}
    <form action="{{ route('items.store') }}" method="POST">
        {{-- Token CSRF untuk mengamankan form. --}}
        @csrf
    </form>
</body>
</html>
