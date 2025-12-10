@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('kategori-items')}}" class="btn btn-secondary">Kembali ke Daftar Item</a>
            </div>
            <div class="card">
                <div class="card-header">Kategori Item</div>

                <div class="card-body">
                    <table>
                        <tr>
                            <th>Kode</th>
                            <td>:</td>
                            <td>{{$data->kode}}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>:</td>
                            <td>{{$data->nama}}</td>
                        </tr>
                    </table>
                    <a class="btn btn-info" href="{{url('kategori-items/form/edit')}}/{{$data->id}}">Edit</a>
                    <a class="btn btn-danger" href="{{url('kategori-items/delete')}}/{{$data->id}}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    <a href="{{url('kategori-items/print')}}/{{$data->id}}" class="btn btn-primary" target="_blank">Print PDF</a>

                    <hr>

                    <h5 class="mt-3">Daftar Item dalam Kategori Ini:</h5>

                    @if($data->items->count() > 0)
                        <table class="table table-bordered mt-2">
                            <thead>
                                <tr>
                                    <th>Kode Item</th>
                                    <th>Nama Item</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->items as $item)
                                <tr>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->nama }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Belum ada item dalam kategori ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection