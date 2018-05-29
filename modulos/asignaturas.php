<?php 
	require("conn.php");
		$asignaturas = mysqli_query($con,"SELECT * FROM materias WHERE estado_mat='1' ORDER BY nombre_mat ASC");
		$rawdata = array();
		$i=0;

		while($row=mysqli_fetch_array($asignaturas)){
			$rawdata[$i] = $row;
			$i++;
		}

		echo json_encode($rawdata);
?>