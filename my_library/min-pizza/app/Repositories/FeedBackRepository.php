<?php

namespace App\Repositories;

use App\Mail\ReplayToFeedbackMail;
use App\Models\FeedBack;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;

class FeedBackRepository implements FeedBackRepositoryInterface
{
    private FeedBack $feedBack;

    public function __construct(FeedBack $feedBack)
    {
        $this->feedBack = $feedBack;
    }

    public function get($search, $order)
    {
        $query = $this->feedBack;
        if ($search != '') {
            $query = searchIt($query, ['email','name','phone'], $search);
        }
        if ($order['by'] != false && $order['type'] != "none") {
            $query = $query->orderBy($order['by'], $order['type']);
        } else {
            $query = $query->orderBy('created_at', 'desc');
        }
        return $query->paginate(15);
    }

    public function store($data)
    {
        $this->feedBack->create($data);
    }

    public function show($feedback)
    {
        $one = $this->feedBack->findOrFail($feedback);
        $one->update(['new' => false]);
        return $one;
    }

    public function delete($feedback)
    {
        $feedback->delete();
    }

    public function replay($feedback, $data)
    {
        if ($feedback->replay_subject != null) {
            return Lang::get('messages.feedback.already');
        }
        $feedback->update($data);
        $record = $this->feedBack->find($feedback->id);
        Mail::to($feedback->email)->send(new ReplayToFeedbackMail($record));
        return Lang::get('messages.feedback.replay');
    }
}
