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
            $tmpHistory->status = isset($tmpStatus->status) ? $tmpStatus->status : 'Submitted' ;
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
        $data['studentList'] = $this->mbilling->getStudentByPriceId($priceId);
        $data['detail'] = $this->mprice->getPriceById($priceId)->row();
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
        var_dump(json_encode($this->input->post()));

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
                var_dump($latestRecord['id']);
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
                if (intval($this->input->post('book')[$i]) > 1) {
                    $subPrice = intval($this->input->post('bookPrice')) * intval($this->input->post('book')[$i]);
                    $dataDetail = array(
                        'id_payment_bill' =>  $latestRecord['id'],
                        'student_id' => $this->input->post('student_id')[$i],
                        'category' => 'BOOK',
                        'price' => $subPrice,
                        'unique_code' => '-',
                        'payment' => "Payment BOOK(" . $this->input->post('book')[$i] . ")",
                        'status' => 'Waiting',
                    );
                    $latestInput = $this->mbillingdetail->addBillDetail($dataDetail);
                }
                if (intval($this->input->post('registration')[$i])  != 0) {
                    $subPrice = intval($this->input->post('registrationPrice')) * intval($this->input->post('registration')[$i]);
                    $dataDetail = array(
                        'id_payment_bill' =>  $latestRecord['id'],
                        'student_id' => $this->input->post('student_id')[$i],
                        'category' => 'REGISTRATION',
                        'price' => $subPrice,
                        'unique_code' => '-',
                        'payment' => "REGISTRATION",
                        'status' => 'Waiting',
                    );
                    $latestInput = $this->mbillingdetail->addBillDetail($dataDetail);
                }
                if (intval($this->input->post('pointBook')[$i]) != 0) {
                    $subPrice = intval($this->input->post('pointbookPrice')) * intval($this->input->post('pointBook')[$i]);
                    $dataDetail = array(
                        'id_payment_bill' =>  $latestRecord['id'],
                        'student_id' => $this->input->post('student_id')[$i],
                        'category' => 'POINT BOOK',
                        'price' => $subPrice,
                        'unique_code' => '-',
                        'payment' => "POINT BOOK-Qty(" . $this->input->post('pointBook')[$i] . ")",
                        'status' => 'Waiting',
                    );
                    $latestInput = $this->mbillingdetail->addBillDetail($dataDetail);
                }
                if (intval($this->input->post('agenda')[$i]) != 0) {
                    $subPrice = intval($this->input->post('agendaPrice')) * intval($this->input->post('agenda')[$i]);
                    $dataDetail = array(
                        'id_payment_bill' =>  $latestRecord['id'],
                        'student_id' => $this->input->post('student_id')[$i],
                        'category' => 'AGENDA',
                        'price' => $subPrice,
                        'unique_code' => '-',
                        'payment' => "AGENDA-Qty(" . $this->input->post('agenda')[$i] . ")",
                        'status' => 'Waiting',
                    );
                    $latestInput = $this->mbillingdetail->addBillDetail($dataDetail);
                }
            }
        }
        redirect(base_url("billing/addRegularBill"));
    }
}
