<?php

class VoucherController
{
    use Controller;

    public function index()
    {
        $voucher = new Voucher();
        $vouchers = $voucher->findAll('VoucherID');



        $this->view('Admin/Voucher/index', ['vouchers' => $vouchers]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $voucher = new Voucher();
            
            // Collect form data
            $data = [
                'VoucherCode'   => $_POST['VoucherCode'] ?? '',
                'Description'   => $_POST['Description'] ?? '',
                'VoucherType'   => $_POST['VoucherType'] ?? '',
                'Value'         => $_POST['Value'] ?? 0,
                'PointRequired' => isset($_POST['PointRequired']) ? $_POST['PointRequired'] : null,
                'StartDate'     => $_POST['StartDate'] ?? '',
                'EndDate'       => $_POST['EndDate'] ?? '',
                'Status'        => $this->calculateStatus($_POST['StartDate'], $_POST['EndDate'])
            ];


            $voucher->insert($data);

            // Redirect or display a success message
            redirect('/VoucherController/index');
            exit();
        }

        $this->view('Admin/Voucher/voucher-create');
    }

    public function delete($id){

        echo $id;
    }

    private function calculateStatus($StartDate, $EndDate): string
    {
        $today = date('Y-m-d');

        if ($today >= $StartDate && $today <= $EndDate) {
            return 'Active';
        } else {
            return 'Inactive';
        }
    }
}