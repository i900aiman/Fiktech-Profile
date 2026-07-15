<?php
/**
 * Fiktech Enterprise - Admin Contact Details & Email Reply Panel
 */
require_once __DIR__ . '/../includes/contact_storage.php';

$activeTab = 'contacts';
require_once __DIR__ . '/includes/header.php';

$id = $_GET['id'] ?? '';
$contact = get_incoming_by_id($id);

if (!$contact) {
    echo '<div class="admin-panel"><div class="empty-state"><i class="fas fa-circle-exclamation"></i><p>Butiran maklum balas tidak dijumpai.</p></div></div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Fetch reply history from data/contacts/outgoing/
$replies = get_outgoing_by_parent_id($id);
?>
<a href="contacts.php" class="back-link">
    <i class="fas fa-chevron-left"></i> Kembali ke Urus Contacts
</a>

<div class="admin-title-row">
    <div>
        <h2>Butiran Maklum Balas</h2>
        <p style="color: var(--text-secondary); font-size: 0.9rem;">ID Mesej: <?= e($contact['id']) ?></p>
    </div>
    <div>
        <?php if (($contact['status'] ?? 'new') === 'new'): ?>
        <span class="badge badge-new" id="contact-badge" style="font-size: 0.9rem; padding: 6px 15px;">New</span>
        <?php else: ?>
        <span class="badge badge-read" id="contact-badge" style="font-size: 0.9rem; padding: 6px 15px;">Read</span>
        <?php endif; ?>
    </div>
</div>

<div class="admin-panel" style="margin-bottom: 40px;">
    <div class="detail-grid">
        <!-- Date -->
        <div class="detail-row">
            <div class="detail-label">Tarikh Hantar</div>
            <div class="detail-value">
                <?php 
                    $parts = explode('T', $contact['submitted_at']);
                    $datePart = $parts[0];
                    $timePart = isset($parts[1]) ? substr(explode('+', $parts[1])[0], 0, 8) : '';
                    echo e($datePart) . ' <span style="color:var(--text-secondary); margin-left:8px; font-size:0.9em;">' . e($timePart) . ' (GMT+8)</span>';
                ?>
            </div>
        </div>
        
        <!-- Name -->
        <div class="detail-row">
            <div class="detail-label">Nama Penuh</div>
            <div class="detail-value" style="font-weight: 600; font-size: 1.1rem; color: var(--accent-gold);">
                <?= e($contact['full_name']) ?>
            </div>
        </div>
        
        <!-- Email -->
        <div class="detail-row">
            <div class="detail-label">Alamat Email</div>
            <div class="detail-value">
                <a href="mailto:<?= e($contact['email']) ?>" id="contact-email" style="color: #FFF; text-decoration: underline;"><?= e($contact['email']) ?></a>
            </div>
        </div>
        
        <!-- Phone -->
        <div class="detail-row">
            <div class="detail-label">No. Telefon</div>
            <div class="detail-value">
                <a href="tel:<?= e($contact['phone']) ?>" style="color: #FFF; text-decoration: underline;"><?= e($contact['phone']) ?></a>
            </div>
        </div>
        
        <!-- Company -->
        <div class="detail-row">
            <div class="detail-label">Syarikat</div>
            <div class="detail-value"><?= e($contact['company_name'] ?: '-') ?></div>
        </div>
        
        <!-- Service -->
        <div class="detail-row">
            <div class="detail-label">Servis Diminati</div>
            <div class="detail-value" style="font-weight: 500;"><?= e($contact['service']) ?></div>
        </div>
        
        <!-- Subject -->
        <div class="detail-row">
            <div class="detail-label">Subjek</div>
            <div class="detail-value" style="font-weight: 500; color: #FFF;"><?= e($contact['subject']) ?></div>
        </div>
        
        <!-- Message -->
        <div class="detail-row" style="border-bottom: none;">
            <div class="detail-label" style="margin-bottom: 10px; display: block;">Kandungan Mesej</div>
            <div class="detail-value">
                <div class="message-box"><?= e($contact['message']) ?></div>
            </div>
        </div>
        
        <!-- Consent & Source File -->
        <div class="detail-row">
            <div class="detail-label">Persetujuan Hubung</div>
            <div class="detail-value">
                <?php if (!empty($contact['consent'])): ?>
                <span style="color: #2ee65e;"><i class="fas fa-circle-check"></i> Bersetuju untuk dihubungi</span>
                <?php else: ?>
                <span style="color: #ff5f6e;"><i class="fas fa-circle-xmark"></i> Tiada Persetujuan</span>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="detail-row" style="border-bottom: none; padding-bottom: 0;">
            <div class="detail-label">Sumber Fail Data</div>
            <div class="detail-value" style="font-family: monospace; font-size: 0.85rem; color: var(--text-secondary);">
                data/contacts/incoming/<?= e($contact['_source_file']) ?>
            </div>
        </div>
    </div>
    
    <!-- Action buttons inside detail view -->
    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.05); display: flex; gap: 15px;">
        <!-- Toggle status dynamically via the shared script -->
        <?php if (($contact['status'] ?? 'new') === 'new'): ?>
        <button type="button" class="action-btn action-btn-status toggle-status-btn" data-id="<?= e($contact['id']) ?>" data-current-status="new" data-csrf="<?= csrf_token() ?>">
            <i class="fas fa-envelope-open"></i> Tandakan Sudah Dibaca (Mark Read)
        </button>
        <?php else: ?>
        <button type="button" class="action-btn toggle-status-btn" data-id="<?= e($contact['id']) ?>" data-current-status="read" data-csrf="<?= csrf_token() ?>">
            <i class="fas fa-envelope"></i> Tandakan Belum Dibaca (Mark New)
        </button>
        <?php endif; ?>
    </div>
