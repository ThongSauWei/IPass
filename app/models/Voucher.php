<?php

class Voucher
{

    use Model;

    protected $table = 'vouchers';

    protected $allowedColumns = [
        'VoucherCode',
        'Description',
        'VoucherType',
        'Value',
        'PointRequired',
        'StartDate',
        'EndDate',
        'Status'

    ];

    public function validate($data)
    {

    }


}