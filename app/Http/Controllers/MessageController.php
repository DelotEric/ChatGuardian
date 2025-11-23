<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Mail\NewMessageNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $messages = Message::inbox(auth()->id())->paginate(20);
        $unreadCount = Message::inbox(auth()->id())->unread()->count();

        return view('messages.index', compact('messages', 'unreadCount'));
    }

    public function sent(): View
    {
        $messages = Message::sent(auth()->id())->paginate(20);

        return view('messages.sent', compact('messages'));
    }

    public function create(): View
    {
        $users = User::where('id', '!=', auth()->id())->orderBy('name')->get();

        return view('messages.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'recipient_id' => ['required', 'exists:users,id'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $data['sender_id'] = auth()->id();

        $message = Message::create($data);

        // Send email notification
        $recipient = User::find($data['recipient_id']);
        if ($recipient->email) {
            try {
                Mail::to($recipient->email)->send(new NewMessageNotification($message));
            } catch (\Exception $e) {
                // Log error but don't block message sending
            }
        }

        return redirect()->route('messages.index')->with('status', 'Message envoyé avec succès.');
    }

    public function show(Message $message): View
    {
        // Check authorization
        if ($message->recipient_id !== auth()->id() && $message->sender_id !== auth()->id()) {
            abort(403);
        }

        // Mark as read if recipient is viewing
        if ($message->recipient_id === auth()->id()) {
            $message->markAsRead();
        }

        return view('messages.show', compact('message'));
    }

    public function destroy(Message $message): RedirectResponse
    {
        // Check authorization
        if ($message->recipient_id !== auth()->id() && $message->sender_id !== auth()->id()) {
            abort(403);
        }

        // Soft delete based on user
        if ($message->sender_id === auth()->id()) {
            $message->update(['sender_deleted' => true]);
        }
        if ($message->recipient_id === auth()->id()) {
            $message->update(['recipient_deleted' => true]);
        }

        // Permanently delete if both deleted
        if ($message->sender_deleted && $message->recipient_deleted) {
            $message->delete();
        }

        return back()->with('status', 'Message supprimé.');
    }

    public function reply(Message $message): View
    {
        // Check authorization
        if ($message->recipient_id !== auth()->id()) {
            abort(403);
        }

        $users = User::where('id', '!=', auth()->id())->orderBy('name')->get();

        return view('messages.create', compact('message', 'users'));
    }
}
