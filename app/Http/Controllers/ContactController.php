<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    //

    public function contact()
    {
        return view('front.pages.contact-us');
    }

    public function submitForm(Request $request)
    {
        // 验证请求数据
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);
        //如果 subject 为空，则设置为 '其他'
        if (empty($validated['subject'])) {
            $validated['subject'] = '其他';
        }

        try {
            // 创建并保存联系记录
            $contact = Contact::create([
                'name'    => $validated['name'],
                'email'   => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'ip'      => $request->ip(),
            ]);

            // 记录日志
            Log::channel('contact')->info('New contact form submission', [
                'id'      => $contact->id,
                'name'    => $contact->name,
                'email'   => $contact->email,
                'subject' => $contact->subject,
                'ip'      => $contact->ip,
                'submitted_at' => $contact->created_at->toDateTimeString(),
            ]);

            // 返回成功的JSON响应
            // return response()->json([
            //     'success' => true,
            //     'message' => __('contact.success_message'),
            //     'data' => [
            //         'id' => $contact->id,
            //         'created_at' => $contact->created_at->toDateTimeString(),
            //     ]
            // ], 201);
            return redirect()
                ->back()
                ->with('success', __('contact-us.success_message'));
        } catch (\Exception $e) {
            // 记录错误日志
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // 返回错误的JSON响应
            return response()->json([
                'success' => false,
                'message' => __('contact.error_message'),
            ], 500);
        }
    }
}

