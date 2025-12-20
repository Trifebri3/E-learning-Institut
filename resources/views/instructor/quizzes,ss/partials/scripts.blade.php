@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add option functionality
    document.addEventListener('click', function(e) {
        // For create modal
        if (e.target.id === 'addOption') {
            const optionsContainer = document.getElementById('optionsContainer');
            addNewOption(optionsContainer);
        }

        // For edit modals
        if (e.target.classList.contains('add-option')) {
            const targetId = e.target.getAttribute('data-target');
            const optionsContainer = document.getElementById(targetId);
            addNewOption(optionsContainer);
        }

        // Remove option
        if (e.target.classList.contains('remove-option') || e.target.closest('.remove-option')) {
            const optionDiv = e.target.closest('.input-group');
            const optionsContainer = optionDiv.parentElement;
            if (optionsContainer.children.length > 2) {
                optionDiv.remove();
                updateRadioButtons(optionsContainer);
            }
        }
    });

    function addNewOption(container) {
        const optionCount = container.children.length;
        if (optionCount < 5) { // Max 5 options
            const newOption = document.createElement('div');
            newOption.className = 'input-group mb-2';
            newOption.innerHTML = `
                <div class="input-group-text">
                    <input class="form-check-input mt-0" type="radio" name="correct_option" value="${optionCount}">
                </div>
                <input type="text" class="form-control" name="options[]" placeholder="Opsi ${String.fromCharCode(65 + optionCount)}" required>
                <button type="button" class="btn btn-outline-danger remove-option">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newOption);
        } else {
            alert('Maksimal 5 opsi jawaban');
        }
    }

    function updateRadioButtons(container) {
        const radioButtons = container.querySelectorAll('input[type="radio"]');
        radioButtons.forEach((radio, index) => {
            radio.value = index;
        });
    }

    // Auto focus on modal show
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            const textarea = this.querySelector('textarea');
            if (textarea) {
                textarea.focus();
            }
        });
    });

    // Image preview for file inputs
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You can add image preview functionality here if needed
                    console.log('File selected:', file.name);
                };
                reader.readAsDataURL(file);
            }
        });
    });
});
</script>
@endpush
