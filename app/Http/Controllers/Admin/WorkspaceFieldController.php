<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkspaceField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WorkspaceFieldController extends Controller
{
    protected array $types = [
        'text' => 'Short text',
        'textarea' => 'Long text',
        'number' => 'Number',
        'select' => 'Dropdown',
        'multiselect' => 'Multi-select',
        'date' => 'Date',
        'datetime' => 'Date & time',
        'boolean' => 'Checkbox',
    ];

    public function index(): View
    {
        $fields = WorkspaceField::query()
            ->orderBy('form')
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        return view('admin.workspace-fields.index', compact('fields'));
    }

    public function create(): View
    {
        return view('admin.workspace-fields.create', [
            'field' => new WorkspaceField(['form' => 'coffee_chat', 'type' => 'text']),
            'types' => $this->types,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        WorkspaceField::create($data);

        return redirect()->route('admin.workspace-fields.index')
            ->with('status', 'Workspace field created successfully.');
    }

    public function edit(WorkspaceField $workspaceField): View
    {
        return view('admin.workspace-fields.edit', [
            'field' => $workspaceField,
            'types' => $this->types,
        ]);
    }

    public function update(Request $request, WorkspaceField $workspaceField): RedirectResponse
    {
        $data = $this->validated($request, $workspaceField);

        $workspaceField->update($data);

        return redirect()->route('admin.workspace-fields.index')
            ->with('status', 'Workspace field updated successfully.');
    }

    public function destroy(WorkspaceField $workspaceField): RedirectResponse
    {
        $workspaceField->delete();

        return redirect()->route('admin.workspace-fields.index')
            ->with('status', 'Workspace field removed.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request, ?WorkspaceField $field = null): array
    {
        $fieldId = $field?->id;

        $data = $request->validate([
            'form' => ['required', 'string', 'max:255'],
            'key' => [
                'required',
                'string',
                'max:255',
                Rule::unique('workspace_fields', 'key')->where(fn ($query) => $query->where('form', $request->input('form')))->ignore($fieldId),
            ],
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(array_keys($this->types))],
            'required' => ['nullable', 'boolean'],
            'active' => ['nullable', 'boolean'],
            'in_analytics' => ['nullable', 'boolean'],
            'position' => ['nullable', 'integer', 'min:0'],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'help_text' => ['nullable', 'string', 'max:255'],
            'options' => ['nullable', 'string'],
            'validation' => ['nullable', 'string'],
            'style' => ['nullable', 'string'],
            'meta' => ['nullable', 'string'],
        ]);

        $data['required'] = $request->boolean('required');
        $data['active'] = $request->boolean('active', true);
        $data['in_analytics'] = $request->boolean('in_analytics');
        $data['position'] = $data['position'] ?? 0;

        $data['options'] = $this->parseOptions($request->input('options'), $data['type']);
        $data['validation'] = $this->decodeJson($request->input('validation'), 'validation');
        $data['style'] = $this->decodeJson($request->input('style'), 'style');
        $data['meta'] = $this->decodeJson($request->input('meta'), 'meta');

        return $data;
    }

    protected function parseOptions(?string $raw, string $type): ?array
    {
        if (! in_array($type, ['select', 'multiselect']) || blank($raw)) {
            return null;
        }

        $lines = array_filter(array_map('trim', preg_split('/\r?\n/', $raw)));

        return collect($lines)->map(function ($line) {
            if (str_contains($line, '::')) {
                [$value, $label] = array_map('trim', explode('::', $line, 2));
                return compact('value', 'label');
            }

            return ['value' => $line, 'label' => $line];
        })->values()->all();
    }

    protected function decodeJson(?string $json, string $attribute): ?array
    {
        if (blank($json)) {
            return null;
        }

        $decoded = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages([
                $attribute => 'Invalid JSON: ' . json_last_error_msg(),
            ]);
        }

        return $decoded;
    }
}
