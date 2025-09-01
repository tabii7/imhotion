@php
    use Illuminate\Support\Facades\Storage;

    $fmtDate = fn($v) => $v ? \Illuminate\Support\Carbon::parse($v)->format('d-m-y H:i') : '—';

    $fmtSize = function ($bytes) {
        $bytes = (int) $bytes;
        if ($bytes <= 0) return '0 B';
        $u = ['B','KB','MB','GB','TB'];
        $i = (int) floor(log($bytes, 1024));
        $i = min($i, count($u)-1);
        return round($bytes / (1024 ** $i), 2) . ' ' . $u[$i];
    };

    // Only visible docs
    $docs = $record->documents->where('is_hidden', false)->values();
@endphp

<div
    x-data="projectDocs({
        projectId: {{ $record->id }},
        csrf: '{{ csrf_token() }}',
        startCount: {{ $docs->count() }}
    })"
    class="space-y-4"
>
    {{-- Header info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
        <div><span class="text-gray-500">Projjjjject:</span> <span class="font-medium">{{ $record->title }}</span></div>
        <div><span class="text-gray-500">Client:</span> <span class="font-medium">{{ optional($record->user)->name ?? '—' }}</span></div>
        <div><span class="text-gray-500">Status:</span> <span class="font-medium">{{ ucfirst(str_replace('_',' ', $record->status ?? '—')) }}</span></div>
        <div>
            <span class="text-gray-500">When / Note:</span>
            <span class="font-medium">
                @php
                    $fmt = fn ($v) => $v ? \Illuminate\Support\Carbon::parse($v)->format('d-m-y') : null;
                    $due  = $fmt($record->due_date ?? null);
                    $done = $fmt($record->completed_at ?? null);
                    echo match ($record->status) {
                        'in_progress', 'pending' => $due
                            ? 'Delivery on ' . $due
                            : (filled($record->pending_note) ? (string) $record->pending_note : '—'),
                        'completed' => $done ? 'Completed ' . $done : 'Completed —',
                        'cancelled' => filled($record->cancel_reason) ? (string) $record->cancel_reason : 'Cancelled',
                        default => $due ? 'Delivery on ' . $due : '—',
                    };
                @endphp
            </span>
        </div>
    </div>

    {{-- Documents --}}
    <div class="border-t pt-3">
        <div class="font-medium text-sm mb-3">
            Documents (<span x-text="docCount"></span>)
        </div>

        @if($docs->isEmpty())
            <div class="text-sm text-gray-500 mb-2">No documents uploaded yet.</div>
        @else
            <div class="overflow-x-auto rounded border w-full">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50/10">
                        <tr class="text-left">
                            <th class="px-3 py-2 font-semibold w-1/2">Name / Filename</th>
                            <th class="px-3 py-2 font-semibold w-1/6">Type / Size</th>
                            <th class="px-3 py-2 font-semibold w-1/6">Date</th>
                            <th class="px-3 py-2 font-semibold text-right w-1/6">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($docs as $doc)
                            @php
                                $url    = $doc->path && Storage::disk('public')->exists($doc->path) ? Storage::url($doc->path) : null;
                                $fname  = $doc->filename ?? basename($doc->path ?? '');
                                $name   = $doc->name ?: pathinfo($fname, PATHINFO_FILENAME);
                                $ext    = strtolower(pathinfo($fname, PATHINFO_EXTENSION)) ?: '—';
                            @endphp
                            <tr class="border-t align-top" x-data="{ saved:false, saving:false }" id="doc-row-{{ $doc->id }}">
                                {{-- Name + Filename (both editable) --}}
                                <td class="px-3 py-2">
                                    <div class="space-y-1">
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <input
                                                    type="text"
                                                    name="name"
                                                    value="{{ $name }}"
                                                    class="block w-full rounded border border-gray-300 bg-transparent px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                                                    @keydown.enter.stop.prevent="
                                                        saving = true;
                                                        const name = $el.value;
                                                        const filename = $el.closest('td').querySelector('.filename-text').textContent;
                                                        fetch('/admin/projects/{{ $record->id }}/documents/{{ $doc->id }}/rename', {
                                                            method: 'POST',
                                                            headers: {
                                                                'X-Requested-With': 'XMLHttpRequest',
                                                                'Content-Type': 'application/json',
                                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            },
                                                            body: JSON.stringify({ name, filename }),
                                                        })
                                                        .then(response => {
                                                            if (response.ok) {
                                                                saved = true;
                                                                setTimeout(() => saved = false, 1500);
                                                            } else {
                                                                console.error('Save failed:', response.status);
                                                            }
                                                        })
                                                        .catch(error => console.error('Error:', error))
                                                        .finally(() => saving = false);
                                                    "
                                                />
                                            </div>
                                            <div class="flex items-center px-2 py-1">
                                                <span class="text-xs text-gray-600 filename-text">{{ $fname }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 pt-1">
                                            <span x-show="saving" class="text-xs text-blue-500">Saving...</span>
                                            <span x-show="saved" class="text-xs text-green-500">Saved</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Type + Size --}}
                                <td class="px-3 py-2 whitespace-nowrap align-middle">
                                    <div class="uppercase">{{ $ext }}</div>
                                    <div class="text-[12px] text-gray-400">{{ $fmtSize($doc->size ?? 0) }}</div>
                                </td>

                                {{-- Date --}}
                                <td class="px-3 py-2 whitespace-nowrap align-middle">
                                    {{ $fmtDate($doc->created_at ?? null) }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-3 py-2 text-right whitespace-nowrap align-middle">
                                    @if($url)
                                        <a href="{{ $url }}" download class="text-xs underline mr-3">Download</a>
                                    @else
                                        <span class="text-xs text-gray-400 mr-3">Not found</span>
                                    @endif
                                    <button
                                        type="button"
                                        class="text-xs font-bold"
                                        title="Hide"
                                        @click="confirmHide({{ $doc->id }})"
                                        style="line-height:1;"
                                    >×</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Save All button --}}
        <div class="mt-4 flex justify-end">
            <button 
                type="button" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                x-data="{ savingAll: false }"
                :class="{ 'opacity-50 pointer-events-none': savingAll }"
                x-text="savingAll ? 'Saving...' : 'Save'"
                @click="
                    savingAll = true;
                    const promises = [];
                    document.querySelectorAll('input[name=name]').forEach(input => {
                        const name = input.value;
                        const filename = input.closest('td').querySelector('.filename-text').textContent;
                        const docId = input.closest('tr').id.replace('doc-row-', '');
                        
                        promises.push(
                            fetch('/admin/projects/{{ $record->id }}/documents/' + docId + '/rename', {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: JSON.stringify({ name, filename }),
                            })
                        );
                    });
                    
                    Promise.all(promises)
                        .then(() => {
                            // Show success message
                            alert('All changes saved successfully!');
                        })
                        .catch(error => {
                            console.error('Error saving all:', error);
                            alert('Error saving some changes. Check console for details.');
                        })
                        .finally(() => savingAll = false);
                "
            ></button>
        </div>
    </div>
