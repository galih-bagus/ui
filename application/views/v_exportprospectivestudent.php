<style>
	table,
	th,
	td {
		border: 1px solid black;
	}
</style>
<table id="example1" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th rowspan="3">No.</th>
			<th rowspan="3">Number of Guest Book</th>
			<th rowspan="3">Name of Student</th>
			<th rowspan="3">Grade</th>
			<th rowspan="3">Kind of Test</th>
			<th colspan="4">Placement Test</th>
			<th rowspan="3">Result</th>
			<th rowspan="3">Reg.Date</th>
		</tr>
		<tr>
			<th rowspan="2">Date</th>
			<th colspan="2">Score</th>
			<th rowspan="2">Recomended Level</th>
		</tr>
		<tr>
			<th>Written</th>
			<th>Speaking</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$no = 1;
		foreach ($listStudent->result() as $key => $value) {
			$query = $this->db->query("SELECT * FROM paydetail WHERE studentid = " . $value->id . " && category = 'COURSE' ORDER BY monthpay DESC")->result();
		?>
			<tr>
				<td><?= $no++ ?></td>
				<td><?= $value->staff_name ?></td>
				<td><?= $value->name ?></td>
				<td><?= $value->grade ?></td>
				<td><?= $value->kind_of_test ?></td>
				<td><?= $value->date_test != null ? date('d M', strtotime($value->date_test)) : '' ?></td>
				<td><?= $value->written ?></td>
				<td><?= $value->speaking ?></td>
				<td><?= $value->placement_test_result ?></td>
				<td><?= $value->result . " " . substr($value->dayone, 0, 3) . " " . substr($value->daytwo, 0, 3) . " " . $value->course_time . " " . $value->teacher_name ?></td>
				<td><?php
					if ($query != null) {
						echo $query[0]->monthpay != null ? date('d M', strtotime($query[0]->monthpay)) : '';
					} else {
						echo "";
					}
					?></td>
			</tr>
		<?php 			} ?>
	</tbody>

</table>
