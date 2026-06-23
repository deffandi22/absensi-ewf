<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $divisions = Division::withCount([
                'users as employees_count' => function ($query) {
                    $query->where('role', 'employee');
                },
                'users as leaders_count' => function ($query) {
                    $query->where('role', 'leader');
                },
            ])
            ->when($search, function ($query) use ($search) {
                $query->where('division_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.divisions.index', compact('divisions', 'search'));
    }

    public function create()
    {
        return view('admin.divisions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'division_name' => ['required', 'string', 'max:100', 'unique:divisions,division_name'],
            'description' => ['nullable', 'string', 'max:255'],
        ], [
            'division_name.required' => 'Nama divisi wajib diisi.',
            'division_name.unique' => 'Nama divisi sudah digunakan.',
            'division_name.max' => 'Nama divisi maksimal 100 karakter.',
            'description.max' => 'Keterangan maksimal 255 karakter.',
        ]);

        Division::create($validated);

        return redirect('/admin/divisions')
            ->with('success', 'Data divisi berhasil ditambahkan.');
    }

    public function edit(Division $division)
    {
        return view('admin.divisions.edit', compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        $validated = $request->validate([
            'division_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('divisions', 'division_name')->ignore($division->id),
            ],
            'description' => ['nullable', 'string', 'max:255'],
        ], [
            'division_name.required' => 'Nama divisi wajib diisi.',
            'division_name.unique' => 'Nama divisi sudah digunakan.',
            'division_name.max' => 'Nama divisi maksimal 100 karakter.',
            'description.max' => 'Keterangan maksimal 255 karakter.',
        ]);

        $division->update($validated);

        return redirect('/admin/divisions')
            ->with('success', 'Data divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        if ($division->users()->count() > 0) {
            return redirect('/admin/divisions')
                ->with('warning', 'Divisi tidak dapat dihapus karena masih digunakan oleh pengguna atau karyawan.');
        }

        $division->delete();

        return redirect('/admin/divisions')
            ->with('success', 'Data divisi berhasil dihapus.');
    }
}