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
			<th rowspan="3">Parent Name</th>
			<th rowspan="3">Phone Number</th>
			<th rowspan="3">Address</th>
			<th rowspan="3">School</th>
			<th rowspan="3">Birth Day</th>
			<th rowspan="3">How Do You Know U&I English Course</th>
			<th rowspan="3">Grade</th>
			<th rowspan="3">Kind of Test</th>
			<th colspan="2">Placement Test</th>
			<th rowspan="3">Result</th>
			<th rowspan="3">Reg.Date</th>
		</tr>
		<tr>
			<th rowspan="2">Date</th>
			<th rowspan="2">Recomended Level</th>
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
				<td><?= $value->parent_name ?></td>
				<td><?= $value->parent_phone ?></td>
				<td><?= $value->address ?></td>
				<td><?= $value->school ?></td>
				<td><?= $value->birthday ?></td>
				<td><?= $value->know ?></td>
				<td><?= $value->grade ?></td>
				<td><?= $value->kind_of_test ?></td>
				<td><?= $value->date_test != null ? date('d M', strtotime($value->date_test)) : '' ?></td>
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