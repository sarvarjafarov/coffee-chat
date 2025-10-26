<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $contacts = Contact::query()
            ->with(['company'])
            ->when($request->filled('company_id'), function ($query) use ($request): void {
                $query->where('company_id', $request->integer('company_id'));
            })
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($inner) use ($search): void {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.contacts.index', [
            'contacts' => $contacts,
            'companies' => Company::orderBy('name')->get(),
            'filters' => [
                'company_id' => $request->integer('company_id'),
                'search' => $request->string('search')->toString(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.contacts.create', [
            'companies' => Company::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $contact = Contact::create($data);

        return redirect()->route('admin.contacts.edit', $contact)
            ->with('status', 'Contact created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact): RedirectResponse
    {
        return redirect()->route('admin.contacts.edit', $contact);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact): View
    {
        $contact->load([
            'company',
            'coffeeChats' => fn ($query) => $query->orderByDesc('scheduled_at'),
        ]);

        return view('admin.contacts.edit', [
            'contact' => $contact,
            'companies' => Company::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact): RedirectResponse
    {
        $data = $this->validatedData($request, $contact);

        $contact->update($data);

        return redirect()->route('admin.contacts.edit', $contact)
            ->with('status', 'Contact updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('status', 'Contact deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedData(Request $request, ?Contact $contact = null): array
    {
        $contactId = $contact?->id;

        return $request->validate([
            'company_id' => ['nullable', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'team_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:contacts,email,' . $contactId],
            'phone' => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
