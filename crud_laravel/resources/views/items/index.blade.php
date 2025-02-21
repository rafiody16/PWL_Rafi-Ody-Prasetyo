<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item List</title>
</head>
<body>
    <h1>Items</h1>
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
                    {{-- Token csrf untuk mengamankan form --}}
                    @csrf
                    {{-- Method DELETE digunakan untuk memproses penghapusan data --}}
                    @method('DELETE')
                    {{-- Tombol untuk menghapus data --}}
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>
