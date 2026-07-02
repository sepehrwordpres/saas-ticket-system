// غیب شدن خودکار پیام‌های سیستم بعد از ۵ ثانیه
document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.dynamic-alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});