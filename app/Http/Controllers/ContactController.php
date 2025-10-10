<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact', [
            'title' => 'Afuta | Contact Kami'
        ]);
    }

    public function StoreContact(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:3|max:25',
            'email' => 'required|email:dns|',
            'subject' => 'required|min:3|max:25',
            'message' => 'required|min:3|max:255'
        ]);

        Contact::create($data);
        return redirect('/contact')->with('success', 'Pesan Berhasil Terkirim');
    }
}
