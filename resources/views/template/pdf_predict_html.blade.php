<!DOCTYPE html>
<html>
<head>
<style>
#prediksi {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#prediksi td, #reporting th {
  border: 1px solid #ddd;
  padding: 8px;
}

#prediksi tr:nth-child(even){background-color: #f2f2f2;}

#prediksi tr:hover {background-color: #ddd;}

#prediksi th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>
</head>
<body>
	<div align="center"><h3>Laporan Prediksi untuk Item {{ $nama }}</h3></div>
	<br/>
	<table id="prediksi">
		<tr>
			<th>Tanggal Prediksi</th>
			<th>Periode</th>
			<th>Dari</th>
			<th>Sampai</th>
			<th>Hasil Prediksi</th>
			<th>MSE</th>
			<th>MAD</th>
		</tr>
		@foreach($predicts as $d)
			@php 
				$hasil = json_decode($d->hasil);
			@endphp
		<tr>
			<td>{{ $d->tgl_prediksi }}</td>
			<td>{{ $hasil->periode }}</td>
			<td>{{ $hasil->dari }}</td>
			<td>{{ $hasil->sampai }}</td>
			<td>{{ $hasil->total }}</td>
			<td>{{ $d->mse }}</td>
			<td>{{ $d->mad }}</td>
		</tr>
		@endforeach
	</table>

</body>
</html>
