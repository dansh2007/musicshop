<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-6">
        <div class="bg-neutral-900 border border-white/10 shadow-2xl rounded-3xl p-6 sm:p-8">
            <div class="max-w-xl space-y-2 mb-4">
                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Профіль</p>
                <h2 class="text-2xl font-semibold text-white">Особисті дані</h2>
                <p class="text-gray-400 text-sm">Оновіть контактні дані та пароль.</p>
            </div>
            <div class="space-y-6">
                <div class="bg-neutral-950 border border-white/10 rounded-2xl p-5">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="bg-neutral-950 border border-white/10 rounded-2xl p-5">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="bg-neutral-950 border border-white/10 rounded-2xl p-5">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
