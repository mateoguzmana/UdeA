<table class="table table-hover">
	<thead>
		<th style="color:#006761;">Nombre</th>
	</thead>
	<tbody>
	<?php  
	foreach ($data as $datax):
	?>
	<tr>
		<td><?=$datax["Nombres"]?></td>
	</tr>
<?php 
endforeach;
?>
	</tbody>
</table>