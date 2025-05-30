<?php

namespace App\Livewire\Transaction\Transaction;

use Carbon\Carbon;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Permissions\PermissionHelper;
use App\Permissions\AccessTransaction;
use App\Models\Transaction\Transaction;
use App\Repositories\Account\UserRepository;
use App\Repositories\Transaction\Transaction\TransactionRepository;

class Filter extends Component
{
    public $statuses = [];
    public $status = 'Seluruh';
    public $dateStart;
    public $dateEnd;
    public $isCanDelete;
    public $targetDeleteId;

    public function mount()
    {
        $this->statuses = Transaction::PAYMENT_STATUS_CHOICE;
        $this->isCanDelete = UserRepository::authenticatedUser()->hasPermissionTo(PermissionHelper::transform(AccessTransaction::TRANSACTION, PermissionHelper::TYPE_DELETE));

        $this->dateStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updated()
    {
        $this->dispatch('datatable-add-filter', [
            'status' => $this->status,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd,
        ]);
    }

    #[On('on-delete-dialog-expired-confirm')]
    public function onDialogDeleteConfirm()
    {
        if (!$this->isCanDelete) {
            return;
        }

        TransactionRepository::forceDeleteBy([
            ['status', Transaction::PAYMENT_STATUS_EXPIRED]
        ]);

        Alert::success($this, 'Berhasil', 'Data Expired berhasil dihapus');
        $this->dispatch('datatable-refresh');
    }

    public function showDeleteDialog()
    {
        Alert::confirmation(
            $this,
            Alert::ICON_QUESTION,
            "Hapus Data",
            "Apakah Anda Yakin Ingin Menghapus Data Ini ?",
            "on-delete-dialog-expired-confirm",
            "on-delete-dialog-expired-cancel",
            "Hapus",
            "Batal",
        );
    }
    
    public function render()
    {
        return view('livewire.transaction.transaction.filter');
    }
}
