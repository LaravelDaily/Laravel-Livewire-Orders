<div>
    <!-- Password -->
    <div class="mt-4">
        <x-input-label for="password" :value="__('Password')" />

        <div class="flex mt-1 mb-2">
            <div class="relative flex-1 col-span-4" x-data="{ show: true }">
                <input class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       id="password"
                       :type="show ? 'password' : 'text'"
                       name="password"
                       wire:model="password"
                       required autocomplete="new-password" />

                <button type="button" class="flex absolute inset-y-0 right-0 items-center pr-3" @click="show = !show" :class="{'hidden': !show, 'block': show }">
                    <!-- Heroicon name: eye -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
                <button type="button" class="flex absolute inset-y-0 right-0 items-center pr-3" @click="show = !show" :class="{'block': !show, 'hidden': show }">
                    <!-- Heroicon name: eye-slash -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <div class="flex items-center place-content-end ml-1">
                <x-primary-button wire:click="generatePassword" type="button">Generate</x-primary-button>
            </div>
        </div>
        <span class="text-sm">
            <span class="font-semibold">Password strength:</span> {{ $strengthLevels[$strengthScore] ?? 'Weak' }}
        </span>

        <progress value="{{ $strengthScore }}" max="4" class="w-full"></progress>

        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Confirm Password -->
    <div class="mt-4">
        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

        <x-text-input id="password_confirmation" class="block mt-1 w-full"
                      type="password"
                      wire:model="passwordConfirmation"
                      name="password_confirmation" required />

        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>
</div>
