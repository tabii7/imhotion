<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <img class="mx-auto h-16 w-auto" src="{{ asset('images/imhotion.jpg') }}" alt="Imhotion">
                <h2 class="mt-6 text-center text-3xl font-semibold text-white">
                    Create your account
                </h2>
                <p class="mt-2 text-center text-sm text-gray-300">
                    Or
                    <a href="{{ route('login') }}" class="font-medium text-brand-primary-200 hover:text-brand-primary-200">
                        sign in to your existing account
                    </a>
                </p>
            </div>

            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl py-8 px-6 shadow-2xl">
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 text-red-200 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($selectedPlan)
                    <!-- Selected Plan Info -->
                    <div class="mb-6 p-4 bg-brand-primary/20 border border-brand-primary/50 rounded-lg">
                        <h3 class="font-semibold text-white mb-2">
                            Selected Plan: {{ $selectedPlan->title }}
                        </h3>
                        <p class="text-brand-primary-200 text-sm">
                            €{{ number_format($selectedPlan->price, 0) }}/{{ str_replace('per_', '', $selectedPlan->price_unit) }}
                            @if($selectedPlan->category->slug === 'our-packs')
                                @php
                                    $days = (int) filter_var($selectedPlan->title, FILTER_SANITIZE_NUMBER_INT);
                                    $totalPrice = $selectedPlan->price * $days;
                                @endphp
                                - Total: €{{ number_format($totalPrice, 0) }}
                            @endif
                        </p>
                        <p class="text-brand-primary-200 text-xs mt-1">
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
                        <label for="name" class="block text-sm font-medium text-white">
                            Full Name
                        </label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" autocomplete="name" required 
                                   value="{{ old('name') }}" autofocus
                                   class="appearance-none block w-full px-3 py-3 border border-white/20 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent bg-white/10 text-white sm:text-sm">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-white">
                            Email address
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                   value="{{ old('email') }}"
                                   class="appearance-none block w-full px-3 py-3 border border-white/20 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent bg-white/10 text-white sm:text-sm">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-white">
                            Password
                        </label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                   class="appearance-none block w-full px-3 py-3 border border-white/20 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent bg-white/10 text-white sm:text-sm">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-white">
                            Confirm Password
                        </label>
                        <div class="mt-1">
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                   class="appearance-none block w-full px-3 py-3 border border-white/20 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent bg-white/10 text-white sm:text-sm">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-colors duration-300">
                            @if($selectedPlan)
                                Register & Pay
                            @else
                                Create Account
                            @endif
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-white/20"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-transparent text-gray-300">Or register with</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="w-full inline-flex justify-center py-3 px-4 border border-white/20 rounded-lg shadow-sm bg-white/10 text-sm font-medium text-white hover:bg-white/20 transition-colors duration-300">
                            <img src="{{ asset('images/google-g-64.png') }}" alt="Google" class="h-5 w-5 mr-3"> Continue with Google
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
