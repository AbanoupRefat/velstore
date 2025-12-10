<!-- Login Reminder Popup for Guest Users -->
@guest('customer')
<div class="modal fade" id="loginReminderModal" tabindex="-1" aria-labelledby="loginReminderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" 
             style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); font-family: Arial, sans-serif;">
             
            <div class="modal-header" 
                 style="background: linear-gradient(135deg, #800020 0%, #5D001E 100%); 
                        color: #ffffff !important; 
                        font-weight: 600; 
                        border-radius: 12px 12px 0 0; 
                        border: none;
                        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                <h5 class="modal-title" id="loginReminderModalLabel" 
                    style="color: #ffffff !important; font-weight: 600; font-size: 18px;">
                    <i class="fas fa-info-circle me-2" style="color: #ffffff !important;"></i>
                    {{ __('store.checkout.login_reminder_title') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body" style="padding: 30px; font-size: 16px; line-height: 1.8; color: #333333 !important;">
                <p style="margin-bottom: 20px;">{{ __('store.checkout.login_reminder_message') }}</p>
                
                <div class="benefits-list mt-4">
                    <div class="benefit-item mb-3" style="display: flex; align-items: start;">
                        <i class="fas fa-check-circle" style="color: #800020 !important; font-size: 20px; margin-top: 2px; margin-right: 12px;"></i>
                        <span style="flex: 1; color: #333333 !important;">{{ __('store.checkout.benefit_track_orders') }}</span>
                    </div>
                    <div class="benefit-item mb-3" style="display: flex; align-items: start;">
                        <i class="fas fa-check-circle" style="color: #800020 !important; font-size: 20px; margin-top: 2px; margin-right: 12px;"></i>
                        <span style="flex: 1; color: #333333 !important;">{{ __('store.checkout.benefit_faster_checkout') }}</span>
                    </div>
                    <div class="benefit-item mb-3" style="display: flex; align-items: start;">
                        <i class="fas fa-check-circle" style="color: #800020 !important; font-size: 20px; margin-top: 2px; margin-right: 12px;"></i>
                        <span style="flex: 1; color: #333333 !important;">{{ __('store.checkout.benefit_order_history') }}</span>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-4 mb-0" style="background: #fff3cd !important; border: 1px solid #ffc107 !important; border-radius: 8px; color: #856404 !important; padding: 12px 16px;">
                    <i class="fas fa-exclamation-triangle me-2" style="color: #856404 !important;"></i>
                    <small>{{ __('store.checkout.guest_limitation') }}</small>
                </div>
            </div>
            
            <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 20px 30px;">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="dontShowAgain" style="cursor: pointer;">
                    <label class="form-check-label" for="dontShowAgain" style="font-size: 14px; color: #666666 !important; cursor: pointer;">
                        {{ __('store.checkout.dont_show_again') }}
                    </label>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('customer.login') }}" 
                       class="btn btn-primary me-2" 
                       style="background: #800020 !important; border: none !important; padding: 10px 24px; color: #ffffff !important; text-decoration: none;">
                        {{ __('store.checkout.login_now') }}
                    </a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" 
                            style="padding: 10px 24px; color: #333333 !important; border: 1px solid #cccccc !important; background: #ffffff !important;">
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
