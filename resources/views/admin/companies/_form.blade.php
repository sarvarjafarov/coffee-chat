<div class="form-group">
    <label for="name">Company Name</label>
    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $company->name ?? '') }}" required>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="industry">Industry</label>
        <input type="text" id="industry" name="industry" class="form-control" value="{{ old('industry', $company->industry ?? '') }}">
    </div>
    <div class="form-group col-md-6">
        <label for="location">Location</label>
        <input type="text" id="location" name="location" class="form-control" value="{{ old('location', $company->location ?? '') }}">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="website">Website</label>
        <input type="url" id="website" name="website" class="form-control" value="{{ old('website', $company->website ?? '') }}">
    </div>
    <div class="form-group col-md-6">
        <label for="linkedin_url">LinkedIn URL</label>
        <input type="url" id="linkedin_url" name="linkedin_url" class="form-control" value="{{ old('linkedin_url', $company->linkedin_url ?? '') }}">
    </div>
</div>

<div class="form-group">
    <label for="notes">Notes</label>
    <textarea id="notes" name="notes" rows="4" class="form-control">{{ old('notes', $company->notes ?? '') }}</textarea>
</div>
