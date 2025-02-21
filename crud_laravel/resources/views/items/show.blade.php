<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item List</title>
</head>
<body>
    <h1>Items</h1>
    {{-- Berfungsi untuk pengecekkan pesan 'success' pada session --}}
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif
    {{-- Menambahkan item baru --}}
    <a href="{{ route('items.create') }}">Add Item</a>
    {{-- Menampilkan seluruh daftar item --}}
    <ul>
        @foreach ($items as $item)
            <li>
                {{ $item->name }}
                {{ $item->stock }}
                {{-- Mengubah data sesuai dengan baris data yang dipilih --}}
                <a href="{{ route('items.edit', $item) }}">Edit</a>
                {{-- Form digunakan untuk menghapus data --}}
                <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline;">
                    {{-- Token csrf untuk melindungi form --}}
                    @csrf
                    {{-- Method ini digunakan untuk mengubah method POST menjadi DELETE. Karena pada method
                         form hanya ada 2 method yaitu GET dan POST.  --}}
                    @method('DELETE')
                    {{-- Tombol untuk menghapus data --}}
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>
