<table class="table table-hover">
	<thead>
		<th>Nombre</th>
		<th>Telefono</th>
	</thead>
	<tbody>
	<?php  
	foreach ($data as $datax):
	?>
	<tr>
		<td><?=$datax["Nombres"]?></td>
		<td><?=$datax["Telefono"]?></td>
	</tr>
<?php 
endforeach;
?>
	</tbody>
</table>