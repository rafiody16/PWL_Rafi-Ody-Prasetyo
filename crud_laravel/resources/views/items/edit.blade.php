<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
</head>
<body>
    <h1>Add Item</h1>
    {{-- Form untuk memproses dan menginputkan data yang ingin diubah. --}}
    <form action="{{ route('items.update', $item) }}" method="POST">
        {{-- Token csrf untuk melindungi form --}}
        @csrf

        {{-- Method ini digunakan untuk mengubah method POST menjadi PUT. Karena pada method
             form hanya ada 2 method yaitu GET dan POST.  --}}
        @method('PUT')

        {{-- Value digunakan agar form otomatis terisi dengan data yang sudah ada
        sehingga user dapat memilih data yang ingin diubah. --}}
        <label for="name">Name:</label>
        <input type="text" name="name" value="{{ $item->name }}" required>
        <br>
        <label for="description">Description:</label>
        <textarea name="description" required>{{ $item->description }}</textarea>
        <br>
        <label for="stock">Stock:</label>
        <input type="number" name="stock" value="{{ $item->stock }}" required>
        <br>
        <button type="submit">Update Item</button>
    </form>
    {{-- Mengalihkan halaman ke halaman index. --}}
    <a href="{{ route('items.index') }}">Back to List</a>
</body>
</html>
