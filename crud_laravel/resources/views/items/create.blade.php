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
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <br>
        <label for="description">Description:</label>
        <textarea name="description" required></textarea>
        <br>
        <label for="stock">Stock:</label>
        <input type="number" name="stock" required>
        <br>
        <button type="submit">Add Item</button>
    </form>
    {{-- Mengalihkan halaman ke halaman index. --}}
    <a href="{{ route('items.index') }}">Back to List</a>
</body>
</html>
