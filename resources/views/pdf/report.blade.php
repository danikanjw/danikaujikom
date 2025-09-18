<!DOCTYPE html>
<html>
<head>
    <title>Laporan Belanja</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 15px; }
        .info { width: 100%; margin-bottom: 10px; }
        .info td { border: none; padding: 4px; }
        .signature { text-align: right; margin-top: 40px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h3>Toko Alat Kesehatan</h3>
        <h4 class="mb-1">Laporan Belanja Anda</h4>
    </div>

    <table class="info">
        <tr>
            <td>
                User ID: {{ $order->user->username }} <br>
                Nama: {{ $order->user->name }} <br>
                Alamat: {{ $order->user->address ?? '-' }} <br>
                No HP: {{ $order->user->contact_no ?? '-' }}
            </td>
            <td>
                Tanggal: {{ $order->order_date->format('d-m-Y') }} <br>
                Jasa Pengiriman: {{ $order->shippingService->name ?? '-' }} <br>
                Cara Bayar: {{ ucfirst($order->payment_method) }} <br>
                @if($order->payment_method === 'prepaid' && $order->payment_prepaid === 'bank_transfer')
                    Nama Bank: {{ $order->userAccount->bank_name ?? '-' }} <br>
                    Nomor Rekening: {{ $order->userAccount->account_number ?? '-' }}
                @else
                    Metode Prepaid: {{ $order->payment_prepaid ?? '-' }}
                @endif
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Produk dengan IDnya</th>
                <th>Jumlah</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name }} ({{ $item->product->code ?? 'N/A' }})</td>
                <td>{{ $item->quantity }}</td>
                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total belanja (termasuk pajak):</strong>
        Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>

    <div class="signature">
        TANDATANGAN TOKO
    </div>
</body>
</html>