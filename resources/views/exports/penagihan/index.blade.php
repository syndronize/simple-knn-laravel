<table>
    <tr>
        <td colspan="4"><strong>Total Pendapatan {{$header}}</strong></td>
    </tr>
    <tr></tr>
    <tr>
        <th>No</th>
        <th>Customer</th>
        <th>Product</th>
        <th>Total Penagihan</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($data as $row)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $row->perusahaan }}</td>
        <td>{{ $row->productname }}</td>
        <td>Rp{{ number_format($row->tagihan, 0, ',', '.') }}</td>
    </tr>
    @endforeach
    <tr></tr>
    <tr>
        <td colspan="3"><strong>Total Seluruh penagihan</strong></td>
        <td><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></td>
    </tr>
</table>