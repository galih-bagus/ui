<style>
	table, th, td {
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
			<th>Excercise Book</th>
			<th>Total Pay</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$no = 1;
		$arr = [ 
			"id_siswa" => [],
			"date" => []
			];
		if (isset($listTransaction)) {
			foreach ($listTransaction as $row) {
				$var = $row->paydate;
				$parts = explode('-', $var);
				$paydate = $parts[2] . '/' . $parts[1];
				$paydetail = $this->mpaydetail->getPaymentByPaymentId($row->id);
				$prv = $row->explanation;
				$exPrv = explode(' ', $prv);
				$query = $this->db->query("SELECT COUNT(*) as countpy
											FROM paydetail
											WHERE paymentid = '" . $row->id . "'");
				$result = $query->num_rows();
				$this->db->select("paydetail.*, s.name, p.program");
				$this->db->from("paydetail");
				$this->db->join("student as s", 's.id = paydetail.studentid');
				$this->db->join("price as p", 'p.id = s.priceid');
				$this->db->where('paymentid', $row->id);
				$count = $this->db->count_all_results();
					foreach ($paydetail->result() as $key => $value) { 
						if ($result < 1) {
							if($key + 1 == $count){
						?>
								<tr>
									<!-- <td><?= $no++ ?></td> -->
									<td><?= $paydate ?></td>
									<td><?= $row->id ?></td>
									<td>
										<?= $value->name ?>
									</td>
									<td>
										<?php if ($row->method == 'CASH') { ?>
											<font color='red'><?= $row->method ?></font>
										<?php } else { ?>
											<font color='blue'><?= $row->method ?></font>
										<?php } ?>
									</td>
									<td>
										<?= $row->method == 'BANK TRANSFER' ? $row->trfdate : $row->number?>
									</td>
									<td><?= $row->program ?></td>
									<td>
										<?php if ($row->level != 'Private'){
											echo date('M', strtotime($value->monthpay));
										}else{
											echo trim($exPrv[0], "()");
										}
										?>
									</td>
									<td>Rp <?= number_format($row->regist, 0, ".", ".") ?></td>
									<td>Rp <?= number_format($row->book, 0, ".", ".") ?></td>
									<td>Rp <?= number_format($row->agenda, 0, ".", ".") ?></td>
									<td>Rp <?= number_format($row->point_book, 0, ".", ".") ?></td>
									<td>Rp <?= number_format($value->category == 'COURSE' ? $value->amount : 0, 0, ".", ".") ?></td>
									<td>Rp <?= number_format($row->agenda, 0, ".", ".") ?></td>
									<td style="background-color: greenyellow;">Rp <?= number_format($row->grandTotal, 0, ".", ".") ?></td>
								</tr>
						<?php
							}
					} else{ if($key + 1 == $count){?>
							<tr>
								<!-- <td><?= $no++ ?></td> -->
								<td><?= $paydate ?></td>
								<td><?= $row->id ?></td>
								<td>
									<?= $value->name ?>
								</td>
								<td>
									<?php if ($row->method == 'CASH') { ?>
										<font color='red'><?= $row->method ?></font>
									<?php } else { ?>
										<font color='blue'><?= $row->method ?></font>
									<?php } ?>
								</td>
								<td>
									<?= $row->method == 'BANK TRANSFER' ? $row->trfdate : $row->number?>
								</td>
								<td><?= $value->program ?></td>
								<td>
									<?php if ($row->level != 'Private'){
										echo date('M', strtotime($value->monthpay));
									}else{
										echo trim($exPrv[0], "()");
									}
									?>
								</td>
								<td>Rp <?= number_format($row->regist, 0, ".", ".") ?></td>
								<td>Rp <?= number_format($row->book, 0, ".", ".") ?></td>
								<td>Rp <?= number_format($row->agenda, 0, ".", ".") ?></td>
								<td>Rp <?= number_format($row->point_book, 0, ".", ".") ?></td>
								<td>Rp <?= number_format($value->category == 'COURSE' ? $value->amount : 0, 0, ".", ".") ?></td>
								<td>Rp <?= number_format($row->agenda, 0, ".", ".") ?></td>
								<td style="background-color: greenyellow;">Rp <?= number_format($row->grandTotal, 0, ".", ".") ?></td>
							</tr>
				
					<?php		}else{?>
						<tr>
							<!-- <td></td> -->
							<td><?= $paydate ?></td>
							<td><?= $row->id ?></td>
							<td>
								<?= $value->name ?>
							</td>
							<td>
								<?php if ($row->method == 'CASH') { ?>
									<font color='red'><?= $row->method ?></font>
								<?php } else { ?>
									<font color='blue'><?= $row->method ?></font>
								<?php } ?>
							</td>
							<td>
								<?= $row->method == 'BANK TRANSFER' ? $row->trfdate : $row->number?>
							</td>
							<td><?= $value->program ?></td>
							<td>
								<?php if ($row->level != 'Private'){
									echo date('M', strtotime($value->monthpay));
								}else{
									echo trim($exPrv[0], "()");
								}
								?>
							</td>
							<td>Rp <?= number_format($row->regist, 0, ".", ".") ?></td>
							<td>Rp <?= number_format($row->book, 0, ".", ".") ?></td>
							<td>Rp <?= number_format($row->agenda, 0, ".", ".") ?></td>
							<td>Rp <?= number_format($row->point_book, 0, ".", ".") ?></td>
							<td>Rp <?= number_format($value->category == 'COURSE' ? $value->amount : 0, 0, ".", ".") ?></td>
							<td>Rp <?= number_format($row->agenda, 0, ".", ".") ?></td>
							<td>Rp <?= number_format(0, 0, ".", ".") ?></td>
						</tr>
				<?php 
						}
					}
				}
			}
		}
		?>
	</tbody>

</table>
