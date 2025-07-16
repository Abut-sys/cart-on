document.addEventListener('DOMContentLoaded', function() { setTimeout(() => { const alert =
document.getElementById('success-alert'); if (alert) { alert.style.transition = 'opacity 0.5s ease'; alert.style.opacity
= '0'; setTimeout(() => alert.remove(), 500); } }, 8000);});
