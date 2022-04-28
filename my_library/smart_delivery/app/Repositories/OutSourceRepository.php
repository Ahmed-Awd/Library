<?php

/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 4/7/2021
 * Time: 11:17 AM
 */

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class OutSourceRepository implements OutSourceRepositoryInterface
{

    private User $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function get()
    {
        try {
            return $this->user->role('outsource')->paginate(10);
        } catch (\Exception $error) {
            return [];
        }
    }

    public function show($user)
    {
        return $this->user->where('id', $user->id)->first();
    }

    public function store($data)
    {
        $password_generate = Str::random(6);
        $data['password']     = Hash::make($password_generate);
        $current = $this->user->create($data);
        $current->assignRole('outsource');
        $this->mail($current->email, $password_generate);
    }

    public function update($user, $outsource)
    {
        $data['name'] = $outsource['name'];
        $data['username'] = $outsource['username'];
        $data['email'] = $outsource['email'];
        $data['phone'] = $outsource['phone'];
        $data['country_code'] = $outsource['country_code'];
        $user->update($data);
    }

    public function disabled($user)
    {
        $user->update(['is_disabled' => !$user->is_disabled]);
    }

    public function mail($email_address, $password_generate)
    {
        Mail::send(
            'emails.password',
            ['password' => $password_generate],
            function (Message $message) use ($email_address) {
                $message->from('theimaginaryking@gmail.com');
                $message->to($email_address);
                $message->subject("your password");
            }
        );
    }

    public function delete($user)
    {
        $user->delete();
    }
}