</div>

<!-- History of Sent Email Replies -->
<div class="admin-panel" style="margin-bottom: 40px;">
    <div class="admin-panel-title">
        <i class="fas fa-history" style="color: var(--accent-gold); margin-right: 10px;"></i> Sejarah E-mel Balasan (Outgoing)
    </div>
    
    <div id="reply-history-list">
        <?php if (!empty($replies)): ?>
            <?php foreach ($replies as $reply): 
                $rParts = explode('T', $reply['sent_at']);
                $rDate = $rParts[0];
                $rTime = isset($rParts[1]) ? substr(explode('+', $rParts[1])[0], 0, 8) : '';
            ?>
            <div style="border-bottom: 1px solid rgba(255, 255, 255, 0.05); padding-bottom: 20px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 8px;">
                    <div><strong>Kepada:</strong> <?= e($reply['recipient_name']) ?> (<?= e($reply['recipient_email']) ?>)</div>
                    <div>Dihantar pada: <?= e($rDate) ?> <?= e($rTime) ?></div>
                </div>
                <div style="font-weight: bold; font-size: 0.9rem; margin-bottom: 8px; color: #FFF;">Subjek: <?= e($reply['subject']) ?></div>
                <div class="message-box" style="padding: 15px; font-size: 0.88rem;"><?= e($reply['body']) ?></div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state" id="empty-history" style="padding: 20px 0;">
                <i class="fas fa-share-nodes"></i>
                <p>Tiada rekod balasan e-mel keluar dihantar lagi untuk mesej ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Send Reply Form -->
<div class="admin-panel" id="reply-panel">
    <div class="admin-panel-title">
        <i class="fas fa-reply" style="color: var(--accent-gold); margin-right: 10px;"></i> Balas E-mel Pelanggan via SMTP
    </div>
    
    <!-- Status message placeholder -->
    <div id="reply-status-msg" class="form-status"></div>
    
    <form id="reply-form" autocomplete="off" style="margin-top: 10px;">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="parent_contact_id" value="<?= e($contact['id']) ?>">
        <input type="hidden" name="recipient_name" value="<?= e($contact['full_name']) ?>">
        <input type="hidden" name="recipient_email" value="<?= e($contact['email']) ?>">
        
        <!-- Templat Balasan Pantas -->
        <div class="filter-group" style="margin-bottom: 20px;">
            <label for="reply_template" style="color: var(--text-secondary); font-size: 0.8rem; margin-bottom: 8px;">Pilih Templat E-mel Pantas</label>
            <select id="reply_template" class="filter-control" style="background-color: #1a1a1a; width: 100%;">
                <option value="" selected>-- Tulis Mesej Sendiri (Kosong) --</option>
                <option value="general">Pertanyaan Am (General Inquiry Reply)</option>
                <option value="quote">Sebut Harga Projek (Quotation/Project Scope Request)</option>
                <option value="support">Sokongan IT & Rangkaian (IT/Network Technical Support)</option>
            </select>
        </div>

        <!-- Subject Field -->
        <div class="filter-group" style="margin-bottom: 20px;">
            <label for="reply_subject" style="color: var(--text-secondary); font-size: 0.8rem; margin-bottom: 8px;">Subjek E-mel Balasan <span style="color: var(--accent-gold);">*</span></label>
            <input type="text" id="reply_subject" name="reply_subject" class="filter-control" style="background-color: #1a1a1a; width: 100%;" value="Re: <?= e($contact['subject']) ?>" required>
        </div>
        
        <!-- Message Box -->
        <div class="filter-group" style="margin-bottom: 30px;">
            <label for="reply_message" style="color: var(--text-secondary); font-size: 0.8rem; margin-bottom: 8px;">Kandungan E-mel Balasan <span style="color: var(--accent-gold);">*</span></label>
            <textarea id="reply_message" name="reply_message" class="filter-control" style="background-color: #1a1a1a; width: 100%; min-height: 180px; resize: vertical; font-family: 'Inter', sans-serif;" placeholder="Tulis jawapan balasan anda di sini..." required></textarea>
        </div>
        
        <button type="submit" class="filter-submit-btn" style="width: 100%; padding: 12px; font-size: 0.95rem;">
            Hantar E-mel Balasan <i class="fas fa-paper-plane" style="margin-left: 5px;"></i>
        </button>
    </form>
