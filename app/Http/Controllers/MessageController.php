<?php

namespace App\Http\Controllers;

use App\Models\ItemReport;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $messages = Message::query()
            ->with(['report', 'sender', 'recipient'])
            ->where(function ($query) use ($request): void {
                $query->where('sender_id', $request->user()->id)
                    ->orWhere('recipient_id', $request->user()->id);
            })
            ->latest()
            ->paginate(15);

        return view('messages.index', ['messages' => $messages]);
    }

    public function store(Request $request, ItemReport $report): RedirectResponse
    {
        abort_unless($report->status === 'approved', 404);
        abort_if($report->user_id === $request->user()->id, 422, 'You cannot message your own report.');

        $attributes = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        Message::query()->create([
            'item_report_id' => $report->id,
            'sender_id' => $request->user()->id,
            'recipient_id' => $report->user_id,
            'body' => $attributes['body'],
        ]);

        return redirect()->route('messages.index')->with('status', 'Message sent.');
    }
}
