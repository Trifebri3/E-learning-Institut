
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <section>
                    <header class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Profile Information') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Update your account's profile information and email address.") }}
                        </p>
                    </header>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('patch')

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input id="name"
                                   name="name"
                                   type="text"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   autofocus
                                   autocomplete="name">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Email') }} <span class="text-red-500">*</span>
                            </label>
                            <input id="email"
                                   name="email"
                                   type="email"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   autocomplete="email">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                    <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        {{ __('Your email address is unverified.') }}
                                    </p>

                                    <button form="send-verification"
                                            class="mt-2 text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 font-medium transition-colors duration-200">
                                        <i class="fas fa-paper-plane mr-1"></i>
                                        {{ __('Click here to re-send the verification email.') }}
                                    </button>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 text-sm text-green-600 dark:text-green-400 flex items-center">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            {{ __('A new verification link has been sent to your email address.') }}
                                        </p>
                                    @endif
                                </div>
                            @elseif ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && $user->hasVerifiedEmail())
                                <div class="mt-2 flex items-center text-sm text-green-600 dark:text-green-400">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ __('Email address verified.') }}
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 focus:scale-105">
                                <i class="fas fa-save mr-2"></i>
                                {{ __('Save') }}
                            </button>

                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }"
                                   x-show="show"
                                   x-transition
                                   x-init="setTimeout(() => show = false, 2000)"
                                   class="text-sm text-green-600 dark:text-green-400 flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ __('Profile updated successfully!') }}
                                </p>
                            @endif
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real-time validation for name field
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');

        if (nameInput) {
            nameInput.addEventListener('input', function() {
                validateName(this);
            });
        }

        if (emailInput) {
            emailInput.addEventListener('input', function() {
                validateEmail(this);
            });
        }

        function validateName(field) {
            const value = field.value.trim();
            const errorElement = field.parentElement.querySelector('.error-message');

            // Remove existing error styling
            field.classList.remove('border-red-500', 'dark:border-red-400');

            if (errorElement) {
                errorElement.classList.add('hidden');
            }

            // Check required
            if (!value) {
                showError(field, 'Name is required');
                return false;
            }

            // Check min length
            if (value.length < 2) {
                showError(field, 'Name must be at least 2 characters');
                return false;
            }

            // Check max length
            if (value.length > 255) {
                showError(field, 'Name must not exceed 255 characters');
                return false;
            }

            // Valid
            field.classList.add('border-green-500', 'dark:border-green-400');
            return true;
        }

        function validateEmail(field) {
            const value = field.value.trim();
            const errorElement = field.parentElement.querySelector('.error-message');

            // Remove existing error styling
            field.classList.remove('border-red-500', 'dark:border-red-400');

            if (errorElement) {
                errorElement.classList.add('hidden');
            }

            // Check required
            if (!value) {
                showError(field, 'Email is required');
                return false;
            }

            // Check email format
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(value)) {
                showError(field, 'Please enter a valid email address');
                return false;
            }

            // Valid
            field.classList.add('border-green-500', 'dark:border-green-400');
            return true;
        }

        function showError(field, message) {
            field.classList.add('border-red-500', 'dark:border-red-400');

            // Create or update error message
            let errorElement = field.parentElement.querySelector('.error-message');
            if (!errorElement) {
                errorElement = document.createElement('p');
                errorElement.className = 'mt-2 text-sm text-red-600 dark:text-red-400 flex items-center error-message';
                field.parentElement.appendChild(errorElement);
            }

            errorElement.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>${message}`;
            errorElement.classList.remove('hidden');
        }

        // Form submission validation
        const form = document.querySelector('form[action="{{ route('profile.update') }}"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                const isNameValid = validateName(nameInput);
                const isEmailValid = validateEmail(emailInput);

                if (!isNameValid || !isEmailValid) {
                    e.preventDefault();

                    // Scroll to first error
                    const firstError = document.querySelector('.border-red-500');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        }
    });
</script>

<style>
    input:valid:not(:placeholder-shown) {
        border-color: #22c55e;
    }

    input:invalid:not(:placeholder-shown) {
        border-color: #ef4444;
    }
</style>

