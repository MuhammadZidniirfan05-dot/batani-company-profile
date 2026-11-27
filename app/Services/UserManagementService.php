<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserManagementService
{
    /**
     * Membuat akun pengguna baru.
     */
    public function create(
        int|string|null $actorId,
        array $data
    ): User {
        return DB::transaction(function () use (
            $actorId,
            $data
        ): User {

            $actor = User::query()
                ->lockForUpdate()
                ->find($actorId);

            abort_unless(
                $actor instanceof User,
                403
            );

            Gate::forUser($actor)
                ->authorize('create', User::class);

            $validated = $this->validateData($data);

            $user = new User();

            $user->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make(
                    $validated['password']
                ),
            ]);

            $user->forceFill([
                'role' => $validated['role'],
                'is_active' => (bool) $validated['is_active'],
            ]);

            $user->save();

            return $user;

        }, 3);
    }

    /**
     * Memperbarui akun pengguna.
     */
    public function update(
        int|string|null $actorId,
        User $record,
        array $data
    ): User {
        return DB::transaction(function () use (
            $actorId,
            $record,
            $data
        ): User {

            // Mengunci akun selama perubahan hak akses.
            $users = User::query()
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $actor = $users->find($actorId);

            $target = $users->find($record->getKey());

            abort_unless(
                $actor instanceof User,
                403
            );

            abort_unless(
                $target instanceof User,
                404
            );

            Gate::forUser($actor)
                ->authorize('update', $target);

            // Mencegah perubahan role dan status akun sendiri.
            if ($actor->is($target)) {

                $data['role'] = $target->role;
                $data['is_active'] = $target->is_active;

            }

            $validated = $this->validateData(
                $data,
                $target
            );

            $newRole = $validated['role'];
            $newStatus = (bool) $validated['is_active'];

            // Menghitung Super Admin aktif.
            $activeSuperAdmins = $users
                ->filter(
                    fn (User $user): bool =>
                        $user->isSuperAdmin()
                )
                ->count();

            // Mencegah hilangnya Super Admin aktif terakhir.
            if (
                $target->isSuperAdmin()
                && (
                    $newRole !== User::ROLE_SUPER_ADMIN
                    || ! $newStatus
                )
                && $activeSuperAdmins <= 1
            ) {
                throw ValidationException::withMessages([
                    'data.role' =>
                        'Super Admin aktif terakhir tidak dapat diturunkan hak aksesnya atau dinonaktifkan.',
                ]);
            }

            $target->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $target->forceFill([
                'role' => $newRole,
                'is_active' => $newStatus,
            ]);

            // Reset verifikasi jika alamat email berubah.
            if ($target->isDirty('email')) {
                $target->email_verified_at = null;
            }

            // Password hanya diubah jika diisi.
            if (filled($validated['password'] ?? null)) {

                $target->password = Hash::make(
                    $validated['password']
                );

                $target->remember_token = null;

            }

            $target->save();

            return $target;

        }, 3);
    }

    /**
     * Validasi data pada backend.
     */
    private function validateData(
        array $data,
        ?User $record = null
    ): array {

        $emailRule = Rule::unique('users', 'email');

        if ($record !== null) {
            $emailRule->ignore($record);
        }

        $validated = Validator::make(
            ['data' => $data],
            [

                'data.name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'data.email' => [
                    'required',
                    'email',
                    'max:255',
                    $emailRule,
                ],

                'data.password' => [
                    $record === null
                        ? 'required'
                        : 'nullable',

                    'string',
                    'min:12',
                    'max:72',

                    function (
                        string $attribute,
                        mixed $value,
                        \Closure $fail
                    ): void {

                        if (
                            is_string($value)
                            && strlen($value) > 72
                        ) {
                            $fail(
                                'Password tidak boleh melebihi 72 byte.'
                            );
                        }
                    },
                ],

                'data.role' => [
                    'required',
                    Rule::in([
                        User::ROLE_SUPER_ADMIN,
                        User::ROLE_EDITOR,
                        User::ROLE_USER,
                    ]),
                ],

                'data.is_active' => [
                    'required',
                    'boolean',
                ],

            ]
        )->validate();

        return $validated['data'];
    }
}
