window.confirmDelete = function (formId) {
    const isDarkMode = document.documentElement.classList.contains('dark');

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this deletion!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        background: isDarkMode ? '#1f2937' : undefined,
        color: isDarkMode ? '#f9fafb' : undefined,
        customClass: {
            confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2',
            cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
};

window.showAlert = function(type, title, message) {
    const isDarkMode = document.documentElement.classList.contains('dark');

    Swal.fire({
        icon: type, // 'success', 'error', 'warning', etc.
        title: title,
        text: message,
        timer: 3000,
        timerProgressBar: true,
        confirmButtonText: 'OK',
        background: isDarkMode ? '#1e293b' : '#fff',
        color: isDarkMode ? '#f1f5f9' : '#000',
        customClass: {
            confirmButton: 'bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded'
        }
    });
};


