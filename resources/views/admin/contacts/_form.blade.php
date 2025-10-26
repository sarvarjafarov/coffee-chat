<div class="form-row">
    <div class="form-group col-md-6">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $contact->name ?? '') }}" required>
    </div>
    <div class="form-group col-md-6">
        <label for="company_id">Company</label>
        <select name="company_id" id="company_id" class="form-control">
            <option value="">— Select company —</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}" @selected(old('company_id', $contact->company_id ?? '') == $company->id)>{{ $company->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-4">
        <label for="position">Position</label>
        <input type="text" id="position" name="position" class="form-control" value="{{ old('position', $contact->position ?? '') }}">
    </div>
    <div class="form-group col-md-4">
        <label for="team_name">Team name</label>
        <input type="text" id="team_name" name="team_name" class="form-control" value="{{ old('team_name', $contact->team_name ?? '') }}">
    </div>
    <div class="form-group col-md-4">
        <label for="location">City / Location</label>
        <input type="text" id="location" name="location" class="form-control" value="{{ old('location', $contact->location ?? '') }}">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $contact->email ?? '') }}">
    </div>
    <div class="form-group col-md-6">
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $contact->phone ?? '') }}">
    </div>
</div>

<div class="form-group">
    <label for="linkedin_url">LinkedIn URL</label>
    <input type="url" id="linkedin_url" name="linkedin_url" class="form-control" value="{{ old('linkedin_url', $contact->linkedin_url ?? '') }}">
</div>

<div class="form-group">
    <label for="notes">Notes</label>
    <textarea id="notes" name="notes" rows="4" class="form-control">{{ old('notes', $contact->notes ?? '') }}</textarea>
</div>
