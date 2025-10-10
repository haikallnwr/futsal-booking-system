<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class DashboardDevContactController extends Controller
{
    public function index()
    {
        return view('backend.dev.contact.contact', [
            'title' => 'Afuta | Contact',
            'chat' =>  Contact::latest()->get()
        ]);
    }
    public function show(Contact $contact)
    {
        return View('backend.dev.contact.show', [
            'title' => 'Chat Customers',
            'chat' =>  $contact
        ]);
    }
    public function destroy(Contact $contact)
    {
        $data = Contact::destroy($contact->id);
        return redirect('/dashboarddev/contact')->with('success', 'Post has been deleted!');
    }
}
