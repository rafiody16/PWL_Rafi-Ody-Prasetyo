<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    // Index digunakan untuk menampilkan seluruh data.
    public function index()
    {
        $items = Item::all(); // Mengambil semua data dari tabel item.
        return view('items.index', compact('items')); // Mengembalikan tampilan dengan data kepada user.
    }

    // Menampilkan view create.
    public function create()
    {
        return view('items.create');
    }

    // Memproses data yang masuk pada view create.
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'stock' => 'required'
        ]); // Digunakan untuk memvalidasi data yang masuk.

        // Menginputkan atribut tanpa adanya requirement.
        // Item::create($request->all());
        // return redirect()->route('items.index');

        // Hanya dapat menginputkan atribut yang diizinkan ke dalam tabel.
        Item::create($request->only(['name', 'description', 'stock']));
        // Apabila data berhasil diinputkan kedalam tabel database.
        return redirect()->route('items.index')->with('success', 'Item added successfully.');
    }

    // Menampilkan data berdasarkan id.
    public function show(string $id)
    {
        return view('items.show', compact('item'));
    }

    // Menampilkan view edit berdasarkan id.
    public function edit(string $id)
    {
        return view('items.edit', compact('item'));
    }

    // Memproses data yang akan diubah.
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'stock' => 'required'
        ]); // Digunakan untuk memvalidasi data yang masuk.

        // Menginputkan atribut tanpa adanya requirement.
        // $item->update($request->all());
        // return redirect()->route('items.index');

        // Hanya dapat menginputkan atribut yang diizinkan ke dalam tabel.
        $item->update($request->only(['name', 'description', 'stock']));
        // Apabila data berhasil diubah.
        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    // Destroy digunakan untuk menghapus data.
    public function destroy(Item $item)
    {
        // Menghapus data dari tabel item.
        $item->delete();
        // Apabila data berhasil dihapus.
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
