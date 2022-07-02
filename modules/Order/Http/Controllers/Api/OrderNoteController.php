<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Http\Controllers\Tenant\EmailController;
use App\Models\Tenant\Configuration;
use Illuminate\Http\Request;
use Modules\Order\Http\Requests\OrderNoteRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Order\Models\OrderNote;
use Modules\Order\Http\Resources\OrderNoteCollection;
use Modules\Order\Http\Resources\OrderNoteResource;
use Modules\Order\Mail\OrderNoteEmail;
use App\Models\Tenant\Person;
use Illuminate\Support\Facades\Mail;


class OrderNoteController extends Controller
{

    public function email(Request $request)
    {

        $order_note = OrderNote::find($request->id);
        $client = Person::find($order_note->customer_id);
        $customer_email = $request->input('email');

        $email = $customer_email;
        $mailable = new OrderNoteEmail($client, $order_note);
        $id = (int) $order_note->id;
        $model = __FILE__.";;".__LINE__;
        $sendIt = EmailController::SendMail($email, $mailable, $id, $model);
        /*
        Configuration::setConfigSmtpMail();
        $array_email = explode(',', $customer_email);
        if (count($array_email) > 1) {
            foreach ($array_email as $email_to) {
                $email_to = trim($email_to);
                if(!empty($email_to)) {
                    Mail::to($email_to)->send(new OrderNoteEmail($client, $order_note));
                }
            }
        } else {
            Mail::to($customer_email)->send(new OrderNoteEmail($client, $order_note));
        }*/
        return [
            'success' => true,
            'message'=> 'Email enviado correctamente.'
        ];

    }

    // public function store(OrderNoteRequest $request)
    // {
    //     DB::connection('tenant')->transaction(function () use ($request) {
    //         $data = $this->mergeData($request);

    //         $this->order_note =  OrderNote::create($data);

    //         foreach ($data['items'] as $row) {
    //             $this->order_note->items()->create($row);
    //         }

    //         $this->setFilename();
    //         $this->createPdf($this->order_note, "a4", $this->order_note->filename);

    //     });

    //     return [
    //         'success' => true,
    //         'data' => [
    //             'id' => $this->order_note->id,
    //         ],
    //     ];
    // }

    public function lists()
    {
        $records = OrderNote::orderBy('id', 'desc')->take(50)->get();

        return new OrderNoteCollection($records);
    }

    // private function setFilename(){

    //     $name = [$this->order_note->prefix,$this->order_note->id,date('Ymd')];
    //     $this->order_note->filename = join('-', $name);
    //     $this->order_note->save();

    // }
}
