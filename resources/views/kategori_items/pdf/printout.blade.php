<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Kategori - {{ $data->nama }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid #333;
        }

        th, td {
            padding: 7px 10px;
        }

        .info-table {
            width: 60%;
            border: none;
            margin-bottom: 20px;
        }

        .info-table td {
            border: none;
            padding: 4px 0;
        }

        .no-print {
            margin-bottom: 15px;
        }

        footer {
            position: fixed;
            bottom: 15px;
            right: 20px;
            font-size: 11px;
            color: #444;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="no-print"
        style="padding:6px 12px;background:#0d6efd;color:white;border:none;border-radius:4px;">
        Cetak / Simpan PDF
    </button>

    <h2>Detail Kategori Item</h2>

    <table class="info-table">
        <tr>
            <td><strong>Kode Kategori</strong></td>
            <td>:</td>
            <td>{{ $data->kode }}</td>
        </tr>
        <tr>
            <td><strong>Nama Kategori</strong></td>
            <td>:</td>
            <td>{{ $data->nama }}</td>
        </tr>
    </table>

    <h3>Daftar Item dalam Kategori Ini</h3>

    <table style="margin-top:10px;">
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="25%">Kode Item</th>
                <th>Nama Item</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align:center;">Tidak ada item dalam kategori ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <footer>
        Dicetak pada: {{ $tanggal }}
    </footer>

</body>
</html>
