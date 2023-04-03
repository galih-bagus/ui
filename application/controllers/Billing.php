<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Billing extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        // $this->load->model("mpayment");
        $this->load->model("mstudent");
        $this->load->model("mprice");
        $this->load->model("mbilling");
        $this->load->model("mbillingdetail");
        // $this->load->model("mvoucher");
        // $this->load->model("mpaydetail");
        if ($this->session->userdata('status') != "login") {
            redirect(base_url("user"));
        }
    }

    public function index()
    {
        # code...
    }

    public function data()
    {
        $tmpData = [];
        $data['from_sistem'] = $this->mbilling->getAllPayment()->result();
        $history =  $this->mbilling->history();
        foreach ($history as $value) {
            $tmpStatus = $this->mbillingdetail->historyStatus($value->unique_code);
            $tmpHistory = $value;
            $tmpHistory->status = $tmpStatus->status;
            array_push($tmpData, $tmpHistory);
        }
        $data['from_parent'] = $tmpData;
        $this->load->view('v_header');
        $this->load->view('v_price_bill_data', $data);
        $this->load->view('v_footer');
    }

    public function detail($id)
    {
        $data['detail'] = $this->mbillingdetail->historyDetail('id_payment_bill', $id);
        $data['type'] = 'sistem';
        $this->load->view('v_header');
        $this->load->view('v_price_bill_detail', $data);
        $this->load->view('v_footer');
    }

    public function detailHistory($id)
    {
        $data['detail'] = $this->mbillingdetail->historyDetail('unique_code', $id);
        $data['type'] = 'parent';
        $this->load->view('v_header');
        $this->load->view('v_price_bill_detail', $data);
        $this->load->view('v_footer');
    }

    public function confirmBilling()
    {
        $id = $this->input->post('unique_code');
        $data = array(
            'status' => 'Paid',
        );
        $where['unique_code'] = $id;

        $this->mbillingdetail->confirm($data, $where);

        redirect(base_url() . "billing/detailHistory/" . $id);
    }

    public function addRegularBill()
    {
        $data['listPrice'] = $this->mprice->getPriceRegular();
        $this->load->view('v_header');
        $this->load->view('v_price_bill', $data);
        $this->load->view('v_footer');
    }

    public function studentByClass($priceId)
    {
        $listLateStudent = $this->mbilling->getStudentByPriceId($priceId);

        foreach ($listLateStudent as $student) {
            $monthpay = date("m", strtotime($student->monthpay));
            if (($monthpay < date('m')) || ($student->monthpay == '')) {
                if ($student->condition == "DEFAULT") {
                    $data = array(
                        'penalty' => ($student->course * 10 / 100)
                    );
                } else {
                    $data = array(
                        'penalty' => ($student->adjusment * 10 / 100)
                    );
                }
                $where['id'] = $student->id;
                $this->mstudent->updateStudent($data, $where);
            } else {
                $data = array(
                    'penalty' => 0
                );
                $where['id'] = $student->id;
                $this->mstudent->updateStudent($data, $where);
            }
        }
        $data['detail'] = $this->mprice->getPriceById($priceId)->row();
        $data['studentList'] = $this->mbilling->getLastPayment($priceId)->result();
        
        
        $this->load->view('v_header');
        $this->load->view('v_price_students', $data);
        $this->load->view('v_footer');
    }

    public function savePrivateBill()
    {
        var_dump(json_encode($this->input->post('data')));
        $data = $this->input->post('data');
        usort($data, function ($item1, $item2) {
            return $item1['studentid'] <=> $item2['studentid'];
        });
        $tmpPush = [];
        $tmpBill = [];
        foreach ($data as $key => $dt) {
            if (in_array($dt['studentid'], array_column($tmpBill, 'student_id'))) {
                $cek = array_search($dt['studentid'], array_column($tmpBill, 'student_id'));
                $tmpatn = $tmpBill[$cek]['total'] + intval(str_replace('.', '', $dt['data']['subtotal']));
                $tmpBill[$cek] = ([
                    'student_id' => $dt['studentid'],
                    'total' => $tmpatn,
                ]);
            } else {
                $tmpData = ([
                    'student_id' => $dt['studentid'],
                    'total' => intval(str_replace('.', '', $dt['data']['subtotal'])),
                ]);
                array_push($tmpBill, $tmpData);
            }
            if (in_array($dt['studentid'], array_column($tmpPush, 'student_id'))) {
                $cek = array_search($dt['studentid'], array_column($tmpPush, 'student_id'));
                $tmpatn = $tmpPush[$cek]['attendance'] + $dt['data']['attendance'];
                $tmpPush[$cek] = ([
                    'student_id' => $dt['studentid'],
                    'categori' => 'COURSE',
                    'price' => $dt['data']['price'],
                    'attendance' => $tmpatn,
                ]);
            } else {
                $tmpData = ([
                    'student_id' => $dt['studentid'],
                    'categori' => 'COURSE',
                    'price' => $dt['data']['price'],
                    'attendance' =>  $dt['data']['attendance'],
                ]);
                array_push($tmpPush, $tmpData);
            }
        }
        for ($i = 0; $i < count($tmpBill); $i++) {
            $data = array(
                'total_price' => $tmpBill[$i]['total'],
                'class_type' => 'Private',
                'created_by' => $this->session->userdata('nama'),
                'updated_by' => $this->session->userdata('nama'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $latestRecord = $this->mbilling->addBill($data);
            $dataDetail = array(
                'id_payment_bill' =>  $latestRecord['id'],
                'student_id' => $tmpPush[$i]['student_id'],
                'category' => 'COURSE',
                'price' => $tmpPush[$i]['price'],
                'unique_code' => '-',
                'payment' => "PRIVATE COURSE (" . $tmpPush[$i]['attendance'] . ")",
                'status' => 'Waiting',
            );
            $latestInput = $this->mbillingdetail->addBillDetail($dataDetail);
        }
        var_dump(json_encode(true));
    }

    public function saveRegularBill()
    {
        // var_dump(json_encode($this->input->post()));

        for ($i = 0; $i < count($this->input->post('student_id')); $i++) {
            if ($this->input->post('totalBill')[$i]) {
                $data = array(
                    'total_price' => $this->input->post('totalBill')[$i],
                    'class_type' => 'Reguler',
                    'created_by' => $this->session->userdata('nama'),
                    'updated_by' => $this->session->userdata('nama'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $latestRecord = $this->mbilling->addBill($data);
                
                if (count($this->input->post('course')[$i + 1]) > 1) {
                    $dataDetail = array(
                        'id_payment_bill' =>  $latestRecord['id'],
                        'student_id' => $this->input->post('student_id')[$i],
                        'category' => 'COURSE',
                        'price' => $this->input->post('coursePrice'),
                        'unique_code' => '-',
                        'payment' => "COURSE " . $this->input->post('monthpay')[$i],
                        'status' => 'Waiting',
                    );
                    $latestInput = $this->mbillingdetail->addBillDetail($dataDetail);
                }
                if (count($this->input->post('book')[$i + 1]) > 1) {
                    var_dump("input book");
                    $dataDetail = array(
                        'id_payment_bill' =>  $latestRecord['id'],
                        'student_id' => $this->input->post('student_id')[$i],
                        'category' => 'BOOK',
                        'price' => intval($this->input->post('bookPrice')),
                        'unique_code' => '-',
                        'payment' => "Payment BOOK",
                        'status' => 'Waiting',
                    );
                    $this->mbillingdetail->addBillDetail($dataDetail);
                }
                if (count($this->input->post('pointBook')[$i + 1]) > 1) {
                    var_dump("input pointbook");
                    $dataDetail = array(
                        'id_payment_bill' =>  $latestRecord['id'],
                        'student_id' => $this->input->post('student_id')[$i],
                        'category' => 'POINT BOOK',
                        'price' => intval($this->input->post('pointbookPrice')),
                        'unique_code' => '-',
                        'payment' => "POINT BOOK",
                        'status' => 'Waiting',
                    );
                    $this->mbillingdetail->addBillDetail($dataDetail);
                }
                if (count($this->input->post('agenda')[$i + 1]) > 1) {
                    var_dump("input agenda");
                    $dataDetail = array(
                        'id_payment_bill' =>  $latestRecord['id'],
                        'student_id' => $this->input->post('student_id')[$i],
                        'category' => 'AGENDA',
                        'price' => intval($this->input->post('agendaPrice')),
                        'unique_code' => '-',
                        'payment' => "AGENDA",
                        'status' => 'Waiting',
                    );
                    $this->mbillingdetail->addBillDetail($dataDetail);
                }
            }
        }
        // redirect(base_url("billing/addRegularBill"));
    }
}
