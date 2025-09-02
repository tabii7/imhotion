<!-- Project Modal Component -->
<div id="projectModal" class="project-modal-overlay" style="display: none;">
    <div class="project-modal-container">
        <div class="project-modal-content">
            <!-- Modal Header -->
            <div class="project-modal-header">
                <div class="project-modal-header-left">
                    <h2 class="project-modal-title" id="modalProjectTitle">Project Title</h2>
                    <!-- header topic will be populated with the actual topic value -->
                    <div id="modalProjectTopicHeader" class="project-modal-topic"><span id="modalProjectTopicHeaderValue"></span></div>
                    <div class="project-modal-meta">
                        <span class="project-modal-status" id="modalProjectStatus">Status</span>
                        <span class="project-modal-delivery" id="modalProjectDelivery">Delivery Date</span>
                    </div>
                </div>
                <div class="project-modal-header-right">
                    <!-- Project number shown near the close button with label -->
                    <div class="project-number-header" title="Project Number">
                        <div class="project-number-badge">
                            <div class="project-number-label">PROJECT NUMBER</div>
                            <div id="modalProjectNumberHeader" class="project-number-value">2517</div>
                        </div>
                    </div>
                    <button class="project-modal-close" onclick="closeProjectModal()">&times;</button>
                    <!-- view toggle moved to header-right as requested (list/grid) -->
                    <div class="view-toggle header-view-toggle" role="tablist" aria-label="Files view">
                        <button class="view-toggle-btn active" onclick="toggleFilesView('list')" data-view="list" title="List view">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                <line x1="3" y1="18" x2="3.01" y2="18"></line>
                            </svg>
                        </button>
                        <button class="view-toggle-btn" onclick="toggleFilesView('grid')" data-view="grid" title="Grid view">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- NOTES moved into top area (directly under header) -->
            <div class="detail-row notes-top top-notes" style="margin:12px 28px 0 28px;">
                <strong>Notes:</strong>
                <div id="modalNotes" class="project-notes">No notes available</div>
            </div>

            <!-- Client info removed (moved up into header per design request) -->

            <!-- Tabs removed - showing details and files stacked -->

            <!-- Tab Content -->
            <div class="project-modal-body">
                <!-- Details Tab (now visible by default) -->
                <div id="detailsTab" class="modal-tab-content active">
                    <div class="project-details">
                        <!-- compact two rows: each row has two stacked label+value blocks -->
                        <div class="details-rows">
                                <div class="details-row-compact">
                                    <div class="stack-item compact">
                                        <div class="stack-label">Project Number</div>
                                        <div class="stack-value" id="modalProjectNumber">Project #</div>
                                    </div>
                                    <div class="stack-item compact">
                                        <!-- display the topic value directly (no uppercase label) -->
                                        <div class="stack-value" id="modalProjectTopic">Topic</div>
                                    </div>
                                </div>
                            <div class="details-row-compact">
                                <div class="stack-item compact">
                                    <div class="stack-label">Start Date</div>
                                    <div class="stack-value" id="modalStartDate">Not set</div>
                                </div>
                                <div class="stack-item compact">
                                    <div class="stack-label">End Date</div>
                                    <div class="stack-value" id="modalEndDate">Not set</div>
                                </div>
                            </div>
                        </div>

                        <!-- (notes moved to top) -->

                        <!-- Modern inline uploader (moved up) -->
                        <div id="modalUploader" class="uploader" title="Drag & drop files here or click to browse">
                            <div class="uploader-left">
                                <div class="uploader-icon">⇪</div>
                                <div class="uploader-cta">
                                    <div class="title">Drag & drop files here</div>
                                    <div class="subtitle">or click to browse — images, PDFs, video</div>
                                </div>
                            </div>
                            <div class="uploader-actions">
                                <button type="button" onclick="document.getElementById('modalUploadInput').click();event.stopPropagation();">Select files</button>
                            </div>
                        </div>
                        <div id="modalUploadPreview" class="upload-preview" aria-hidden="true"></div>

                        <!-- details rows now appear after notes and uploader -->

                        <!-- Days Used removed as requested -->
                    </div>
                </div>

                <!-- Files Tab (now visible by default) -->
                <div id="filesTab" class="modal-tab-content active">
                    <div class="files-header">
                        <div class="files-actions">
                            <!-- keep files header minimal: view toggle removed here (moved to header) -->
                        </div>
                    </div>

                    <!-- hidden file input used for uploads -->
                    <input type="file" id="modalUploadInput" multiple style="display:none" onchange="(function(e){ if(typeof uploadProjectFiles === 'function'){ uploadProjectFiles(e.target.files); } else { console.warn('uploadProjectFiles not implemented'); } })(event);" />

                    <div id="filesList" class="files-container list-view">
                        <!-- Files will be populated by JavaScript -->
                        <div class="no-files-message">No files available for this project</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox for file preview -->
