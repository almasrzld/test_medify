<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('kategori_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $data_search = Category::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');

        $data_search = $data_search->select('kode', 'nama')->orderBy('id')->get();


        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = Category::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        return view('kategori_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = Category::where('kode', $kode)->first();
        return view('kategori_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new Category;
            $kode = Category::count('id');
            $kode = $kode + 1;
            $kode = 'KTGR' . str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = Category::find($id);
            $kode = $data_item->kode;
        }

        $data_item->kode = $kode;
        $data_item->nama = $request->nama;
        $data_item->save();

        if ($request->categories) {
            $data_item->categories()->sync($request->categories);
        }

        return redirect('kategori-items');
    }

    public function delete($id)
    {
        Category::find($id)->delete();
        return redirect('kategori-items');
    }

    public function updateRandomData()
    {
        $data = Category::get();
        foreach($data as $item)
        {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100,1000000);
            $item->laba = rand(10,99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    public function printPdf($id)
{
    $data = Category::with('items')->findOrFail($id);
    $items = $data->items;

    return view('kategori_items.pdf.printout', [
        'data' => $data,
        'items' => $items,
        'tanggal' => now()->format('d/m/Y H:i')
    ]);
}

}
