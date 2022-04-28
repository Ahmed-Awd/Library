<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use App\Repositories\ContactRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class ContactController extends Controller
{
    private ContactRepositoryInterface $contactRepository;


    public function __construct(ContactRepositoryInterface $contactRepository)
    {

        $this->contactRepository = $contactRepository;
    }

    public function index(): JsonResponse
    {
        $data = $this->contactRepository->get();
        return response()->json(['contacts' => $data]);
    }


    public function store(StoreContactRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->contactRepository->create($data);
        return response()->json(['message' => Lang::get('messages.contact.create')]);
    }


    public function show(Contact $contact): JsonResponse
    {
        $contact = $this->contactRepository->show($contact);
        return response()->json(['data' => $contact]);
    }

    public function destroy(Contact $contact): JsonResponse
    {
        $this->contactRepository->delete($contact);
        return response()->json(["message" => Lang::get('messages.contact.delete')]);
    }
}
