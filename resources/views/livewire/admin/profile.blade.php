<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your personal information')">
        <form wire:submit="updateProfile" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('Name')" type="text" maxlength="100" />

            <flux:input wire:model="email" :label="__('Email')" type="email" maxlength="100" readonly />

            <flux:input wire:model="phone" :label="__('Phone')" type="text" maxlength="15" />

            <flux:input wire:model="faculty" :label="__('Faculty')" type="text" maxlength="255" />

            <flux:input wire:model="pose" :label="__('Position')" type="text" maxlength="100" />

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Image</label>
                <input wire:model="newImage" type="file" accept="image/*" class="mt-1">
            </div>

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div>
                    <flux:text class="mt-4">
                        {{ __('Your email address is unverified.') }}

                        <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                            {{ __('Click here to re-send the verification email.') }}
                        </flux:link>
                    </flux:text>

                    @if (session('status') === 'verification-link-sent')
                        <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </flux:text>
                    @endif
                </div>
            @endif

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>

