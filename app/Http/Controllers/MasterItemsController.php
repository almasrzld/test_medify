<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use App\Models\Category;
use Illuminate\Http\Request;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        if ($request->filled('hargamin')) {
            $data_search = $data_search->where('harga_beli', '>=', $hargamin);
        }

        if ($request->filled('hargamax')) {
            $data_search = $data_search->where('harga_beli', '<=', $hargamax);
        }

        $data_search = $data_search->select('kode', 'nama', 'foto', 'jenis', 'harga_beli', 'laba', 'supplier')->orderBy('id')->get();


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
            $item = MasterItem::find($id);
        }

       $data['item'] = $item;
        $data['method'] = $method;
        $data['categories'] = Category::all();
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

                if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data_item->foto = $filename;
        }


        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;
        $data_item->save();

        $data_item->categories()->sync($request->categories ?? []);

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
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

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi','Bukulapuk','TokoBagas','E Commurz','Blublu'];
        $random = rand(0,4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat','Alkes','Matkes','Umum','ATK'];
        $random = rand(0,4);
        return $array[$random];
    }

    public function exportExcel()
{
    $items = \App\Models\MasterItem::with('categories')->get();

    $filename = "master_items_" . date('Ymd_His') . ".xls";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    echo "<table border='1'>";
    echo "
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Nama Item</th>
            <th>Nama Supplier</th>
            <th>Harga</th>
            <th>Laba (%)</th>
            <th>Harga Jual</th>
        </tr>
    ";

    $no = 1;

    foreach ($items as $item) {

        $kategoriList = $item->categories->pluck('nama')->implode(', ');

        $hargaJual = $item->harga + ($item->harga * $item->laba / 100);

        echo "
            <tr>
                <td>{$no}</td>
                <td>{$kategoriList}</td>
                <td>{$item->nama}</td>
                <td>{$item->supplier}</td>
                <td>{$item->harga}</td>
                <td>{$item->laba}</td>
                <td>{$hargaJual}</td>
            </tr>
        ";

        $no++;
    }

    echo "</table>";
    exit;
}

}
