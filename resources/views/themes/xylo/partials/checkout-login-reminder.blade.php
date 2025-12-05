<!-- Login Reminder Popup for Guest Users -->
@guest('customer')
<div class="modal fade" id="loginReminderModal" tabindex="-1" aria-labelledby="loginReminderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: linear-gradient(135deg, #800020 0%, #5D001E 100%); color: white; border-radius: 12px 12px 0 0; border: none;">
                <h5 class="modal-title" id="loginReminderModalLabel" style="font-weight: 600;">
                    <i class="fas fa-info-circle me-2"></i>{{ __('store.checkout.login_reminder_title') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <p style="font-size: 16px; line-height: 1.8; color: #333;">
                    {{ __('store.checkout.login_reminder_message') }}
                </p>
                
                <div class="benefits-list mt-4">
                    <div class="benefit-item mb-3" style="display: flex; align-items: start;">
                        <i class="fas fa-check-circle" style="color: #800020; font-size: 20px; margin-top: 2px; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 12px;"></i>
                        <span style="flex: 1;">{{ __('store.checkout.benefit_track_orders') }}</span>
                    </div>
                    <div class="benefit-item mb-3" style="display: flex; align-items: start;">
                        <i class="fas fa-check-circle" style="color: #800020; font-size: 20px; margin-top: 2px; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 12px;"></i>
                        <span style="flex: 1;">{{ __('store.checkout.benefit_faster_checkout') }}</span>
                    </div>
                    <div class="benefit-item mb-3" style="display: flex; align-items: start;">
                        <i class="fas fa-check-circle" style="color: #800020; font-size: 20px; margin-top: 2px; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 12px;"></i>
                        <span style="flex: 1;">{{ __('store.checkout.benefit_order_history') }}</span>
                    </div>
                </div>

                <div class="alert alert-warning mt-4 mb-0" style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <small>{{ __('store.checkout.guest_limitation') }}</small>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 20px 30px;">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="dontShowAgain">
                    <label class="form-check-label" for="dontShowAgain" style="font-size: 14px; color: #666;">
                        {{ __('store.checkout.dont_show_again') }}
                    </label>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('customer.login') }}" class="btn btn-primary me-2" style="background: #800020; border: none; padding: 10px 24px;">
                        {{ __('store.checkout.login_now') }}
                    </a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="padding: 10px 24px;">
                        {{ __('store.checkout.continue_as_guest') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if user has dismissed the popup before
    const dontShowAgain = localStorage.getItem('checkout_login_reminder_dismissed');
    
    if (!dontShowAgain) {
        // Show the modal after a short delay
        setTimeout(function() {
            const modal = new bootstrap.Modal(document.getElementById('loginReminderModal'));
            modal.show();
        }, 1000);
    }
    
    // Handle "Don't show again" checkbox
    document.getElementById('loginReminderModal').addEventListener('hidden.bs.modal', function() {
        if (document.getElementById('dontShowAgain').checked) {
            localStorage.setItem('checkout_login_reminder_dismissed', 'true');
        }
    });
});
</script>
@endguest
