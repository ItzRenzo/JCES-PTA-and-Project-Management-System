<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Parent Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your parent account information.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="pt-4 border-t border-gray-200">
            <h3 class="text-md font-medium text-gray-900 mb-4">Emergency Contact Information</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-3">This information will be used in case of emergencies involving your child(ren).</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="emergency_contact_name" :value="__('Emergency Contact Name')" />
                        <x-text-input id="emergency_contact_name" name="emergency_contact_name" type="text" class="mt-1 block w-full" :value="old('emergency_contact_name', $user->emergency_contact_name ?? '')" />
                    </div>
                    <div>
                        <x-input-label for="emergency_contact_phone" :value="__('Emergency Contact Phone')" />
                        <x-text-input id="emergency_contact_phone" name="emergency_contact_phone" type="text" class="mt-1 block w-full" :value="old('emergency_contact_phone', $user->emergency_contact_phone ?? '')" />
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>