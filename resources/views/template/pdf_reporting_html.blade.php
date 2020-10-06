<!DOCTYPE html>
<html>
<head>
<style>
#reporting {
font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
border-collapse: collapse;
width: 100%;
}

#reporting td, #reporting th {
border: 1px solid #ddd;
padding: 8px;
}

#reporting tr:nth-child(even){background-color: #f2f2f2;}

#reporting tr:hover {background-color: #ddd;}

#reporting th {
padding-top: 12px;
padding-bottom: 12px;
text-align: left;
background-color: #4CAF50;
color: white;
}
</style>
</head>
<body>
	@php 
		function convertDate($monthNum) {
			$dateObj   = DateTime::createFromFormat('!m', $monthNum);
			$monthName = $dateObj->format('F');

			return $monthName;
		}
	@endphp
	<div align="center"><h3>Laporan Penjualan untuk periode {{ convertDate(explode('-', $datetime)[1]) }} - {{ explode('-', $datetime)[0] }}</h3></div>
	<br/>
	<table id="reporting">
		<tr>
			<th>ID</th>
			<th>Nama Produk</th>
			<th>Harga Produk</th>
			<th>Berat Produk</th>
			<th>Warna</th>
			<th>Kategori</th>
			<th>Jumlah Terjual</th>
		</tr>
		@foreach($data as $d)
		<tr>
			<td>{{ $d->id }}</td>
			<td>{{ $d->nama_produk }}</td>
			<td>{{ $d->harga_produk }}</td>
			<td>{{ $d->berat_produk }}</td>
			<td>{{ $d->color }}</td>
			<td>{{ $d->kategori }}</td>
			<td>{{ $d->qty }}</td>
		</tr>
		@endforeach
	</table>

</body>
</html>
