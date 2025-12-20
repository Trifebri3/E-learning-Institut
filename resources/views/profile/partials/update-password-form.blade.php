
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <section>
                    <header class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Update Password') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Ensure your account is using a long, random password to stay secure.') }}
                        </p>
                    </header>

                    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('put')

                        <div>
                            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Current Password') }} <span class="text-red-500">*</span>
                            </label>
                            <input id="update_password_current_password"
                                   name="current_password"
                                   type="password"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                                   autocomplete="current-password"
                                   required>
                            @error('current_password', 'updatePassword')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="update_password_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('New Password') }} <span class="text-red-500">*</span>
                            </label>
                            <input id="update_password_password"
                                   name="password"
                                   type="password"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                                   autocomplete="new-password"
                                   required
                                   minlength="8">
                            @error('password', 'updatePassword')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Password harus minimal 8 karakter
                            </p>
                        </div>

                        <div>
                            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Confirm Password') }} <span class="text-red-500">*</span>
                            </label>
                            <input id="update_password_password_confirmation"
                                   name="password_confirmation"
                                   type="password"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                                   autocomplete="new-password"
                                   required>
                            @error('password_confirmation', 'updatePassword')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 focus:scale-105">
                                <i class="fas fa-save mr-2"></i>
                                {{ __('Save') }}
                            </button>

                            @if (session('status') === 'password-updated')
                                <p x-data="{ show: true }"
                                   x-show="show"
                                   x-transition
                                   x-init="setTimeout(() => show = false, 2000)"
                                   class="text-sm text-green-600 dark:text-green-400 flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ __('Password updated successfully!') }}
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
    // Password strength indicator (optional enhancement)
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('update_password_password');
        const passwordStrength = document.createElement('div');
        passwordStrength.className = 'mt-2 text-xs';
        passwordInput.parentNode.appendChild(passwordStrength);

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let message = '';
            let color = 'text-red-500';

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;

            switch(strength) {
                case 0:
                case 1:
                    message = 'Password lemah';
                    color = 'text-red-500';
                    break;
                case 2:
                    message = 'Password cukup';
                    color = 'text-yellow-500';
                    break;
                case 3:
                    message = 'Password kuat';
                    color = 'text-green-500';
                    break;
                case 4:
                    message = 'Password sangat kuat';
                    color = 'text-green-600';
                    break;
            }

            passwordStrength.textContent = message;
            passwordStrength.className = `mt-2 text-xs ${color} font-medium`;
        });
    });
</script>

