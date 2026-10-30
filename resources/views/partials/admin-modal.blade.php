<!-- Rejection Modal -->
<div id="rejectModal" class="admin-modal-backdrop" aria-hidden="true">
    <div class="admin-modal" role="dialog" aria-modal="true" aria-labelledby="rejectModalTitle">
        <div class="admin-modal-header">
            <h3 id="rejectModalTitle">Reject Bhudev Profile</h3>
            <button type="button" class="admin-modal-close" data-modal-close aria-label="Close modal">&times;</button>
        </div>
        <form method="POST" action="">
            @csrf
            @method('PATCH')
            <div class="admin-modal-body">
                <p style="margin-top:0;font-size:13px;color:var(--admin-muted)">
                    You are rejecting the profile for <strong class="modal-target-name" style="color:var(--admin-ink)">Bhudev</strong>.
                    Please provide a clear reason that will be visible to the applicant on their dashboard.
                </p>
                <div class="form-group" style="margin-bottom:0">
                    <label for="rejectReason">Rejection Reason <span class="required">*</span></label>
                    <textarea id="rejectReason" name="reason" rows="4" class="form-control" required placeholder="E.g., Please upload a clearer identity photo and provide complete experience details." maxlength="2000"></textarea>
                </div>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn-action" data-modal-close>Cancel</button>
                <button type="submit" class="btn-action btn-action-danger">Confirm Rejection</button>
            </div>
        </form>
    </div>
</div>

<!-- Suspension Modal -->
<div id="suspendModal" class="admin-modal-backdrop" aria-hidden="true">
    <div class="admin-modal" role="dialog" aria-modal="true" aria-labelledby="suspendModalTitle">
        <div class="admin-modal-header">
            <h3 id="suspendModalTitle">Suspend Bhudev Profile</h3>
            <button type="button" class="admin-modal-close" data-modal-close aria-label="Close modal">&times;</button>
        </div>
        <form method="POST" action="">
            @csrf
            @method('PATCH')
            <div class="admin-modal-body">
                <p style="margin-top:0;font-size:13px;color:var(--admin-muted)">
                    Suspending <strong class="modal-target-name" style="color:var(--admin-ink)">Bhudev</strong> will immediately remove their profile from public searches and direct URLs.
                </p>
                <div class="form-group" style="margin-bottom:0">
                    <label for="suspendReason">Suspension Reason (Internal / Audit)</label>
                    <textarea id="suspendReason" name="reason" rows="3" class="form-control" placeholder="E.g., Temporary hiatus or verification review." maxlength="2000"></textarea>
                </div>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn-action" data-modal-close>Cancel</button>
                <button type="submit" class="btn-action btn-action-warning">Suspend Profile</button>
            </div>
        </form>
    </div>
</div>

<!-- Safe Deletion Modal -->
<div id="deleteModal" class="admin-modal-backdrop" aria-hidden="true">
    <div class="admin-modal" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
        <div class="admin-modal-header">
            <h3 id="deleteModalTitle">Delete Bhudev Account</h3>
            <button type="button" class="admin-modal-close" data-modal-close aria-label="Close modal">&times;</button>
        </div>
        <form method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="admin-modal-body">
                <p style="margin-top:0;font-size:13px;color:var(--admin-muted)">
                    Are you sure you want to delete <strong class="modal-target-name" style="color:var(--admin-ink)">Bhudev</strong>?
                </p>
                <p style="font-size:12px;background:#fef0ee;border:1px solid #fbdad6;padding:10px 14px;border-radius:6px;color:#c02b2b;margin:0">
                    ⚠ The profile and user account will be safely archived (soft deleted) and removed from public listings, preserving the audit history.
                </p>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn-action" data-modal-close>Cancel</button>
                <button type="submit" class="btn-action btn-action-danger">Delete / Archive Account</button>
            </div>
        </form>
    </div>
</div>
