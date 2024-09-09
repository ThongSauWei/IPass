<?php

class CustomerSupportController
{
    use Controller;

    public function index()
    {

        $this->view('Admin/CustomerSupport/index');
    }

    public function custSupportTicket(){
        $this->view('Customer/SupportTicket');
    }

    public function custTicketHistory(){
        $this->view('Customer/TicketHistory');
    }

}
