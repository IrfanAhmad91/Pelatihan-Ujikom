// Fungsi untuk menampilkan konfirmasi sebelum menghapus
$(document).ready(function() {
    // Toast notification
    if ($('.alert').length) {
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    }
    
    // Validasi form
    $('form').submit(function() {
        $(this).find('button[type="submit"]').prop('disabled', true);
    });
    
    // Format input uang
    $('.currency-input').on('keyup', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(formatCurrency(value));
    });
    
    function formatCurrency(amount) {
        return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
});