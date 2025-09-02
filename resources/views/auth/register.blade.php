<x-guest-layout>
    <div class="flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <img class="mx-auto h-16 w-auto" src="{{ asset('images/imhotion-blue.png') }}" alt="Imhotion">
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900" style="font-family: var(--font-sans)">
                    Create your account
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Or
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        sign in to your existing account
                    </a>
                </p>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li style="font-family: var(--font-sans)">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($selectedPlan)
                    <!-- Selected Plan Info -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h3 class="font-semibold text-blue-900 mb-2" style="font-family: var(--font-sans)">
                            Selected Plan: {{ $selectedPlan->title }}
                        </h3>
                        <p class="text-blue-700 text-sm" style="font-family: var(--font-sans)">
                            €{{ number_format($selectedPlan->price, 0) }}/{{ str_replace('per_', '', $selectedPlan->price_unit) }}
                            @if($selectedPlan->category->slug === 'our-packs')
                                @php
                                    $days = (int) filter_var($selectedPlan->title, FILTER_SANITIZE_NUMBER_INT);
                                    $totalPrice = $selectedPlan->price * $days;
                                @endphp
                                - Total: €{{ number_format($totalPrice, 0) }}
                            @endif
                        </p>
                        <p class="text-blue-600 text-xs mt-1" style="font-family: var(--font-sans)">
                            After registration, you'll be redirected to secure Mollie payment.
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    @if($selectedPlan)
                        <input type="hidden" name="pricing_item_id" value="{{ $selectedPlan->id }}">
                    @endif

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700" style="font-family: var(--font-sans)">
                            Full Name
                        </label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" autocomplete="name" required 
                                   value="{{ old('name') }}" autofocus
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   style="font-family: var(--font-sans)">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700" style="font-family: var(--font-sans)">
                            Email address
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                   value="{{ old('email') }}"
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   style="font-family: var(--font-sans)">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700" style="font-family: var(--font-sans)">
                            Password
                        </label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   style="font-family: var(--font-sans)">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700" style="font-family: var(--font-sans)">
                            Confirm Password
                        </label>
                        <div class="mt-1">
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   style="font-family: var(--font-sans)">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                style="font-family: var(--font-sans); background-color: var(--brand-primary);">
                            @if($selectedPlan)
                                Register & Pay
                            @else
                                Create Account
                            @endif
                        </button>
                    </div>
                </form>

                <div class="mt-4">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Or register with</span>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-3">
                        <a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <img src="{{ asset('images/google-g-64.png') }}" alt="Google" class="h-5 w-5 mr-3"> Continue with Google
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
