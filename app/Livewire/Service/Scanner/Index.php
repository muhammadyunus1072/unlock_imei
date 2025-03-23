<?php

namespace App\Livewire\Service\Scanner;

use Carbon\Carbon;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Transaction\Transaction;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Repositories\Transaction\Transaction\TransactionRepository;

class Index extends Component
{
    public $code = null;

    public function updatedCode($data)
    {
        $this->getData($this->code);
    }
    #[On('on-scanned')]
    public function scanned($data){
       $this->getData($data);
    }

    private function getData($data)
    {
        try {
            $transaction = TransactionRepository::findBy([
                ['booking_code', $data]
            ]);
            
            if(!$transaction){
                Alert::fail($this, 'Gagal', 'Data Tidak Ditemukan');
            }else if($transaction->status === Transaction::STATUS_PAID && !$transaction->scanned_at){
                $validateData = [
                    'scanned_at' => Carbon::now(),
                ];

                TransactionRepository::update($transaction->id, $validateData);

                Alert::success($this, 'Berhasil', "Selamat Datang, Booking untuk ".$transaction->transactionDetailSample['product_name']." pada ".Carbon::parse($transaction->transactionDetailSample['booking_date'])->translatedFormat('l, d F Y')." ".Carbon::parse($transaction->transactionDetailSample['product_booking_time_time'])->translatedFormat('H:i'));
            }else if($transaction->scanned_at){
                Alert::fail($this, 'Gagal', 'Data Sudah Scan');
            }
        } catch (DecryptException $e) {
            Alert::fail($this, 'Gagal', 'Data Tidak Ditemukan');
        }
    }

    public function render()
    {
        return view('livewire.service.scanner.index');
    }
}