</div>

{{-- Alpine helpers --}}
<script>
    function projectDocs({ projectId, csrf, startCount }) {
        return {
            projectId,
            csrf,
            docCount: startCount ?? 0,

            async confirmHide(id) {
                if (!confirm('Hide this document?')) return;

                const res = await fetch(`{{ url('/admin/projects') }}/${this.projectId}/documents/${id}/hide`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                    },
                    body: JSON.stringify({}),
                });

                if (res.ok) {
                    const tr = document.getElementById(`doc-row-${id}`);
                    if (tr) tr.remove();
                    if (this.docCount > 0) this.docCount--;
                }
            },
        };
    }

    // helpers exposed for dynamically added rows
    async function renameDoc(projectId, docId, formEl, csrf) {
        const name = formEl.querySelector('input[name="name"]').value || '';
        const filename = formEl.querySelector('input[name="filename"]').value || '';
        await fetch(`/admin/projects/${projectId}/documents/${docId}/rename`, {
            method: 'POST',
            headers: {'X-Requested-With':'XMLHttpRequest','Content-Type':'application/json','X-CSRF-TOKEN': csrf},
            body: JSON.stringify({ name, filename }),
        });
        const ok = formEl.querySelector(`#saved-${docId}`);
        if (ok) { ok.classList.remove('hidden'); setTimeout(()=>ok.classList.add('hidden'), 1500); }
    }
    async function confirmHideDoc(projectId, docId, csrf) {
        if (!confirm('Hide this document?')) return;
        const res = await fetch(`/admin/projects/${projectId}/documents/${docId}/hide`, {
            method: 'POST',
            headers: {'X-Requested-With':'XMLHttpRequest','Content-Type':'application/json','X-CSRF-TOKEN': csrf},
            body: JSON.stringify({}),
        });
        if (res.ok) {
            const tr = document.getElementById(`doc-row-${docId}`);
            if (tr) tr.remove();
        }
    }
    function formatBytes(bytes) {
        bytes = parseInt(bytes || 0, 10);
        if (bytes <= 0) return '0 B';
        const u = ['B','KB','MB','GB','TB'];
        const i = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), u.length - 1);
        return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + u[i];
    }
    function escapeHtml(str) {
        return (str ?? '').toString().replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[s]));
    }
</script>
