document.addEventListener('DOMContentLoaded', function() {
    // Sayfa yüklendiğinde ve geçmiş değiştiğinde butonları güncelle
    window.addEventListener('popstate', updateNavigationButtons);
    updateNavigationButtons();

    function updateNavigationButtons() {
        const backButton = document.querySelector('[onclick="window.history.back()"]');
        const forwardButton = document.querySelector('[onclick="window.history.forward()"]');

        if (backButton) {
            backButton.style.display = window.history.length > 1 ? 'flex' : 'none';
        }

        if (forwardButton) {
            // Tarayıcı geçmişinde ileri gidilebilecek sayfa varsa göster
            forwardButton.style.display = window.history.length > window.history.position ? 'flex' : 'none';
        }
    }
}); 