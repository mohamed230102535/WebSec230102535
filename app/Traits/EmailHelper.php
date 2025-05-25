<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;

trait EmailHelper
{
    protected function sendSimpleEmail($to, $subject, $view, $data = [])
    {
        try {
            Mail::send($view, $data, function($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });
            return true;
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }
}
