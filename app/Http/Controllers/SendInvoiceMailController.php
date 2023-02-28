<?php

namespace App\Http\Controllers;

use App\Notifications\InvoiceSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SendInvoiceMailController extends Controller
{
    public function sendInvitation($mail, $fileName) 
    {   
        $documentUploadedData = [
            'name' => 'Dear Customer',
            'salutation' => 'To view and download the invoice, use the following link: ',
            'fileName' => $fileName,
            'fileName' => $fileName,
            'content' => 'Thank you for using our services,',
        ];

        Notification::route('mail', [$mail])->notify(new InvoiceSent($documentUploadedData));
    }
}
