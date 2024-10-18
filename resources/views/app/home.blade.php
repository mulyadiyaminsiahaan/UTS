@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Hi, <span class="text-primary">{{ $auth->name }}</span></h3>
                            <a href="{{ route('logout') }}" class="btn btn-primary">Logout</a>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Your Notes</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPinned">Add Note</button>
                        </div>

                        <hr />

                        <div class="row">
                            @if (isset($pinned) && $pinned->count() > 0)
                                @foreach ($pinned as $pinnedItem)
                                    <div class="col-md-4 mb-3 pinned-card" id="card-{{ $pinnedItem->id }}" data-pinned="{{ $pinnedItem->status }}">
                                        <div class="card {{ $pinnedItem->status ? 'border-primary' : '' }}">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $pinnedItem->header }}</h5>
                                                <p class="card-text">{{ $pinnedItem->notes }}</p>
                                                <div class="d-flex justify-content-between">
                                                    <button class="btn btn-sm btn-warning"
                                                            onclick="showModalEditPinned({{ $pinnedItem->id }}, '{{ $pinnedItem->header }}', '{{ $pinnedItem->notes }}', {{ $pinnedItem->status }})">
                                                        Edit
                                                    </button>
                                                    <button class="btn btn-sm btn-danger"
                                                            onclick="showModalDeletePinned({{ $pinnedItem->id }}, '{{ $pinnedItem->header }}')">
                                                        Delete
                                                    </button>
                                                    <button class="btn btn-sm btn-info pinned-btn" onclick="togglePinned({{ $pinnedItem->id }})">
                                                        {{ $pinnedItem->status ? 'Unpin' : 'Pin' }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12 text-center text-muted">No notes available!</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL ADD pinned -->
    <div class="modal fade" id="addPinned" tabindex="-1" aria-labelledby="addPinnedLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPinnedLabel">Tambah Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('post.pinned.add') }}" method="POST" id="addPinnedForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="inputHeader" class="form-label">Header</label>
                            <input type="text" name="header" class="form-control" id="inputHeader" placeholder="Contoh: Pertemuan Hari Senin" required>
                        </div>
                        <div class="mb-3">
                            <label for="inputNotes" class="form-label">Notes</label>
                            <input type="text" name="notes" class="form-control" id="inputNotes" placeholder="Contoh: Dilakukan pukul 08.00" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT pinned -->
    <div class="modal fade" id="editPinned" tabindex="-1" aria-labelledby="editPinnedLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPinnedLabel">Ubah Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('post.pinned.edit') }}" method="POST">
                    @csrf
                    <input name="id" type="hidden" id="inputEditPinnedId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="inputEditHeader" class="form-label">Header</label>
                            <input type="text" name="header" class="form-control" id="inputEditHeader" placeholder="Contoh: Belajar membuat aplikasi website sederhana" required>
                        </div>
                        <div class="mb-3">
                            <label for="inputEditNotes" class="form-label">Notes</label>
                            <input type="text" name="notes" class="form-control" id="inputEditNotes" placeholder="Contoh: Belajar membuat aplikasi website sederhana" required>
                        </div>
                        <div class="mb-3">
                            <label for="selectEditStatus" class="form-label">Status</label>
                            <select class="form-select" name="status" id="selectEditStatus">
                                <option value="0">Belum Selesai</option>
                                <option value="1">Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DELETE pinned -->
    <div class="modal fade" id="deletePinned" tabindex="-1" aria-labelledby="deletePinnedLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePinnedLabel">Hapus data pinned</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        Kamu akan menghapus pinned <strong class="text-danger" id="deletePinnedHeader"></strong>. Apakah kamu yakin?
                    </div>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('post.pinned.delete') }}" method="POST">
                        @csrf
                        <input name="id" type="hidden" id="inputDeletePinnedId">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Tetap Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('other-js')
    <script>
        function showModalEditPinned(pinnedId, header, notes, status) {
            document.getElementById("inputEditPinnedId").value = pinnedId;
            document.getElementById("inputEditHeader").value = header;
            document.getElementById("inputEditNotes").value = notes;
            document.getElementById("selectEditStatus").value = status;

            var myModal = new bootstrap.Modal(document.getElementById("editPinned"));
            myModal.show();
        }

        function showModalDeletePinned(pinnedId, header) {
            document.getElementById("inputDeletePinnedId").value = pinnedId;
            document.getElementById("deletePinnedHeader").innerText = header;

            var myModal = new bootstrap.Modal(document.getElementById("deletePinned"));
            myModal.show();
        }

        function togglePinned(pinnedId) {
            const card = document.getElementById(`card-${pinnedId}`);
            const isPinned = card.dataset.pinned === "1";
            const pinnedBtn = card.querySelector('.pinned-btn');

            fetch(`{{ url('your-toggle-pinned-route') }}/${pinnedId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ status: !isPinned })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    card.dataset.pinned = !isPinned ? "1" : "0";
                    card.classList.toggle('border-primary');
                    pinnedBtn.innerText = !isPinned ? 'Unpin' : 'Pin';

                    // Reorder the cards based on pinned status
                    const parent = card.parentNode;
                    parent.removeChild(card);
                    if (!isPinned) {
                        parent.prepend(card); // Move to top if pinned
                    } else {
                        parent.append(card); // Move to bottom if unpinned
                    }
                }
            });
        }
    </script>
@endsection
