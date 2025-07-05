<table>
    <tr>
        <td colspan="7"><strong>Data Leads</strong></td>
    </tr>
    {{-- <tr></tr> --}}
    <tr>
        <th>No</th>
        <th>Leads</th>
        <th>Email</th>
        <th>No. Telp</th>
        <th>Industry Type</th>
        <th>Alamat</th>
        <th>Type</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($data as $row)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $row->name }}</td>
        <td>{{ $row->email }}</td>
        <td>{{ $row->notelp }}</td>
        <td>{{ $row->indname }}</td>
        <td>{{ $row->alamat }}</td>
        <td>{{ $row->type }}</td>
    </tr>
    @endforeach
</table>