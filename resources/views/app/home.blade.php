@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center"> 
            <div class="col-12">
                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between"> <div>
                            <div>
                                <h3>Hay, <span class="text-primary">{{      
                                $auth->name }}</span></h3>

                            </div>
                        <div>
                            <a href="{{ route('logout') }}"
                            class="btn btn-danger">Logout
                        </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class = "d-flex justify-content-between">
                <div>
                    <h3>Your Note</h3>
                </div>
                <div class="text_end">
                    <button class="btn btn-prymary" data-bs-toggle="modal" data-bs-target="#addPinned">
                        Add Note</button>
                </div>
            </div>

            <hr />

            <table class="table table-striped">
                <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Aktivitas</th>
                <th scope="col">Status</th>
                <th scope="col">Tanggal Dibuat</th>
                <th scope="col">Tindakan</th>
                </tr>
                </thead>
                <tbody>
                @if (isset($pinneds) && sizeof($pinned) > 0)
                @php
                    $counter = 1;
                @endphp
                @foreach ($pinned as $pinned)
                <tr>
                    <td>{{ $counter++ }}</td>
                    <td>{{ $pinned->activity }}</td>
                    <td>
                @if ($pinned->status)
                <span class="badge bg-success">Selesai</span>
                @else
                <span class="badge bg-danger">Belum Selesai</span>
                @endif
                </td>
                    <td>
                    {{ date('d FY H:i', strtotime($pinned->created_at))}}
                    </td>
                <td>
                        <button class="btn btn-sm btn-warning"
                        onclick="showModalEditPinned({{
                        $pinned->id }}, '{{ $pinned->activity }}', {{ $pinned->status }})">
                        Ubah</button>
                        <button class="btn btn-sm btn-danger"
                        onclick="showModalDeletePinned({{
                        $pinned->id }}, '{{ $pinned->activity }}')">Hapus</button>
                </td>
                </tr>
                @endforeach
            @else
            <tr>
            <td colspan="5" class="text-center text-muted">
                Belum ada data tersedia!</td>
            </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
            <!-- MODAL ADD pinned-->
            <div class="modal fade" id="addPinned" tabindex="-1" aria-labelledby="addPinnedLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addPinnedLabel">Tambah Note</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('post.pinned.add') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="inputActivity" class="form-label">Aktivitas</label>
            <input type="text" name="activity" class="form-control" id="inputActivity" placeholder="Contoh: Belajar membuat 
            aplikasi website sederhana">
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Save</button>
                </div>
                                </from>
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
        <form action="{{ route('post.pinned.edit')}}"method="POST">
        @csrf
        <input name="id" type="hidden" id="inputEditPinnedld">
        <div class="modal-body">
            <div class="mb-3">
                <label for="InputEditActivity" class="form-label">Aktivitas</label>
                <input type="text" name="activity" class="form-control" id="inputEditActivity" placeholder = "Contoh: Belajar membuat aplikasi website sederhana">
        </div>
        <div class="mb-3">
            <label for="selectEditStatus" class="form-label">Status</label>
        <select class="form-select" name="status" id="selectEditStatus">
                <option value="0">BelumSelesai</option>
                <option value="1">Selesai</option>
            </select>
        </div>
    </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss = "modal">Batal</button>
        <button type="submit" class="btn btn-primary">Save</button>
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
                    <h5 class="modal-title" id="delete PinnedLabel">Hapus data pinned</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class = "mb-3">
                    kamu akan menghapus pinned
                    <strong class="text-danger" id="deletePinnedActivity"></strong>.
                    Apakah kamu yakin?
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('post.pinned.delete') }}"method="POST">
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
        function showModalEditPinned (PinnedId, activity, status) {
    const modalEditPinned = document.getElementById("editPinned");
    const inputId = document.getElementById("inputEditPinnedId");
    const inputActivity = document.getElementById("inputEditActivity");
    const selectStatus = document.getElementById("selectEditStatus");
    inputId.value = PinnedId;

    inputActivity.value = activity;

    selectStatus.value = status;

    var myModal = new bootstrap.Modal(modalEditPinned)

    myModal.show()
    }
        function showModalDeletePinned (pinnedId, activity) {

        const modalDeletePinned = document.getElementById("deletePinned");

        const elemntActivity = document.getElementById("deletePinnedActivity");

        const inputId = document.getElementById("inputDeletePinnedId");

        inputId.value = PinnedId;

        elemntActivity.innerText = activity;

        var myModal = new bootstrap.Modal(modalDeletePinned) myModal.show()
        }

        </script>

        @endsection