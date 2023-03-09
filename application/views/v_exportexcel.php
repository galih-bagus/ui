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
			<!-- <th>No</th> -->
			<th>Date</th>
			<th>No Nota</th>
			<th>Name</th>
			<th>Payment Method</th>
			<th>Date/TC</th>
			<th>Level</th>
			<th>Month</th>
			<th>Register Fee</th>
			<th>Book</th>
			<th>Agenda Book</th>
			<th>Point Book</th>
			<th>Course Fee</th>
			<th>Exercise Book</th>
			<th>Total Pay</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		foreach ($listTransaction as $key => $value) { 
			$queryDetail = $this->db->query("SELECT pd.*, s.name, p.program, p.level, s.id as student_id,
										(SELECT SUM(amount) FROM paydetail WHERE category ='AGENDA' AND studentid = s.id AND paymentid = py.id) as agenda,
										(SELECT SUM(amount) FROM paydetail WHERE category ='COURSE' AND studentid = s.id AND paymentid = py.id) as course,
										(SELECT SUM(amount) FROM paydetail WHERE category ='POINT BOOK' AND studentid = s.id AND paymentid = py.id) as point_book,
										(SELECT SUM(amount) FROM paydetail WHERE category ='BOOK' AND studentid = s.id AND paymentid = py.id) as book,
										(SELECT SUM(amount) FROM paydetail WHERE category ='REGISTRATION' AND studentid = s.id AND paymentid = py.id) as regist,
										(SELECT SUM(amount) FROM paydetail WHERE category ='EXERCISE' AND studentid = s.id AND paymentid = py.id) as exercise
										FROM paydetail pd
										INNER JOIN student s ON pd.studentid = s.id
										INNER JOIN price p ON p.id = s.priceid
										INNER JOIN payment py ON py.id = pd.paymentid
										WHERE pd.paymentid = '" . $value->id . "'
										
										GROUP BY MONTH(pd.monthpay),student_id");
			$resultDetail = $queryDetail->result();
			$var = $value->paydate;
			$parts = explode('-', $var);
			$paydate = $parts[2] . '/' . $parts[1];
			// echo '<pre>';
			// || ($valueDetail->monthpay == null && $valueDetail->point_book != 0) || ($valueDetail->monthpay == null && $valueDetail->regist != 0) || ($valueDetail->monthpay == null && $valueDetail->exercise != 0) || ($valueDetail->monthpay == null && $valueDetail->book != 0)
			foreach ($resultDetail as $keyDetail => $valueDetail) {
				if ($valueDetail->monthpay != null || (($valueDetail->monthpay == null && $valueDetail->category == 'BOOK')) || (($valueDetail->monthpay == null && $valueDetail->category == 'POINT BOOK')) || (($valueDetail->monthpay == null && $valueDetail->category == 'AGENDA')) || (($valueDetail->monthpay == null && $valueDetail->category == 'REGISTRATION')) /* || (($valueDetail->monthpay == null && $valueDetail->category == 'EXERCISE'))  */) {
		?>
			<tr>
				<td><?= $paydate ?></td>
				<td><?= $value->id ?></td>
				<td><?= $valueDetail->name.'-'.$valueDetail->student_id ?></td>
				<td>
					<?php if ($value->method == 'CASH') { ?>
						<font><?= $value->method ?></font>
					<?php } else { ?>
						<font color='blue'><?= $value->method ?></font>
					<?php } ?>
				</td>
				<td>
					<?= $value->method == 'BANK TRANSFER' ? $value->trfdate : $value->number ?>
				</td>
				<td>
					<?= $valueDetail->program ?>
				</td>
				<td>
					<?php if ($valueDetail->level != 'Private') {
						$month = $valueDetail->monthpay != null ? date('M', strtotime($valueDetail->monthpay)) : '';
						if ($valueDetail->monthpay == $var) {
							echo "<font color='black'>" . $month . "</font>";
						} else {
							echo "<font color='red'>" . $month . "</font>";
						}
					} else {
						echo $valueDetail->explanation;
					}
					?>
				</td>
				<td>
					Rp <?= number_format($valueDetail->regist, 0, ".", ".") ?>
				</td>
				<td>
					Rp <?= number_format($valueDetail->book, 0, ".", ".") ?>
				</td>
				<td>
					Rp <?= number_format($valueDetail->agenda, 0, ".", ".") ?>
				</td>
				<td>
					Rp <?= number_format($valueDetail->point_book, 0, ".", ".") ?>
				</td>
				<td>Rp <?= number_format($valueDetail->category == 'COURSE' ? $valueDetail->amount : 0, 0, ".", ".") ?></td>
				<td>
					Rp <?= number_format($valueDetail->exercise, 0, ".", ".") ?>
				</td>
				<td style="background-color: greenyellow;">
					Rp <?= number_format($value->total, 0, ".", ".") ?>
				</td>
			</tr>
		<?php 
			}
			}
		}
		?>
	</tbody>

</table>