</div>
<?php
$adminScripts = '
<script src="../static/js/admin.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const replyForm = document.getElementById("reply-form");
    const replyStatus = document.getElementById("reply-status-msg");
    const historyList = document.getElementById("reply-history-list");
    const emptyHistory = document.getElementById("empty-history");
    
    // Quick templates definition
    const templates = {
        general: "Salam Sejahtera,\\n\\nTerima kasih kerana menghubungi pihak FIKTECH ENTERPRISE. Kami telah menerima pertanyaan anda dan sedang meneliti butiran yang diberikan.\\n\\nPegawai perunding kami akan menghubungi anda kembali dalam masa 24 jam untuk perbincangan lanjut.\\n\\nSekian, terima kasih.",
        quote: "Salam Sejahtera,\\n\\nTerima kasih atas minat anda terhadap perkhidmatan reka bentuk web & pembangunan sistem daripada FIKTECH ENTERPRISE.\\n\\nBagi memudahkan kami menyediakan sebut harga (quotation) yang terperinci dan tepat, bolehkah anda kongsikan beberapa maklumat tambahan berikut:\\n1. Aliran kerja (workflow) atau fungsi utama sistem yang anda perlukan.\\n2. Anggaran garis masa (timeline) projek.\\n3. Contoh rujukan sistem/web sedia ada (jika ada).\\n\\nSekian, terima kasih.",
        support: "Salam Sejahtera,\\n\\nKami telah menerima laporan isu IT / Rangkaian yang dihadapi oleh pihak anda.\\n\\nJurutera teknikal kami bersedia untuk melakukan sesi bantuan remote support atau lawatan tapak (site visit) bagi menyelesaikan masalah ini dengan segera. Sila maklumkan waktu kesesuaian anda untuk sesi ini.\\n\\nSekian, terima kasih."
    };
    
    const templateSelect = document.getElementById("reply_template");
    const messageTextarea = document.getElementById("reply_message");
    
    if (templateSelect && messageTextarea) {
        templateSelect.addEventListener("change", (e) => {
            const val = e.target.value;
            if (val && templates[val]) {
                messageTextarea.value = templates[val];
            } else {
                messageTextarea.value = "";
            }
        });
    }
    
    if (!replyForm) return;
    
    replyForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        
        // Clear messages
        replyStatus.style.display = "none";
        replyStatus.className = "form-status";
        replyStatus.textContent = "";
        
        const submitBtn = replyForm.querySelector("button[type=\'submit\']");
        const origText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = "<i class=\'fas fa-spinner fa-spin\'></i> Sending Reply...";
        
        const formData = new FormData(replyForm);
        const csrfToken = formData.get("csrf_token");
        
        try {
            const response = await fetch("api_reply.php", {
                method: "POST",
                headers: {
                    "X-CSRF-Token": csrfToken
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (response.ok && result.status === "success") {
                replyStatus.innerHTML = "<i class=\'fas fa-circle-check\'></i> " + result.message;
                replyStatus.className = "form-status success";
                replyStatus.style.display = "block";
                
                // Clear reply message textarea
                document.getElementById("reply_message").value = "";
                
                // Remove empty state if present
                if (emptyHistory) {
                    emptyHistory.remove();
                }
                
                // Prepend the new reply to the history view
                const now = new Date();
                const nowStr = now.toISOString().split("T")[0] + " " + now.toTimeString().split(" ")[0];
                
                const newReplyHtml = `
                <div style="border-bottom: 1px solid rgba(255, 255, 255, 0.05); padding-bottom: 20px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 8px;">
                        <div><strong>Kepada:</strong> ${result.record.recipient_name} (${result.record.recipient_email})</div>
                        <div>Dihantar pada: ${nowStr}</div>
                    </div>
                    <div style="font-weight: bold; font-size: 0.9rem; margin-bottom: 8px; color: #FFF;">Subjek: ${result.record.subject}</div>
                    <div class="message-box" style="padding: 15px; font-size: 0.88rem;">${result.record.body.replace(/\\n/g, "<br>")}</div>
                </div>`;
                
                historyList.insertAdjacentHTML("afterbegin", newReplyHtml);
                
                // Also update status badge to Read dynamically
                const badge = document.getElementById("contact-badge");
                if (badge) {
                    badge.className = "badge badge-read";
                    badge.textContent = "Read";
                }
                
                // Update toggle status button state if present
                const toggleBtn = document.querySelector(".toggle-status-btn");
                if (toggleBtn) {
                    toggleBtn.setAttribute("data-current-status", "read");
                    toggleBtn.className = "action-btn toggle-status-btn";
                    toggleBtn.innerHTML = "<i class=\'fas fa-envelope\'></i> Mark New";
                }
            } else {
                replyStatus.innerHTML = "<i class=\'fas fa-circle-exclamation\'></i> Error: " + (result.message || "Failed to send email.");
                replyStatus.className = "form-status error";
                replyStatus.style.display = "block";
            }
        } catch (err) {
            console.error("Reply submission error:", err);
            replyStatus.innerHTML = "<i class=\'fas fa-circle-exclamation\'></i> Connection error. Failed to send reply.";
            replyStatus.className = "form-status error";
            replyStatus.style.display = "block";
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = origText;
        }
    });
});
</script>';
require_once __DIR__ . '/includes/footer.php';
?>
