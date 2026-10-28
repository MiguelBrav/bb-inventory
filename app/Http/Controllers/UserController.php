<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\PdfReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->orderBy('name')
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(
            new UsersExport,
            'reportes_usuarios_'.now()->format('Y-m-d').'.xlsx',
        );
    }

    public function exportPdf(PdfReportService $pdfReport): Response
    {
        return $pdfReport->download(
            new UsersExport,
            'Reporte de usuarios',
            'reportes_usuarios_'.now()->format('Y-m-d').'.pdf',
        );
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'is_admin' => false,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user): View
    {
        abort_if($user->is_admin, 404);

        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        abort_if($user->is_admin, 404);

        $validated = $request->validated();

        if (filled($validated['password'] ?? null)) {
            $user->password = $validated['password'];
        }

        unset($validated['password']);

        $user->fill($validated);
        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->is_admin, 404);

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
