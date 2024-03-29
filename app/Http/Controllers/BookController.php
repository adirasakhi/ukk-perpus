<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function show($id)
    {
        $book = Buku::with('ulasan_buku')->findOrFail($id);

        return view('buku.show', compact('book'));
    }
    public function index()
    {
        $books = Buku::all();
        $categories = KategoriBuku::all();
        return view('admin.books.index', compact('books','categories'));
    }

    public function create()
    {
        $categories = KategoriBuku::all();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|numeric',
            'sinopsis' => 'nullable',
            'kategori_id' => 'required|exists:kategori_buku,id',
            'sampul' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Max file size 2MB
        ]);

        // Upload Image
        if ($request->hasFile('sampul')) {
            $file = $request->file('sampul');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/covers', $filename);
        } else {
            $filename = null;
        }

        // Create Book
        $book = new Buku([
            'judul' => $request->input('judul'),
            'penulis' => $request->input('penulis'),
            'penerbit' => $request->input('penerbit'),
            'tahun_terbit' => $request->input('tahun_terbit'),
            'sinopsis' => $request->input('sinopsis'),
            'kategori_id' => $request->input('kategori_id'),
            'sampul' => $filename,
        ]);
        $book->save();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $book = Buku::findOrFail($id);
        $categories = KategoriBuku::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|numeric',
            'sinopsis' => 'nullable',
            'kategori_id' => 'required|exists:kategori_buku,id',
        ]);

        $book = Buku::findOrFail($id);
        $book->update($request->all());

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $book = Buku::findOrFail($id);
        $book->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}
