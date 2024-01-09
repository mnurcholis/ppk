<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .invoice {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .invoice-header img {
            max-width: 100px;
            /* Adjust the width as needed */
            height: auto;
        }

        .invoice-body {
            margin-top: 20px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .invoice-total {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="invoice">
        <div class="invoice-header">
            <img src="{{ get_setting()->logo ? route('helper.show-picture', ['path' => get_setting()->logo]) : asset('limitless/global_assets/images/logo_light.png') }}"
                alt="Company Logo">
            <div>
                <h1>{{ get_setting()->title }}</h1>
                <h1>Invoice</h1>
            </div>
        </div>

        <div class="invoice-body">
            <p><strong>Invoice Number:</strong> {{ $data->invoice }}</p>
            <p><strong>Invoice Date:</strong> {{ $data->tanggal }}</p>
            <p><strong>Nama Agent:</strong> {{ $data->agent->name }}</p>
            <p><strong>Status Agent:</strong> {{ $data->agent->comcode->code_nm }}</p>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>QTY Asal</th>
                        <th>QTY Keluar</th>
                        <th>QTY Sisa</th>
                        <th>Harga Jual</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->detailTransaksiJual as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->qty_asal }}</td>
                            <td>{{ $item->qty_keluar }}</td>
                            <td>{{ $item->qty_sisa }}</td>
                            <td>{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->sub_total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="invoice-total">
                <p><strong>Total :</strong> {{ number_format($data->total, 0, ',', '.') }}</p>
                <p><strong>Bayar :</strong> {{ number_format($data->bayar, 0, ',', '.') }}</p>
                <p><strong>Sisa :</strong> {{ number_format($data->sisa, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

</body>

</html>