<div id="fileLightbox" class="file-lightbox" style="display: none;">
    <div class="lightbox-overlay" onclick="closeLightbox()"></div>
    <div class="lightbox-content">
        <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
        <div class="lightbox-body" id="lightboxBody">
            <!-- Content will be populated by JavaScript -->
        </div>
    </div>
</div>

<style>
:root{
    --brand-primary: #003480; /* deeper/darker blue */
    --brand-primary-200: #77a6ff;
    --brand-bg: #f2f7ff;
    --brand-dark: #001f4c;
    --font-sans: 'BrittiSans', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
}

/* Ensure modal uses brand font locally if global CSS not loaded */
.project-modal-content,
.project-modal-title,
.project-modal-client-info,
.project-modal-body {
    font-family: var(--font-sans);
}

/* Project Modal Styles */
.project-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.project-modal-container {
    width: 100%;
    max-width: 1000px;
    max-height: 90vh;
    overflow: hidden;
    border-radius: 14px;
    box-shadow: 0 18px 60px rgba(2,6,23,0.45);
    position: relative;
    background: var(--brand-bg);
}

.project-modal-content {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    max-height: 90vh;
}

/* subtle accent strip matching header color */
.project-modal-container::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 6px;
    width: 100%;
    background: linear-gradient(90deg, var(--brand-dark) 0%, var(--brand-primary) 100%);
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
}

