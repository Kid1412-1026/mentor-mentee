<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your personal information')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            @if(auth()->user()->role === 'student')
                <flux:input wire:model="matric_no" :label="__('Matric Number')" type="text" maxlength="15" required />

                <flux:input wire:model="name" :label="__('Name')" type="text" maxlength="100" autocomplete="name" />

                <flux:input wire:model="program" :label="__('Program')" type="text" maxlength="100" />

                <flux:input wire:model="email" :label="__('Email')" type="email" maxlength="100" readonly autocomplete="email" />

                <flux:input wire:model="intake" :label="__('Intake')" type="number" />

                <flux:input wire:model="phone" :label="__('Phone')" type="text" maxlength="15" autocomplete="tel" />

                <flux:select wire:model="state" :label="__('State')">
                    <option value="">Select a state</option>
                    <option value="Johor">Johor</option>
                    <option value="Kedah">Kedah</option>
                    <option value="Kelantan">Kelantan</option>
                    <option value="Melaka">Melaka</option>
                    <option value="Negeri Sembilan">Negeri Sembilan</option>
                    <option value="Pahang">Pahang</option>
                    <option value="Perak">Perak</option>
                    <option value="Perlis">Perlis</option>
                    <option value="Pulau Pinang">Pulau Pinang</option>
                    <option value="Sabah">Sabah</option>
                    <option value="Sarawak">Sarawak</option>
                    <option value="Selangor">Selangor</option>
                    <option value="Terengganu">Terengganu</option>
                    <option value="W.P. Kuala Lumpur">W.P. Kuala Lumpur</option>
                    <option value="W.P. Labuan">W.P. Labuan</option>
                    <option value="W.P. Putrajaya">W.P. Putrajaya</option>
                </flux:select>

                <flux:input wire:model="address" :label="__('Address')" type="text" maxlength="255" />

                <flux:input wire:model="motto" :label="__('Motto')" type="text" maxlength="255" />

                <flux:input wire:model="faculty" :label="__('Faculty')" type="text" maxlength="255" readonly value="Faculty of Computing and Informatics (FKI)" />
            @else
                <flux:input wire:model="name" :label="__('Name')" type="text" maxlength="100" autocomplete="name" />

                <flux:input wire:model="phone" :label="__('Phone')" type="text" maxlength="15" autocomplete="tel" />

                <flux:input wire:model="email" :label="__('Email')" type="email" maxlength="100" readonly autocomplete="email" />

                <flux:input wire:model="faculty" :label="__('Faculty')" type="text" maxlength="255" readonly />
                <flux:input wire:model="pose" :label="__('Position')" type="text" maxlength="100" />
            @endif

            <div class="mb-4 space-y-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Image</label>

                <!-- Current Image -->
                <div class="flex items-center space-x-4">
                    <div class="h-24 w-24 overflow-hidden rounded-lg bg-gray-100 dark:bg-zinc-800">
                        @if($currentImage)
                            <img src="{{ Storage::disk('public')->url($currentImage) }}"
                                 alt="Current profile image"
                                 class="h-full w-full object-cover">
                        @else
                            <img src="{{ asset('images/default-avatar.png') }}"
                                 alt="Default profile image"
                                 class="h-full w-full object-cover">
                        @endif
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Current Image</span>
                </div>

                <!-- Image Upload Input -->
                <input wire:model="newImage"
                       type="file"
                       accept="image/*"
                       class="mt-1">

                <!-- Preview Selected Image -->
                @if($newImage && $newImage->temporaryUrl())
                    <div class="flex items-center space-x-4">
                        <div class="h-24 w-24 overflow-hidden rounded-lg bg-gray-100 dark:bg-zinc-800">
                            <img src="{{ $newImage->temporaryUrl() }}"
                                 alt="Selected profile image"
                                 class="h-full w-full object-cover">
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Selected Image</span>
                    </div>
                @endif

                @error('newImage')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
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

















