<?php

namespace App\Livewire\Account\User;

use Exception;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\Account\RoleRepository;
use App\Repositories\Account\UserRepository;
use App\Repositories\Account\UserStudioRepository;

class Detail extends Component
{
    public $objId;

    public $roles = [];

    #[Validate('required', message: 'Nama Harus Diisi', onUpdate: false)]
    public $name;
    
    #[Validate('required', message: 'Username Harus Diisi', onUpdate: false)]
    public $username;

    #[Validate('required', message: 'Email Harus Diisi', onUpdate: false)]
    #[Validate('email', message: "Format Email Tidak Sesuai", onUpdate: false)]
    public $email;

    #[Validate('required', message: 'Jabatan Harus Dipilih', onUpdate: false)]
    public $role;

    public $password;

    public $userStudios = [];

    public function mount()
    {
        $this->roles = RoleRepository::getIdAndNames()->pluck('name');
        $this->role = $this->roles[0];

        if ($this->objId) {
            $user = UserRepository::find(Crypt::decrypt($this->objId));

            $this->name = $user->name;
            $this->username = $user->username;
            $this->email = $user->email;
            $this->role = isset($user->roles[0]) ? $user->roles[0]->name : null;

            foreach ($user->userStudios as $item) {
                $this->userStudios[] = [
                    'id' => Crypt::encrypt($item->studio_id),
                    'text' => $item->studio->name,
                ];
            }
        }
    }

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('user.edit', $this->objId);
        } else {
            $this->redirectRoute('user.create');
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('user.index');
    }

    public function selectStudio($data)
    {
        $this->userStudios[] = [
            'id' => $data['id'],
            'text' => $data['text'],
        ];
    }

    public function unselectStudio($data)
    {
        $index = array_search($data['id'], array_column($this->userStudios, 'id'));
        if ($index !== false) {
            unset($this->userStudios[$index]);
        }
    }

    public function store()
    {
        $this->validate();
        $objId = $this->objId ? Crypt::decrypt($this->objId) : false;
        $otherUser = UserRepository::findByEmail($this->email);
        if (!empty($otherUser) && $otherUser->id != $objId) {
            Alert::fail($this, "Gagal", "Email telah digunakan pada akun yang lainnya. Silahkan gunakan email lain.");
            return;
        }

        $otherUser = UserRepository::findByUsername($this->username);
        if (!empty($otherUser) && $otherUser->id != $objId) {
            Alert::fail($this, "Gagal", "Username telah digunakan pada akun yang lainnya. Silahkan gunakan username lain.");
            return;
        }


        if (empty($objId) && empty($this->password)) {
            Alert::fail($this, "Gagal", "Password Harus Diisi");
            return;
        }

        $validatedData = [
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
        ];
        if (!empty($this->password)) {
            $validatedData['password'] = Hash::make($this->password);
        }

        try {
            DB::beginTransaction();
            if ($objId) {
                UserRepository::update($objId, $validatedData);
                $user = UserRepository::find($objId);
                $user->syncRoles($this->role);
            } else {
                $user = UserRepository::create($validatedData);
                $user->assignRole($this->role);
            }
            // Handle User Studios
            foreach ($this->userStudios as $item) {
                UserStudioRepository::createIfNotExist([
                    'user_id' => $objId,
                    'studio_id' => Crypt::decrypt($item['id']),
                ]);
            }
            UserStudioRepository::deleteExcept($objId, array_map(function ($item) {
                return Crypt::decrypt($item['id']);
            }, $this->userStudios));

            DB::commit();

            Alert::confirmation(
                $this,
                Alert::ICON_SUCCESS,
                "Berhasil",
                "Pengguna Berhasil Diperbarui",
                "on-dialog-confirm",
                "on-dialog-cancel",
                "Oke",
                "Tutup",
            );
        } catch (Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.account.user.detail');
    }
}
