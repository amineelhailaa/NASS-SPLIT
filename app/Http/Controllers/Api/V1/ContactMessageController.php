<?php

namespace App\Http\Controllers\Api\V1;

use App\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ContactMessageController extends Controller
{
    use ApiResponses;

    public function store(ContactMessageRequest $request)
    {
        $message = ContactMessage::create($request->validated());

        return $this->createdResponse($message, 'Message sent successfully');
    }

    public function index(Request $request)
    {
        Gate::authorize('admin');

        $status = $request->query('status');

        $messages = ContactMessage::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('id')
            ->paginate(15);

        return $this->successResponse($messages);
    }

    public function show(ContactMessage $contactMessage)
    {
        Gate::authorize('admin');

        return $this->successResponse($contactMessage);
    }

    public function updateStatus(Request $request, ContactMessage $contactMessage)
    {
        Gate::authorize('admin');

        $request->validate([
            'status' => ['required', Rule::in(['pending', 'treated'])],
        ]);

        $contactMessage->update(['status' => $request->status]);

        return $this->successResponse($contactMessage, 'Status updated');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        Gate::authorize('admin');

        $contactMessage->delete();

        return $this->noContentResponse();
    }
}