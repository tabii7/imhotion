<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <img class="mx-auto h-16 w-auto" src="{{ asset('images/imhotion.jpg') }}" alt="Imhotion">
                <h2 class="mt-6 text-center text-3xl font-semibold text-white">
                    Confirm your password
                </h2>
                <p class="mt-2 text-center text-sm text-gray-300">
                    This is a secure area of the application
                </p>
            </div>

            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl py-8 px-6 shadow-2xl">
                <div class="mb-6 text-sm text-gray-300">
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </div>

                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-white">
                            Password
                        </label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   class="appearance-none block w-full px-3 py-3 border border-white/20 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent bg-white/10 text-white sm:text-sm">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-colors duration-300">
                            {{ __('Confirm') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
