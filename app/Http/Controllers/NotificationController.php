<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\DonationRequest;
use App\Models\Donor;
use App\Models\Recipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function sendNotification($id)
    {
        $donationRequest = DonationRequest::findOrFail($id);
        $bloodRequest = $donationRequest->bloodRequest;
        $recipient = Recipient::findOrFail($bloodRequest->recipient_id);
        $donor = Donor::find($donationRequest->donor_id);


        $data = [
            'status' => $bloodRequest->status,
            'recipient_name' => $recipient->name,
            'hospital_name' => $bloodRequest->hospital_name,
            'units_required' => $bloodRequest->units_required,
            'urgency_level' => $bloodRequest->urgency_level,
            'required_by_date' => $bloodRequest->required_by_date,
            'additional_info' => $bloodRequest->additional_info,
        ];

        if ($donor) {
            Mail::send('emails.donor_notification', $data, function ($message) use ($donor) {
                $message->to($donor->user->email)->subject('Blood Donation Request Update');
            });
        }

        Mail::send('emails.recipient_notification', $data, function ($message) use ($recipient) {
            $message->to($recipient->user->email)->subject('Blood Request Update');
        });

        return redirect()->back()->with('success', 'Notifications sent successfully.');
    }
}