/* Modal Header */
.project-modal-header {
    background: var(--brand-primary);
    color: white;
    padding: 20px 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

.project-modal-title {
    font-size: 24px;
    font-weight: 600;
    margin: 0 0 8px 0;
    color: white;
}


    .project-number-header{
        display:flex;align-items:center;gap:8px;margin-right:8px;padding:6px;border-radius:8px;color:white;font-weight:700;
    }
    .project-number-badge{background:rgba(255,255,255,0.06);padding:8px 10px;border-radius:8px;text-align:center}
    .project-number-label{font-size:11px;opacity:0.95;color:rgba(255,255,255,0.9);font-weight:800}
    .project-number-value{font-size:13px;color:white;font-weight:800;margin-top:4px}
    .project-modal-header-right{display:flex;flex-direction:column;align-items:flex-end;gap:8px}
    .header-view-toggle{display:flex;gap:6px}
.project-modal-topic{
    font-size:14px;
    font-weight:400;
    color: rgba(255,255,255,0.9);
    margin-bottom:6px;
}

.project-modal-header-left {
    display: flex;
    flex-direction: column;
    .uploader {
        border: 2px dashed rgba(6,58,128,0.08);
        border-radius: 10px;
        padding: 14px 18px;
        background: #fbfdff;
        display:flex;
        gap:12px;
        align-items:center;
        justify-content:space-between;
    }
    gap: 6px;
}

.project-modal-meta {
    display: flex;
    gap: 14px;
    align-items: center;
    flex-wrap: wrap;

    .notes-top{margin-top:6px}
}

.project-modal-status {
    background: var(--brand-primary-200);
    color: var(--brand-dark);
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}

.project-modal-status.completed { background: #10b981; color: white; }
.project-modal-status.in_progress { background: #f59e0b; color: white; }
.project-modal-status.pending { background: #ef4444; color: white; }
.project-modal-status.finalized { background: #6366f1; color: white; }

.project-modal-delivery {
    font-size: 14px;
    color: rgba(255,255,255,0.85);
}

.project-modal-close {
    background: rgba(255,255,255,0.08);
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    padding: 6px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    box-shadow: 0 1px 6px rgba(2,6,23,0.2);
    transition: background-color 0.12s, transform 0.12s;
}

.project-modal-close:hover {
    background: rgba(255, 255, 255, 0.12);
    transform: scale(1.03);
}

/* Client Info */
.project-modal-client-info {
    background: #ffffff;
    padding: 12px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.client-info-item {
    font-size: 14px;
    color: #263648;
}

.client-email {
    color: #64748b;
    font-style: italic;
}

.remaining-days {
    color: #7fa7e1;
    font-weight: 600;
}

.dates-inline{font-size:14px;color:#64748b}

/* Modal Tabs */
.project-modal-tabs {
    display: flex;
    background: #f1f5f9;
    border-bottom: 1px solid #e2e8f0;
}

.project-modal-tab {
    flex: 1;
    padding: 14px 20px;
    background: none;
    border: none;
    font-size: 15px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    transition: all 0.18s;
    border-bottom: 3px solid transparent;
}

.project-modal-tab:hover {
    background: #e2e8f0;
    color: #334155;
}

.project-modal-tab.active {
    background: #ffffff;
    color: var(--brand-dark);
    border-bottom-color: var(--brand-primary);
}

/* make tab look like a pill */
.project-modal-tab {
    border-radius: 6px 6px 0 0;
}

/* Modal Body */
.project-modal-body {
    flex: 1;
    overflow-y: auto;
    min-height: 420px;
    padding-bottom: 18px;
}

/* nicer scrollbar for the modal body */
.project-modal-body::-webkit-scrollbar {
    width: 10px;
}
.project-modal-body::-webkit-scrollbar-track {
    background: transparent;
}
.project-modal-body::-webkit-scrollbar-thumb {
    background: rgba(124,160,225,0.35);
    border-radius: 10px;
}

.modal-tab-content {
    display: none;
    padding: 28px 30px;
}

.modal-tab-content.active {
    display: block;
}

/* Details Tab */
.project-details {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.details-grid{
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap:12px;
}
.details-grid .grid-item{
    background:#ffffff;
    padding:12px 14px;
    border-radius:8px;
    border:1px solid #eef6ff;
}
.details-actions .download-all-btn{ padding:8px 12px }

.detail-row {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 16px 18px;
    background: #ffffff;
    border-radius: 8px;
    border-left: 4px solid var(--brand-primary-200);
    box-shadow: 0 6px 18px rgba(8,16,32,0.04);
}

.detail-row strong {
    color: #0a1428;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-row span, .project-notes {
    color: #475569;
    font-size: 16px;
}

.project-notes {
    background: #fbfdff;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #eef3f8;
    min-height: 80px;
    line-height: 1.6;
}

/* Files Tab */
.files-header {
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.download-all-btn { display:none }

.view-toggle {
    display: flex;
    background: #f4f7fb;
    border-radius: 8px;
    overflow: hidden;
}

.view-toggle-btn {
    background: none;
    border: none;
    padding: 12px;
    cursor: pointer;
    color: #64748b;
    transition: all 0.2s;
}

.view-toggle-btn:hover {
    background: #e2e8f0;
}

.view-toggle-btn.active {
    background: var(--brand-primary);
    color: white;
}

/* Files Container */
.files-container {
    min-height: 200px;
}

.files-container.list-view {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.files-container.grid-view {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
}

/* stacked compact fields */
.details-stack{
    display:flex;
    flex-direction:column;
    gap:6px;
}
.details-stack .stack-item{
    display:flex;
    flex-direction:column;
    gap:4px;
}
.stack-label{
    font-size:12px;
    color:#94a3b8;
    text-transform:uppercase;
    font-weight:700;
}
.stack-value{
    font-size:16px;
    color:#0b2540;
    font-weight:700;
}

/* Modern uploader */
.uploader {
    border: 2px dashed rgba(6,58,128,0.08);
    border-radius: 10px;
    padding: 18px;
    background: #fbfdff;
    display:flex;
    gap:12px;
    align-items:center;
    justify-content:space-between;
}
.uploader .uploader-left{
    display:flex;
    gap:12px;
    align-items:center;
}
.uploader .uploader-icon{
    width:56px;height:56px;border-radius:10px;background:linear-gradient(180deg,var(--brand-primary),#0052cc);display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:18px;
}
.uploader .uploader-cta{
    display:flex;flex-direction:column;gap:6px;
}
.uploader .uploader-cta .title{font-weight:700;color:#0b2540}
.uploader .uploader-cta .subtitle{font-size:13px;color:#64748b}
.uploader .uploader-actions{display:flex;gap:8px}
.uploader .uploader-actions button{padding:8px 12px;border-radius:8px;border:1px solid #e6eefb;background:white;cursor:pointer}
.uploader.dragover{border-color:var(--brand-primary);box-shadow:0 6px 18px rgba(6,58,128,0.06)}

/* small preview strip */
.upload-preview{display:flex;gap:8px;align-items:center;margin-top:10px}
.upload-preview img{width:56px;height:40px;object-fit:cover;border-radius:6px;border:1px solid #eef6ff}

.file-item {
    background: #ffffff;
    border: 1px solid #eef3f8;
    border-radius: 10px;
    padding: 12px 14px;
    cursor: pointer;
    transition: all 0.14s;
    display: flex;
    align-items: center;
    gap: 12px;
}

.file-item:hover {
    background: #fbfdff;
    border-color: var(--brand-primary-200);
    transform: translateY(-3px);
}

.file-item.grid-view {
    flex-direction: column;
    text-align: center;
    aspect-ratio: 1;
}

.file-icon {
    width: 48px;
    height: 48px;
    background: var(--brand-primary);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 14px;
    flex-shrink: 0;
}

.file-thumbnail {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 8px;
    flex-shrink: 0;
    box-shadow: 0 6px 18px rgba(8,16,32,0.06);
}

.grid-view .file-icon,
.grid-view .file-thumbnail {
    width: 80px;
    height: 80px;
}

.file-info {
    flex: 1;
    min-width: 0;
}

.file-name {
    font-weight: 700;
    color: #0b2540;
    margin-bottom: 4px;
    word-break: break-word;
}

.file-size {
    font-size: 12px;
    color: #64748b;
}

.no-files-message {
    text-align: center;
    color: #64748b;
    font-style: italic;
    padding: 60px 20px;
}

/* Lightbox */
.file-lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    z-index: 20000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.lightbox-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.lightbox-content {
    position: relative;
    max-width: 90vw;
    max-height: 90vh;
    background: white;
    border-radius: 12px;
    overflow: hidden;
}

.lightbox-close {
    position: absolute;
    top: 16px;
    right: 16px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    border: none;
    font-size: 24px;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
}

.lightbox-body {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 300px;
}

.lightbox-body img {
    max-width: 100%;
    max-height: 90vh;
    object-fit: contain;
}

.lightbox-body video {
    max-width: 100%;
    max-height: 90vh;
}

/* Responsive */
@media (max-width: 768px) {
    .project-modal-overlay {
        padding: 10px;
    }
    
    .project-modal-header {
        padding: 20px;
        flex-direction: column;
        gap: 16px;
    }
    
    .project-modal-client-info {
        padding: 16px 20px;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .modal-tab-content {
        padding: 20px;
    }
    
    .files-container.grid-view {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
}
</style>

<script>
// Uploader UI: drag/drop and preview
;(function(){
    const uploader = document.getElementById('modalUploader');
    const input = document.getElementById('modalUploadInput');
    const preview = document.getElementById('modalUploadPreview');

    if(!uploader || !input) return;

    function prevent(e){ e.preventDefault(); e.stopPropagation(); }
    ['dragenter','dragover'].forEach(evt=> uploader.addEventListener(evt, function(e){ prevent(e); uploader.classList.add('dragover'); }));
    ['dragleave','drop'].forEach(evt=> uploader.addEventListener(evt, function(e){ prevent(e); uploader.classList.remove('dragover'); }));

    uploader.addEventListener('drop', function(e){
        const files = Array.from(e.dataTransfer.files || []);
        handleFiles(files);
    });

    uploader.addEventListener('click', function(e){
        // clicking the whole uploader triggers the hidden file input
        input.click();
    });

    input.addEventListener('change', function(e){
        const files = Array.from(e.target.files || []);
        handleFiles(files);
        // reset input so same file can be reselected
        e.target.value = '';
    });

    function handleFiles(files){
        if(files.length === 0) return;
        // render small previews for images & first 6 files
        preview.innerHTML = '';
        files.slice(0,6).forEach(f => {
            const el = document.createElement('div');
            if(f.type.startsWith('image/')){
                const img = document.createElement('img');
                img.src = URL.createObjectURL(f);
                img.onload = () => URL.revokeObjectURL(img.src);
                el.appendChild(img);
            } else {
                const img = document.createElement('img');
                img.src = '/images/file-icon-placeholder.png';
                el.appendChild(img);
            }
            preview.appendChild(el);
        });

        // call existing uploader hook if present (dashboard JS)
        if(typeof uploadProjectFiles === 'function'){
            try{ uploadProjectFiles(files); } catch(err){ console.error(err); }
        } else {
            console.info('uploadProjectFiles not implemented — files prepared for upload', files);
        }
    }
})();
</script>
