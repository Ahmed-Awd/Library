<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ContactRepository implements ContactRepositoryInterface
{

    private Contact $contact;


    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }


    public function get()
    {
        return $this->contact->all();
    }

    public function show($contact)
    {
        return  $contact;
    }

    public function create($data)
    {
        $this->contact->create($data);
    }

    public function delete($contact)
    {
        $contact->delete();
    }
}
