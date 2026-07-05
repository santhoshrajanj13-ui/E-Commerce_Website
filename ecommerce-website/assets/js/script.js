/* =========================================================
   ShopEase E-Commerce Project - Main JS
   Module: Overall UI / Frontend Interactivity (Santhosh)
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {
  // Mobile nav toggle
  const navToggle = document.getElementById('navToggle');
  const navLinks = document.getElementById('navLinks');
  if (navToggle && navLinks) {
    navToggle.addEventListener('click', function () {
      navLinks.classList.toggle('open');
    });
  }

  // Payment method selection (payment.php)
  const paymentOptions = document.querySelectorAll('.payment-option');
  if (paymentOptions.length) {
    paymentOptions.forEach(function (opt) {
      opt.addEventListener('click', function () {
        paymentOptions.forEach(o => o.classList.remove('selected'));
        opt.classList.add('selected');
        const radio = opt.querySelector('input[type=radio]');
        if (radio) radio.checked = true;
      });
    });
  }

  // Confirm before delete/cancel actions
  document.querySelectorAll('.confirm-action').forEach(function (el) {
    el.addEventListener('click', function (e) {
      if (!confirm('Are you sure you want to continue?')) {
        e.preventDefault();
      }
    });
  });

  // Auto-hide alerts after 4 seconds
  document.querySelectorAll('.alert').forEach(function (alertBox) {
    setTimeout(function () {
      alertBox.style.transition = 'opacity 0.5s ease';
      alertBox.style.opacity = '0';
      setTimeout(() => alertBox.remove(), 500);
    }, 4000);
  });
});

// Quantity increment/decrement on product details page
function changeQty(delta) {
  const input = document.getElementById('qtyInput');
  if (!input) return;
  let val = parseInt(input.value || '1') + delta;
  if (val < 1) val = 1;
  input.value = val;
}
