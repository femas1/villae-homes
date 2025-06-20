document.querySelector('form').addEventListener('submit', function(e) {
    const checkIn = new Date(document.querySelector('input[name="check_in"]').value);
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Reset time to midnight for accurate date comparison
    
    if (checkIn < today) {
        e.preventDefault();
        alert('Buchungen in der Vergangenheit sind nicht möglich.');
        return false;
    }
});
